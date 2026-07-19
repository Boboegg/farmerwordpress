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
    $guide = get_page_by_path( "farming-guide", OBJECT, "page" );
    if ( $guide ) {
        $result = wp_update_post(
            array(
                "ID"         => $guide->ID,
                "post_title" => "做農實用指南",
                "post_status"=> "publish",
            ),
            true
        );
    } else {
        $result = wp_insert_post(
            array(
                "post_type"   => "page",
                "post_name"   => "farming-guide",
                "post_title"  => "做農實用指南",
                "post_status" => "publish",
                "post_content"=> "",
            ),
            true
        );
    }
    if ( is_wp_error( $result ) ) {
        fwrite( STDERR, "Unable to create or update farming-guide: " . $result->get_error_message() . "\n" );
        exit( 1 );
    }

    foreach ( array(
        "research-results" => "研究與行動",
        "podcast"          => "聽見田裡的聲音",
    ) as $slug => $title ) {
        $page = get_page_by_path( $slug, OBJECT, "page" );
        if ( ! $page ) {
            fwrite( STDERR, "Required Page is missing: {$slug}\n" );
            exit( 1 );
        }
        $updated = wp_update_post( array( "ID" => $page->ID, "post_title" => $title ), true );
        if ( is_wp_error( $updated ) ) {
            fwrite( STDERR, "Unable to update {$slug}: " . $updated->get_error_message() . "\n" );
            exit( 1 );
        }
    }

    if ( ! taxonomy_exists( "audience" ) ) {
        fwrite( STDERR, "The audience taxonomy is not registered.\n" );
        exit( 1 );
    }

    $audiences = array(
        "new-farmers"         => "剛開始務農",
        "young-farmers"       => "青年農民",
        "experienced-farmers" => "資深農友",
        "general-public"      => "關心農業的一般民眾",
    );
    foreach ( $audiences as $slug => $name ) {
        $term = get_term_by( "slug", $slug, "audience" );
        if ( $term && ! is_wp_error( $term ) ) {
            if ( $name !== $term->name ) {
                $updated = wp_update_term( $term->term_id, "audience", array( "name" => $name ) );
                if ( is_wp_error( $updated ) ) {
                    fwrite( STDERR, "Unable to update audience term {$slug}.\n" );
                    exit( 1 );
                }
            }
            continue;
        }
        $created = wp_insert_term( $name, "audience", array( "slug" => $slug ) );
        if ( is_wp_error( $created ) ) {
            fwrite( STDERR, "Unable to create audience term {$slug}: " . $created->get_error_message() . "\n" );
            exit( 1 );
        }
    }

    echo "Site v3 LocalWP structure synchronized.\n";
'

wp rewrite flush --quiet
