#!/usr/bin/env bash
set -euo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
MYSQL_BIN="${MYSQL_BIN:-$HOME/Library/Application Support/Local/lightning-services/mysql-8.0.35+4/bin/darwin-arm64/bin}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"

export PATH="$MYSQL_BIN:$PATH"
export MYSQL_UNIX_PORT

wp() {
    "$PHP" \
        -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
        -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
        "$WP_CLI" --path="$WP_PATH" "$@"
}

wp eval '
if ( ! function_exists( "fwdignity_topic_registry" ) ) {
    fwrite( STDERR, "People Who Farm topic helpers are unavailable.\n" );
    exit( 1 );
}

$post_topics = array(
    1029 => array( "rural-support", "knowledge-to-field" ),
    1297 => array( "body-workload", "risk-responsibility" ),
    1469 => array( "body-workload", "changing-work" ),
    1499 => array( "body-workload", "risk-responsibility", "knowledge-to-field" ),
    1609 => array( "body-workload", "knowledge-to-field" ),
    1626 => array( "rural-support", "knowledge-to-field" ),
    1633 => array( "body-workload", "changing-work", "rural-support", "risk-responsibility" ),
    1646 => array( "body-workload", "changing-work", "risk-responsibility" ),
    1829 => array( "body-workload", "rural-support", "knowledge-to-field" ),
);

foreach ( $post_topics as $post_id => $term_slugs ) {
    if ( "publish" !== get_post_status( $post_id ) ) {
        fwrite( STDERR, "Expected publish post is unavailable: {$post_id}\n" );
        exit( 1 );
    }
    $result = wp_set_object_terms( $post_id, $term_slugs, "fwd_topic", false );
    if ( is_wp_error( $result ) ) {
        fwrite( STDERR, "Unable to assign topics to post {$post_id}: " . $result->get_error_message() . "\n" );
        exit( 1 );
    }
}

$hub_content = "<p>農業工作發生在身體、家庭、制度與知識之間。這裡從五個問題出發，理解做農的人如何工作、承擔風險，也看見研究與行動如何回到田間。</p>";
$hub = get_page_by_path( "people-who-farm", OBJECT, "page" );
if ( ! $hub ) {
    $hub_id = wp_insert_post(
        array(
            "post_type"    => "page",
            "post_status"  => "publish",
            "post_title"   => "做農的人",
            "post_name"    => "people-who-farm",
            "post_content" => $hub_content,
            "post_excerpt" => "從五個問題出發，理解農業工作者的身體、勞動、家庭、保障與知識。",
        ),
        true
    );
    if ( is_wp_error( $hub_id ) ) {
        fwrite( STDERR, "Unable to create People Who Farm hub: " . $hub_id->get_error_message() . "\n" );
        exit( 1 );
    }
    $hub = get_post( $hub_id );
} else {
    wp_update_post(
        array(
            "ID"          => $hub->ID,
            "post_title"  => "做農的人",
            "post_status" => "publish",
            "post_parent" => 0,
        )
    );
}

$page_content = array(
    "body-workload" => "<p>農務工作常同時面對高溫、重複動作、彎腰負重、農藥暴露與長時間勞動。這些負擔不一定立即成為傷害，卻可能在日復一日的工作中累積。這一頁從田間經驗出發，整理農業工作者的身體風險、可使用的防護知識，以及團隊正在進行的研究與行動。</p>",
    "changing-work" => "<p>農業工作並非一成不變。高齡化、缺工、代耕、有機生產、溫室與新工具，持續改變農務的分工、技能與工作節奏，也可能減輕舊負擔或帶來新的風險。這一頁從現場經驗理解農業工作如何改變。</p>",
    "rural-support" => "<p>農村的日常不只由田間生產構成。家務、照顧、記帳、協調、社群互助與地方服務，同樣維持著農業與生活。這一頁看見女性農民、家庭成員、跨世代夥伴與地方支持網絡所承擔的工作。</p>",
    "risk-responsibility" => "<p>當職傷、災損或收入中斷發生，農民與家庭往往先承受後果，也必須面對認定、申請與舉證。這一頁整理風險如何被分配、制度在哪裡接住或漏接，以及研究與倡議正在追問的問題。</p>",
    "knowledge-to-field" => "<p>資訊被放上網站，不代表它已經進入田間。知識還需要被取得、理解、信任並轉成可以採取的行動。這一頁整理農學堂、Podcast、影音、田野示範與健康識能研究如何連接農民的工作情境。</p>",
);

$featured_posts = array(
    "body-workload"       => 1499,
    "changing-work"       => 1469,
    "rural-support"       => 1626,
    "risk-responsibility" => 1297,
    "knowledge-to-field"  => 1029,
);

foreach ( fwdignity_topic_registry() as $term_slug => $topic ) {
    $path = "people-who-farm/" . $topic["page_slug"];
    $page = get_page_by_path( $path, OBJECT, "page" );
    if ( ! $page ) {
        $page_id = wp_insert_post(
            array(
                "post_type"    => "page",
                "post_status"  => "publish",
                "post_parent"  => $hub->ID,
                "post_title"   => $topic["label"],
                "post_name"    => $topic["page_slug"],
                "post_content" => $page_content[ $term_slug ],
                "post_excerpt" => $topic["core"],
                "menu_order"   => array_search( $term_slug, array_keys( fwdignity_topic_registry() ), true ) + 1,
            ),
            true
        );
        if ( is_wp_error( $page_id ) ) {
            fwrite( STDERR, "Unable to create topic Page {$term_slug}: " . $page_id->get_error_message() . "\n" );
            exit( 1 );
        }
        $page = get_post( $page_id );
    } else {
        wp_update_post(
            array(
                "ID"          => $page->ID,
                "post_parent" => $hub->ID,
                "post_title"  => $topic["label"],
                "post_status" => "publish",
            )
        );
        if ( "" === trim( $page->post_excerpt ) ) {
            wp_update_post(
                array(
                    "ID"           => $page->ID,
                    "post_excerpt" => $topic["core"],
                )
            );
        }
        if ( "" === trim( $page->post_content ) ) {
            wp_update_post(
                array(
                    "ID"           => $page->ID,
                    "post_content" => $page_content[ $term_slug ],
                )
            );
        }
    }

    $featured_post_id = $featured_posts[ $term_slug ];
    update_post_meta( $page->ID, "_fwd_topic_featured_post_id", $featured_post_id );
    if ( ! has_post_thumbnail( $page->ID ) ) {
        $thumbnail_id = get_post_thumbnail_id( $featured_post_id );
        if ( $thumbnail_id ) {
            set_post_thumbnail( $page->ID, $thumbnail_id );
        }
    }
}

// 聯繫資料統一由全站頁尾維護；保留舊頁內容但不公開。
$contact_pages = get_posts(
    array(
        "post_type"      => "page",
        "post_status"    => array( "publish", "draft", "private", "pending", "future" ),
        "name"           => "contact",
        "posts_per_page" => -1,
    )
);
foreach ( $contact_pages as $contact_page ) {
    wp_update_post(
        array(
            "ID"          => $contact_page->ID,
            "post_status" => "draft",
        )
    );
}

flush_rewrite_rules( false );
echo "People Who Farm LocalWP content setup complete.\n";
'

wp post list \
    --post_type=page \
    --post_status=publish \
    --search='做農的人' \
    --fields=ID,post_title,post_name,post_parent \
    --format=table
