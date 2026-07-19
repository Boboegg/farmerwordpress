#!/usr/bin/env bash
set -uo pipefail

# 檔名保留給既有呼叫；斷言內容已更新為清朗田野 3.3.0 × v4。
PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"

checks=0
failures=0

pass() {
    checks=$(( checks + 1 ))
    printf 'PASS  %s\n' "$1"
}

fail() {
    checks=$(( checks + 1 ))
    failures=$(( failures + 1 ))
    printf 'FAIL  %s\n' "$1" >&2
}

assert_equal() {
    local description="$1"
    local expected="$2"
    local actual="$3"

    if [ "$actual" = "$expected" ]; then
        pass "$description"
    else
        fail "$description (expected: $expected; actual: ${actual:-<empty>})"
    fi
}

[ -x "$PHP" ] || { printf 'ENV   LocalWP PHP 不可用：%s\n' "$PHP" >&2; exit 2; }
home_html="$(curl --silent --show-error --fail --insecure --location --connect-timeout 5 --max-time 20 "$BASE_URL/" 2>/dev/null || true)"
[ -n "$home_html" ] || { printf 'ENV   LocalWP 首頁無法讀取\n' >&2; exit 2; }

snapshot="$(printf '%s' "$home_html" | "$PHP" -r '
    $html = stream_get_contents( STDIN );
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    if ( ! $dom->loadHTML( $html ) ) {
        exit( 1 );
    }
    $xpath = new DOMXPath( $dom );
    $text = static function( $node ) {
        return preg_replace( "/\\s+/u", " ", trim( $node ? $node->textContent : "" ) );
    };
    $path = static function( $node ) {
        if ( ! $node ) {
            return "";
        }
        $value = parse_url( $node->getAttribute( "href" ), PHP_URL_PATH );
        return is_string( $value ) ? $value : "";
    };

    $guide = $xpath->query( "//*[@id=\"home-resources\"]" )->item( 0 );
    $guide_heading = $guide ? $xpath->query( ".//h2", $guide )->item( 0 ) : null;
    $guide_link = $guide ? $xpath->query( ".//a[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-home-v07-guide-link \" )]", $guide )->item( 0 ) : null;
    $task_nodes = $guide ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-home-v07-guide-task \" )]", $guide ) : null;
    $task_labels = array();
    if ( $task_nodes ) {
        foreach ( $task_nodes as $task_node ) {
            $task_labels[] = $text( $xpath->query( ".//strong", $task_node )->item( 0 ) );
        }
    }

    $weather = $xpath->query( "//section[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-home-v06-weather \" )]" )->item( 0 );
    $weather_title = $weather ? $xpath->query( ".//*[@id=\"home-weather-title\"]", $weather )->item( 0 ) : null;
    $weather_label = $weather ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-home-v06-weather-status \" )]//strong", $weather )->item( 0 ) : null;
    $weather_message = $weather ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-home-v06-weather-status \" )]//small", $weather )->item( 0 ) : null;

    $footer = $xpath->query( "//footer[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer \" )]" )->item( 0 );
    $footer_columns = $footer ? $xpath->query( ".//div[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer-col \" )]", $footer ) : null;
    $unit_rows = $footer ? $xpath->query( ".//ul[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer-units \" )]/li", $footer ) : null;
    $unit_labels = array();
    $unit_values_complete = true;
    if ( $unit_rows ) {
        foreach ( $unit_rows as $row ) {
            $label = $xpath->query( "./span[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer-unit-label \" )]", $row )->item( 0 );
            $value = $xpath->query( "./span[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer-unit-value \" )]", $row )->item( 0 );
            $unit_labels[] = $text( $label );
            if ( "" === $text( $label ) || "" === $text( $value ) ) {
                $unit_values_complete = false;
            }
        }
    }

    echo "GUIDE_HEADING\t", $text( $guide_heading ), PHP_EOL;
    echo "GUIDE_HREF\t", $path( $guide_link ), PHP_EOL;
    echo "GUIDE_TASKS\t", implode( "|", $task_labels ), PHP_EOL;
    echo "GUIDE_DISABLED\t", $guide ? $xpath->query( ".//*[@aria-disabled=\"true\"]", $guide )->length : 0, PHP_EOL;
    echo "GUIDE_ANCHORS\t", $guide ? $xpath->query( ".//a", $guide )->length : 0, PHP_EOL;
    echo "WEATHER_TITLE\t", $text( $weather_title ), PHP_EOL;
    echo "WEATHER_LABEL_PRESENT\t", "" !== $text( $weather_label ) ? "1" : "0", PHP_EOL;
    echo "WEATHER_MESSAGE_PRESENT\t", "" !== $text( $weather_message ) ? "1" : "0", PHP_EOL;
    echo "WEATHER_LINKS\t", $weather ? $xpath->query( ".//a", $weather )->length : 0, PHP_EOL;
    echo "FOOTER_COLUMNS\t", $footer_columns ? $footer_columns->length : 0, PHP_EOL;
    echo "FOOTER_BRAND\t", $footer ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-footer-brand \" )]", $footer )->length : 0, PHP_EOL;
    echo "FOOTER_CONTACT\t", $footer ? $xpath->query( ".//*[@id=\"footer-contact\"]", $footer )->length : 0, PHP_EOL;
    echo "FOOTER_DUPLICATE_NAV\t", $footer ? $xpath->query( ".//h2[normalize-space(.)=\"網站導覽\" or normalize-space(.)=\"研究與行動\"]", $footer )->length : 0, PHP_EOL;
    echo "UNIT_LABELS\t", implode( "|", $unit_labels ), PHP_EOL;
    echo "UNIT_VALUES_COMPLETE\t", $unit_values_complete ? "1" : "0", PHP_EOL;
    echo "HEADER_FACEBOOK\t", $xpath->query( "//nav[@id=\"fw-navbar\"]//a[@aria-label=\"Facebook\"]" )->length, PHP_EOL;
    echo "HEADER_INSTAGRAM\t", $xpath->query( "//nav[@id=\"fw-navbar\"]//a[@aria-label=\"Instagram\"]" )->length, PHP_EOL;
    echo "FOOTER_FACEBOOK\t", $footer ? $xpath->query( ".//a[@aria-label=\"Facebook\"]", $footer )->length : 0, PHP_EOL;
    echo "FOOTER_INSTAGRAM\t", $footer ? $xpath->query( ".//a[@aria-label=\"Instagram\"]", $footer )->length : 0, PHP_EOL;
')"
[ "$?" -eq 0 ] || { printf 'RESULT PARSE ERROR\n' >&2; exit 2; }

value() {
    local key="$1"
    awk -F '\t' -v key="$key" '$1 == key { sub(/^[^\t]*\t/, ""); print }' <<<"$snapshot"
}

assert_equal '首頁知識區標題正確' '田間知識與資源' "$(value GUIDE_HEADING)"
assert_equal '首頁知識區只連到公開知識主頁' '/farming-guide/' "$(value GUIDE_HREF)"
assert_equal '首頁知識區保留六個核定入口' '身體與健康|農藥與防護|農機與工作環境|經濟、保險與保障|學習與支持|工具與正式資源' "$(value GUIDE_TASKS)"
assert_equal '首頁知識區沒有停用空卡' '0' "$(value GUIDE_DISABLED)"
assert_equal '首頁知識區不洩漏草稿連結' '1' "$(value GUIDE_ANCHORS)"
assert_equal '天氣列使用核定標題' '今天民雄鄉天氣預報' "$(value WEATHER_TITLE)"
assert_equal '天氣列有狀態標題' '1' "$(value WEATHER_LABEL_PRESENT)"
assert_equal '天氣列有完整訊息' '1' "$(value WEATHER_MESSAGE_PRESENT)"
assert_equal '天氣列沒有額外 CTA' '0' "$(value WEATHER_LINKS)"
assert_equal '頁尾只保留品牌與聯絡兩欄' '2' "$(value FOOTER_COLUMNS)"
assert_equal '頁尾品牌欄存在' '1' "$(value FOOTER_BRAND)"
assert_equal '頁尾聯絡欄存在' '1' "$(value FOOTER_CONTACT)"
assert_equal '頁尾不重複網站導覽或研究導覽' '0' "$(value FOOTER_DUPLICATE_NAV)"
assert_equal '三個單位標籤順序正確' '指導單位|計畫主持單位|合作夥伴' "$(value UNIT_LABELS)"
assert_equal '單位標籤與名稱皆使用獨立且非空欄位' '1' "$(value UNIT_VALUES_COMPLETE)"
assert_equal '頁首 Facebook 連結存在' '1' "$(value HEADER_FACEBOOK)"
# 2026-07-17：Bobo 提供真實帳號 https://www.instagram.com/fwdignity，斷言改回「應存在」；
# 裸網域佔位值仍由 fwdignity_get_social_url() 過濾。
assert_equal '頁首 Instagram 連結存在' '1' "$(value HEADER_INSTAGRAM)"
assert_equal '頁尾 Facebook 連結存在' '1' "$(value FOOTER_FACEBOOK)"
assert_equal '頁尾 Instagram 連結存在' '1' "$(value FOOTER_INSTAGRAM)"

printf '\nRESULT %s（%d failed / %d checks）\n' \
    "$([ "$failures" -eq 0 ] && printf 'PASS' || printf 'FAIL')" \
    "$failures" \
    "$checks"

[ "$failures" -eq 0 ]
