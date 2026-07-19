#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
MODEL="$ROOT_DIR/themes/fwdignity/inc/guide-content.php"
TEMPLATE="$ROOT_DIR/themes/fwdignity/page-farming-guide.php"
CSS="$ROOT_DIR/themes/fwdignity/assets/css/page-farming-guide.css"
FUNCTIONS="$ROOT_DIR/themes/fwdignity/functions.php"
LOCAL_PHP="$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php"
PHP="${PHP:-$LOCAL_PHP}"

if [ ! -x "$PHP" ]; then
    PHP="$(command -v php || true)"
fi

fail() {
    printf 'FAIL: %s\n' "$1" >&2
    exit 1
}

for file in "$MODEL" "$TEMPLATE" "$CSS"; do
    [ -f "$file" ] || fail "required file is missing: ${file#$ROOT_DIR/}"
done

[ -n "$PHP" ] || fail 'PHP executable is unavailable'

"$PHP" -l "$MODEL" >/dev/null
"$PHP" -l "$TEMPLATE" >/dev/null

"$PHP" -r '
    $model    = file_get_contents( $argv[1] );
    $template = file_get_contents( $argv[2] );
    $css      = file_get_contents( $argv[3] );

    $slugs = array(
        "farmer-study",
        "subpage-heat",
        "personal-protective-equipment",
        "labor-saving-machinery",
        "greenhouse-zone",
        "subpage-pesticide",
        "subpage-msd",
        "subpage-mental",
        "occupational-accident-insurance",
        "crop-insurance",
        "agricultural-subsidy-resources",
        "worker-occupational-accident-calculator",
        "farmer-worker-retirement-calculator",
    );

    foreach ( $slugs as $slug ) {
        if ( 1 !== substr_count( $model, "\x27" . $slug . "\x27" ) ) {
            fwrite( STDERR, "guide slug must occur exactly once in the model: {$slug}\n" );
            exit( 1 );
        }
    }

    foreach ( array(
        "身體與健康",
        "農藥與防護",
        "農機與工作環境",
        "經濟、保險與保障",
        "學習與支持",
        "工具與正式資源",
        "教材",
        "檢核表",
        "試算工具",
    ) as $label ) {
        if ( false === strpos( $model, $label ) ) {
            fwrite( STDERR, "guide model label is missing: {$label}\n" );
            exit( 1 );
        }
    }

    foreach ( array(
        "guide-content.php",
        "fwdignity_get_farming_guide_catalog",
        "fwdignity_get_farming_guide_task_index",
        "fwdignity_get_public_farming_guide_resources",
        "內容整理中",
    ) as $contract ) {
        if ( false === strpos( $template, $contract ) ) {
            fwrite( STDERR, "template contract is missing: {$contract}\n" );
            exit( 1 );
        }
    }

    if ( preg_match( "/\\b(the_content|get_post_field|post_content)\\b/", $template ) ) {
        fwrite( STDERR, "guide template must not copy or render professional Page bodies\n" );
        exit( 1 );
    }
    if ( preg_match( "/href=[^>]*(subpage-|personal-protective|labor-saving|greenhouse|insurance|subsidy|calculator)/i", $template ) ) {
        fwrite( STDERR, "guide destinations must come from queried Page permalinks\n" );
        exit( 1 );
    }

    if ( substr_count( $css, "{" ) !== substr_count( $css, "}" ) ) {
        fwrite( STDERR, "farming guide CSS braces are unbalanced\n" );
        exit( 1 );
    }
    if ( preg_match( "/#[0-9a-f]{3,8}\\b|(?:rgb|hsl)a?\\s*\\(/i", $css ) ) {
        fwrite( STDERR, "farming guide CSS contains a non-token color\n" );
        exit( 1 );
    }
    if ( preg_match_all( "/var\\(\\s*(--[a-z0-9-]+)/i", $css, $variables ) ) {
        foreach ( $variables[1] as $variable ) {
            if ( 0 !== strpos( $variable, "--fw-" ) ) {
                fwrite( STDERR, "non-canonical CSS variable used: {$variable}\n" );
                exit( 1 );
            }
        }
    }
    if ( preg_match( "/(^|[},])\\s*(?::root|html|body|\\*)\\b/m", $css ) ) {
        fwrite( STDERR, "farming guide CSS contains an unscoped global selector\n" );
        exit( 1 );
    }
    if ( preg_match( "/opacity\\s*:\\s*0(?:[;}]|\\s*!important)/i", $css ) ) {
        fwrite( STDERR, "farming guide content must be visible by default\n" );
        exit( 1 );
    }
    if ( preg_match( "/border-radius\\s*:\\s*(?:[7-9]|[1-9][0-9])px/i", $css ) ) {
        fwrite( STDERR, "farming guide radius exceeds 6px\n" );
        exit( 1 );
    }
    if ( preg_match( "/background(?:-color)?\\s*:\\s*var\\(--fw-color-(?:footer-background|primary-deep|ink)\\)/i", $css ) ) {
        fwrite( STDERR, "farming guide contains a dark background band\n" );
        exit( 1 );
    }
' "$MODEL" "$TEMPLATE" "$CSS"

"$PHP" -r '
    define( "ABSPATH", __DIR__ );

    final class WP_Post {
        public int $ID;
        public string $post_name;
        public string $post_status;
        public string $post_title;

        public function __construct( int $id, string $slug, string $status, string $title ) {
            $this->ID          = $id;
            $this->post_name   = $slug;
            $this->post_status = $status;
            $this->post_title  = $title;
        }
    }

    $GLOBALS["guide_query_args"] = array();
    $GLOBALS["guide_posts"] = array(
        new WP_Post( 100, "farmer-study", "publish", "農學堂" ),
        new WP_Post( 101, "subpage-heat", "publish", "資料庫熱傷害頁名" ),
        new WP_Post( 102, "personal-protective-equipment", "publish", "資料庫防護具頁名" ),
        new WP_Post( 103, "labor-saving-machinery", "publish", "資料庫農機頁名" ),
        new WP_Post( 104, "subpage-pesticide", "draft", "不可公開的草稿頁名" ),
        new WP_Post( 105, "worker-occupational-accident-calculator", "publish", "資料庫職災試算頁名" ),
        new WP_Post( 106, "farmer-worker-retirement-calculator", "publish", "資料庫退休試算頁名" ),
    );

    function sanitize_title( $value ) {
        return strtolower( trim( (string) $value ) );
    }

    function get_posts( $args ) {
        $GLOBALS["guide_query_args"] = $args;
        return $GLOBALS["guide_posts"];
    }

    function get_permalink( $post ) {
        return "https://example.test/" . $post->post_name . "/";
    }

    function get_the_title( $post ) {
        return $post->post_title;
    }

    require $argv[1];

    $catalog = fwdignity_get_farming_guide_catalog();
    $tasks   = fwdignity_get_farming_guide_task_index( $catalog );
    $tools   = fwdignity_get_public_farming_guide_resources( $catalog );

    $statuses = $GLOBALS["guide_query_args"]["post_status"] ?? array();
    sort( $statuses );
    if ( array( "draft", "publish" ) !== $statuses ) {
        fwrite( STDERR, "guide query must retain draft Pages in its data model\n" );
        exit( 1 );
    }
    if ( 13 !== count( $catalog["items"] ?? array() ) ) {
        fwrite( STDERR, "guide catalog must contain all 13 configured Pages\n" );
        exit( 1 );
    }
    if ( 6 !== count( $tasks ) ) {
        fwrite( STDERR, "guide task index must preserve all six approved task groups\n" );
        exit( 1 );
    }
    if ( "draft" !== $catalog["items"]["subpage-pesticide"]["post_status"] ) {
        fwrite( STDERR, "draft Page was not retained in the catalog\n" );
        exit( 1 );
    }

    $public_items = array();
    foreach ( array_merge( $tasks, $tools ) as $group ) {
        foreach ( $group["items"] as $item ) {
            $public_items[] = $item;
        }
    }
    $public_slugs = array_column( $public_items, "slug" );
    if ( in_array( "subpage-pesticide", $public_slugs, true ) || in_array( "greenhouse-zone", $public_slugs, true ) ) {
        fwrite( STDERR, "draft or missing Pages leaked into public guide groups\n" );
        exit( 1 );
    }
    if ( ! in_array( "資料庫熱傷害頁名", array_column( $public_items, "title" ), true ) ) {
        fwrite( STDERR, "public guide titles must come from queried WordPress Pages\n" );
        exit( 1 );
    }

    echo "farming guide model behavior passed\n";
' "$MODEL"

printf 'farming guide template tests passed\n'

# 2026-07-17 W2-C：頁面 CSS 改由 functions.php 資產對照表統一 enqueue（修 F10 載入順序）。
grep -Fq "'fwdignity-page-farming-guide'" "$FUNCTIONS" || { echo "functions.php missing farming-guide asset map entry" >&2; exit 1; }
grep -Fq "'fwdignity-page-farmer-study'" "$FUNCTIONS" || { echo "functions.php missing farmer-study asset map entry" >&2; exit 1; }
