#!/usr/bin/env bash
set -uo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"

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

wp() {
    "$PHP" \
        -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
        -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
        "$WP_CLI" --path="$WP_PATH" "$@"
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

preflight_failed=0
for required_path in "$PHP" "$WP_CLI" "$WP_PATH/wp-load.php"; do
    if [ ! -e "$required_path" ]; then
        printf 'ENV   缺少 LocalWP 必要路徑：%s\n' "$required_path" >&2
        preflight_failed=1
    fi
done

if [ ! -S "$MYSQL_UNIX_PORT" ]; then
    printf 'ENV   LocalWP MySQL socket 不可用：%s\n' "$MYSQL_UNIX_PORT" >&2
    preflight_failed=1
fi

if [ "$preflight_failed" -ne 0 ] || ! wp core is-installed >/dev/null 2>&1; then
    printf 'RESULT ENVIRONMENT ERROR（未執行 v4 驗收）\n' >&2
    exit 2
fi

home_html="$(curl --silent --show-error --fail --insecure --location --connect-timeout 5 --max-time 20 "$BASE_URL/" 2>/dev/null || true)"
if [ -z "$home_html" ]; then
    printf 'ENV   LocalWP 首頁沒有回傳 HTML：%s/\n' "$BASE_URL" >&2
    exit 2
fi

nav_snapshot="$(printf '%s' "$home_html" | "$PHP" -r '
    $html = stream_get_contents( STDIN );
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    if ( ! $dom->loadHTML( $html ) ) {
        exit( 1 );
    }
    $xpath = new DOMXPath( $dom );
    $menus = $xpath->query( "//nav[@id=\"fw-navbar\"]//ul[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-nav-menu \" )]" );
    if ( ! $menus || 0 === $menus->length ) {
        exit( 1 );
    }
    $menu = $menus->item( 0 );
    $top = array();
    $children = array();
    foreach ( $xpath->query( "./li", $menu ) as $item ) {
        $anchors = $xpath->query( "./a[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-nav-link \" )]", $item );
        if ( ! $anchors || 0 === $anchors->length ) {
            continue;
        }
        $label = preg_replace( "/\\s+/u", " ", trim( $anchors->item( 0 )->textContent ) );
        $top[] = $label;
        $submenu = array();
        foreach ( $xpath->query( "./ul[contains(concat(\" \", normalize-space(@class), \" \"), \" fw-dropdown \" )]/li/a", $item ) as $child ) {
            $submenu[] = preg_replace( "/\\s+/u", " ", trim( $child->textContent ) );
        }
        $children[ $label ] = implode( "|", $submenu );
    }
    echo "TOP\t", implode( "|", $top ), PHP_EOL;
    foreach ( $children as $label => $submenu ) {
        echo $label, "\t", $submenu, PHP_EOL;
    }
')"
nav_status=$?

if [ "$nav_status" -eq 0 ]; then
    pass '可解析 LocalWP 現行主導覽'
else
    fail '可解析 LocalWP 現行主導覽'
fi

top_labels="$(awk -F '\t' '$1 == "TOP" { sub(/^[^\t]*\t/, ""); print }' <<<"$nav_snapshot")"
home_children="$(awk -F '\t' '$1 == "首頁" { sub(/^[^\t]*\t/, ""); print }' <<<"$nav_snapshot")"
field_children="$(awk -F '\t' '$1 == "田野誌" { sub(/^[^\t]*\t/, ""); print }' <<<"$nav_snapshot")"
research_children="$(awk -F '\t' '$1 == "研究與行動" { sub(/^[^\t]*\t/, ""); print }' <<<"$nav_snapshot")"
about_children="$(awk -F '\t' '$1 == "認識尊嚴農業" { sub(/^[^\t]*\t/, ""); print }' <<<"$nav_snapshot")"

assert_equal '六個 v4 頂層入口與順序正確' '首頁|田野誌|聽見田裡的聲音|田間知識與資源|研究與行動|認識尊嚴農業' "$top_labels"
assert_equal '首頁子選單只收全部內容' '全部內容' "$home_children"
assert_equal '田野誌子選單正確' '田野紀錄|做農的人' "$field_children"
assert_equal '研究與行動子選單正確' '研究出版|相關資源' "$research_children"
assert_equal '認識尊嚴農業子選單正確' '研究團隊' "$about_children"

if [[ "|$top_labels|" == *'|最新消息|'* ]] || [[ "|$top_labels|" == *'|做農實用指南|'* ]] || [[ "|$top_labels|" == *'|田野與培力|'* ]]; then
    fail '主導覽不含 v3 舊名稱'
else
    pass '主導覽不含 v3 舊名稱'
fi

if wp eval '
    $expected = array(
        "thenews"                                => "全部內容",
        "research-results"                       => "研究與行動",
        "research-results/related-resources"     => "相關資源",
        "podcast"                                => "聽見田裡的聲音",
        "research-results/research-publications" => "研究出版",
        "about/researchteam"                     => "研究團隊",
        "home"                                   => "首頁",
        "about"                                  => "認識尊嚴農業",
        "farmer-study"                           => "農學堂",
        "field-learning"                         => "田野誌",
        "field-notes"                            => "田野紀錄",
        "people-who-farm"                        => "做農的人",
        "people-who-farm/body-and-farm-work"     => "身體如何承受農務？",
        "people-who-farm/changing-farm-work"     => "農業工作正在怎麼改變？",
        "people-who-farm/rural-support"           => "誰在撐起農村？",
        "people-who-farm/risk-and-responsibility" => "風險由誰承擔？",
        "people-who-farm/knowledge-in-the-field" => "知識如何走進田間？",
        "farming-guide"                          => "田間知識與資源",
    );
    foreach ( $expected as $page_path => $title ) {
        $page = get_page_by_path( $page_path, OBJECT, "page" );
        if ( ! $page instanceof WP_Post || "publish" !== $page->post_status || $title !== $page->post_title ) {
            fwrite( STDERR, "頁面狀態或名稱錯誤：{$page_path} / {$title}\n" );
            exit( 1 );
        }
    }
' >/dev/null; then
    pass '18 個現行 Page 的路徑、名稱與公開狀態正確'
else
    fail '18 個現行 Page 的路徑、名稱與公開狀態正確'
fi

assert_equal '公開文章數為 25' '25' "$(wp post list --post_type=post --post_status=publish --format=count 2>/dev/null)"
assert_equal '草稿文章數為 1' '1' "$(wp post list --post_type=post --post_status=draft --format=count 2>/dev/null)"
assert_equal '垃圾桶文章數為 24' '24' "$(wp post list --post_type=post --post_status=trash --format=count 2>/dev/null)"
assert_equal '公開頁面數為 18' '18' "$(wp post list --post_type=page --post_status=publish --format=count 2>/dev/null)"

assert_equal '現行主題為 fwdignity' 'fwdignity' "$(wp option get stylesheet 2>/dev/null)"
assert_equal '主題 metadata 版本為 3.3.0' '3.3.0' "$(wp theme get fwdignity --field=version 2>/dev/null)"

initiative_status="$(curl --silent --show-error --insecure --location --output /dev/null --write-out '%{http_code}' "$BASE_URL/dignity-farming-initiative/" 2>/dev/null || true)"
assert_equal '尊嚴農業倡議維持隱藏並回應 404' '404' "$initiative_status"

published_routes="$(wp eval '
    $pages = get_posts(
        array(
            "post_type"      => "page",
            "post_status"    => "publish",
            "posts_per_page" => -1,
            "orderby"        => "ID",
            "order"          => "ASC",
        )
    );
    foreach ( $pages as $page ) {
        echo $page->ID, "\t", get_permalink( $page ), "\n";
    }
' 2>/dev/null)"
bad_routes=()
while IFS=$'\t' read -r page_id page_url; do
    [ -n "$page_id" ] || continue
    page_code="$(curl --silent --show-error --insecure --location --connect-timeout 5 --max-time 20 --output /dev/null --write-out '%{http_code}' "$page_url" 2>/dev/null || true)"
    if [ "$page_code" != '200' ]; then
        bad_routes+=( "$page_id:$page_code" )
    fi
done <<<"$published_routes"

if [ "${#bad_routes[@]}" -eq 0 ]; then
    pass '所有公開 Page 網址均回應 200'
else
    fail "所有公開 Page 網址均回應 200（異常：${bad_routes[*]}）"
fi

printf '\nRESULT %s（%d failed / %d checks）\n' \
    "$([ "$failures" -eq 0 ] && printf 'PASS' || printf 'FAIL')" \
    "$failures" \
    "$checks"

if [ "$failures" -ne 0 ]; then
    exit 1
fi
