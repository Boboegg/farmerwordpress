#!/usr/bin/env bash
set -euo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"

"$PHP" \
    -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
    -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
    "$WP_CLI" --path="$WP_PATH" eval '
        $titles = array(
            "research-results" => "從研究到行動",
            "about"            => "認識尊嚴農業",
        );
        foreach ( $titles as $slug => $title ) {
            $page = get_page_by_path( $slug, OBJECT, "page" );
            if ( ! $page ) {
                fwrite( STDERR, "Required Page is missing: {$slug}\n" );
                exit( 1 );
            }
            $result = wp_update_post(
                array(
                    "ID"         => $page->ID,
                    "post_title" => $title,
                ),
                true
            );
            if ( is_wp_error( $result ) ) {
                fwrite( STDERR, "Unable to update {$slug}: " . $result->get_error_message() . "\n" );
                exit( 1 );
            }
        }
        echo "Research and About landing Page titles updated.\n";
    '
