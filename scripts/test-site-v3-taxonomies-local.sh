#!/usr/bin/env bash
set -euo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"

wp() {
    "$PHP" \
        -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
        -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
        "$WP_CLI" --path="$WP_PATH" "$@"
}

wp eval '
    $taxonomies = array(
        "content_type" => array(
            "news"       => "消息文章",
            "field-note" => "田野紀錄",
            "training"   => "農學堂",
            "podcast"    => "Podcast",
            "video"      => "影片",
            "guide"      => "田間知識",
            "research"   => "研究與倡議",
            "resource"   => "外部資源",
            "tool"       => "互動工具",
        ),
        "domain" => array(
            "body-health"                => "身體與健康",
            "pesticide-protection"       => "農藥與防護",
            "machinery-work-environment" => "農機與工作環境",
            "economy-insurance-security" => "經濟、保險與保障",
            "learning-support"           => "學習與支持",
            "tools-official-resources"   => "工具與正式資源",
        ),
        "task" => array(
            "identify-risk"               => "辨識風險",
            "prepare-work"                => "作業前準備",
            "choose-protection-equipment" => "選擇防護或設備",
            "respond-warning-incident"    => "處理警訊或事故",
            "understand-rights-coverage"  => "了解保障與權益",
            "apply-resources"             => "申請資源",
            "use-tools"                   => "使用工具",
            "find-learning-support"       => "找課程與支持",
        ),
        "audience" => array(
            "general-public"         => "一般民眾",
            "agricultural-workers"   => "農業工作者",
            "academic-professionals" => "學術與專業者",
        ),
        "context" => array(
            "heat-outdoor-work"              => "高溫與戶外作業",
            "greenhouse-work"                 => "溫室作業",
            "machinery-operation"             => "農機操作",
            "pesticide-application"           => "農藥施作",
            "lifting-repetitive-work"         => "搬運與重複動作",
            "protective-equipment"            => "防護裝備選用",
            "incident-occupational-injury"    => "事故與職災處理",
            "insurance-subsidy-application"   => "保險或補助申請",
        ),
        "fwd_topic" => array(
            "body-workload"       => "身體如何承受農務？",
            "changing-work"       => "農業工作正在怎麼改變？",
            "rural-support"       => "誰在撐起農村？",
            "risk-responsibility" => "風險由誰承擔？",
            "knowledge-to-field"  => "知識如何走進田間？",
        ),
    );

    foreach ( $taxonomies as $taxonomy => $terms ) {
        if ( ! taxonomy_exists( $taxonomy ) ) {
            fwrite( STDERR, "Missing taxonomy: {$taxonomy}\n" );
            exit( 1 );
        }

        $object = get_taxonomy( $taxonomy );
        if ( ! $object || $object->public || $object->publicly_queryable || ! $object->show_ui || ! $object->show_in_rest ) {
            fwrite( STDERR, "Taxonomy governance mismatch: {$taxonomy}\n" );
            exit( 1 );
        }

        foreach ( $terms as $slug => $name ) {
            $term = get_term_by( "slug", $slug, $taxonomy );
            if ( ! $term || is_wp_error( $term ) || $name !== $term->name ) {
                fwrite( STDERR, "Missing or mismatched term: {$taxonomy}/{$slug}\n" );
                exit( 1 );
            }
        }
    }

    if ( get_term_by( "slug", "public", "audience" ) ) {
        fwrite( STDERR, "Retired audience synonym still exists: audience/public\n" );
        exit( 1 );
    }

    echo "Site v3 taxonomy checks passed\n";
'
