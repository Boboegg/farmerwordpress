<?php
declare(strict_types=1);

/**
 * Import four source-traced public Facebook posts into the LocalWP site.
 *
 * Run with WP-CLI eval-file. The source URL is the idempotency key; existing
 * posts are never overwritten.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$base_dir = __DIR__ . '/images';
$items = array(
    array(
        'title'        => '季工作會議籌備：走訪打貓果菜生產合作社',
        'slug'         => 'seasonal-workshop-field-visit-2026',
        'date'         => '2026-06-05 00:00:00',
        'field_date'   => '2026-06-05',
        'source'       => 'https://www.facebook.com/fwdignity/posts/pfbid02eKgwF5TFjQY1Z4kh8qj3qLNY5sGYzkbH22hRu1W46wcXQG6cuNhTXh3W2evQwaaNl',
        'image'        => $base_dir . '/2026-06-05-seasonal-workshop-field-visit.jpg',
        'alt'          => '尊嚴農業團隊走訪打貓果菜生產合作社籌備季工作會議',
        'categories'   => array( 'event', 'education-farmer' ),
        'topic'        => 'knowledge-to-field',
        'content_type' => 'field-note',
        'excerpt'      => '今年七月底我們即將和同樣參與國科會「人文創新與社會實踐」計畫的嘉義大學團隊共同主辦季工作會議，中正與嘉大兩校的團隊都正在緊鑼密鼓的籌備！',
        'paragraphs'   => array(
            '今年七月底我們即將和同樣參與國科會「人文創新與社會實踐」計畫的嘉義大學團隊共同主辦季工作會議，中正與嘉大兩校的團隊都正在緊鑼密鼓的籌備！',
            '慣例上，每半年我們就會到其他參與這項計劃的學校，由地主學校帶領參訪實踐場域，並且讓各校一起分享彼此的研究和實踐。',
            '今天我們拜訪了鄰近中正大學的 #打貓果菜生產合作社 ，討論怎麼樣為來自各地的團隊帶來最完整的體驗，非常感謝合作社主席 #陳映延先生 、 旺萊山 #陳俊翰 廠長 與 ameipineapple 阿美鳳梨熱情協助，請大家敬請期待唷！',
        ),
    ),
    array(
        'title'        => '到民雄鄉農會分享計畫成果與農業職業傷病議題',
        'slug'         => 'minxiong-results-sharing-2026',
        'date'         => '2026-05-12 00:00:00',
        'field_date'   => '2026-05-12',
        'source'       => 'https://www.facebook.com/fwdignity/posts/pfbid0K1QwLZvPngbYWS8JtQmvVik9PagshZ1K8wxSyk6D2tzXg7xejJqLn2hzhzFeSQeal',
        'image'        => $base_dir . '/2026-05-12-minxiong-results-sharing.jpg',
        'alt'          => '尊嚴農業團隊到民雄鄉農會分享計畫成果',
        'categories'   => array( 'event', 'education-farmer', 'health' ),
        'topic'        => 'body-workload',
        'content_type' => 'training',
        'excerpt'      => '今天早上，「尊嚴農業」團隊執行長管中祥教授、謝秉儒醫師受「財團法人職業預防及重建中心」邀請，到民雄鄉農會和農友分享計畫成果，以及農業職業傷病與農職保議題。',
        'paragraphs'   => array(
            '今天早上，「尊嚴農業」團隊執行長管中祥教授、謝秉儒醫師受「財團法人職業預防及重建中心」邀請，到民雄鄉農會和農友分享計畫成果，以及農業職業傷病與農職保議題。',
            '管中祥除了介紹「尊嚴農業」團隊工作內容及近期發現，也特別感謝民雄鄉農會、民雄青農聯誼會、打貓合作社，以及農民朋友們的協助。管中祥在演講中提到農業及農業工作者真實處境，包括面臨的天候、多重勞動、性別、職業傷病、身心狀況等問題時，引起不少農友共鳴。',
            '謝秉儒醫師則介紹不同類型的農民保險，提到農業傷病的不同型態與申請程序。謝醫師也說明農業傷病的預防方法，並在現場實際示範簡易動作，和農友一起學習保健之道。',
        ),
    ),
    array(
        'title'        => '中正E報：《風吹過田埂時　在她與土地之間》報導',
        'slug'         => 'women-farmers-field-report-2026',
        'date'         => '2026-06-24 13:39:00',
        'field_date'   => '2026-06-24',
        'source'       => 'https://www.facebook.com/fwdignity/posts/pfbid0c21ybUCNgJ2RN4g1Au4aWKrPHzBYTZ9Pp19ZvckPdHukzBeUggKUzuXHuPMLXuhyl',
        'image'        => $base_dir . '/2026-06-24-field-report-women-farmers.jpg',
        'alt'          => '中正E報採訪團隊走進田間拍攝女農工作樣貌',
        'categories'   => array( 'press', 'event' ),
        'topic'        => 'rural-support',
        'content_type' => 'field-note',
        'excerpt'      => '前些陣子，尊嚴農業計劃團隊接受記者賴庭儀、張郁琪的專訪，並引領他們走進田間，拜訪女農紀子琳，一起在倉庫聊天，也到田裡實地拍攝工作的樣貌。',
        'paragraphs'   => array(
            '中正E報：《風吹過田埂時　在她與土地之間》報導',
            '前些陣子，尊嚴農業計劃團隊接受記者賴庭儀、張郁琪的專訪，並引領他們走進田間，拜訪女農紀子琳，一起在倉庫聊天，也到田裡實地拍攝工作的樣貌。事實上，兩位記者早在去年12月，就已經出現在第一次農學堂的現場，聽完兩位女農的分享後，他們發現女農原來跟自己所想的很不一樣。因此，兩位記者便共同投入製作本篇專題報導，藉由多次的訪談、資料整理以及拍攝，完成了這部影音報導作品，真的很用心呢！',
            '影片連結下收留言，一起來關心民雄女農吧！',
        ),
    ),
    array(
        'title'        => '恭賀嚴佳祥先生榮獲第八屆全國百大青農',
        'slug'         => 'young-farmer-award-2026',
        'date'         => '2026-06-26 11:31:00',
        'field_date'   => '2026-06-26',
        'source'       => 'https://www.facebook.com/fwdignity/posts/pfbid02nPKwr8sJrG69hLG86QCYmdDW8cfeDUVcmmjQyP5ZMzni51qStfr7uPGi1FMzhcYCl',
        'image'        => $base_dir . '/2026-06-26-young-farmer-award.jpg',
        'alt'          => '民雄鄉青農聯誼會會長嚴佳祥獲得第八屆全國百大青農',
        'categories'   => array( 'news', 'farmer-stories-production' ),
        'topic'        => 'rural-support',
        'content_type' => 'news',
        'excerpt'      => '恭賀民雄鄉青農聯誼會會長嚴佳祥先生榮獲第八屆全國百大青農！',
        'paragraphs'   => array(
            '恭賀民雄鄉青農聯誼會會長嚴佳祥先生榮獲第八屆全國百大青農！',
            '嚴會長十分關注農友們的職業安全和身心健康，一年來帶領青農聯誼會和尊嚴農業團隊有諸多合作，從辦理講座、拍攝影片、訪談、健康檢查等等，可說是無役不與，熱血、戮力協助團隊順利進行各項工作。',
            '嚴會長甚至在參與了農學堂聽見女農心聲以後，便馬上辦理了支持女農身心的「婦酬者聯盟」活動。',
            '本次嚴佳祥會長獲獎實至名歸，尊嚴農業團隊同感驕傲與歡喜！期許有越來越多的朋友和我們一起繼續加油、努力，愛護農民！',
        ),
    ),
);

$results = array();
foreach ( $items as $item ) {
    $existing = get_posts(
        array(
            'post_type'      => 'post',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_fwd_facebook_source_url',
            'meta_value'     => $item['source'],
        )
    );
    if ( $existing ) {
        $results[] = array( 'status' => 'skipped', 'post_id' => (int) $existing[0], 'title' => $item['title'] );
        continue;
    }

    $category_ids = array();
    foreach ( $item['categories'] as $category_slug ) {
        $term = get_category_by_slug( $category_slug );
        if ( ! $term instanceof WP_Term ) {
            $results[] = array( 'status' => 'error', 'title' => $item['title'], 'message' => '缺少分類：' . $category_slug );
            continue 2;
        }
        $category_ids[] = (int) $term->term_id;
    }

    if ( ! is_readable( $item['image'] ) ) {
        $results[] = array( 'status' => 'error', 'title' => $item['title'], 'message' => '找不到照片：' . $item['image'] );
        continue;
    }

    $paragraphs = array_map(
        static function ( $paragraph ) {
            return '<p>' . esc_html( $paragraph ) . '</p>';
        },
        $item['paragraphs']
    );
    $paragraphs[] = '<p><a href="' . esc_url( $item['source'] ) . '" target="_blank" rel="noopener noreferrer">查看 Facebook 原始貼文</a></p>';

    $post_id = wp_insert_post(
        array(
            'post_type'     => 'post',
            'post_status'   => 'draft',
            'post_title'    => $item['title'],
            'post_name'     => $item['slug'],
            'post_date'     => $item['date'],
            'post_excerpt'  => $item['excerpt'],
            'post_content'  => implode( "\n", $paragraphs ),
            'post_category' => $category_ids,
        ),
        true
    );
    if ( is_wp_error( $post_id ) ) {
        $results[] = array( 'status' => 'error', 'title' => $item['title'], 'message' => $post_id->get_error_message() );
        continue;
    }

    update_post_meta( $post_id, '_fwd_facebook_source_url', esc_url_raw( $item['source'] ) );
    update_post_meta( $post_id, '_fwdignity_field_date', $item['field_date'] );
    wp_set_object_terms( $post_id, array( $item['topic'] ), 'fwd_topic', false );
    wp_set_object_terms( $post_id, array( $item['content_type'] ), 'content_type', false );

    $temporary_file = wp_tempnam( basename( $item['image'] ) );
    if ( ! $temporary_file || ! copy( $item['image'], $temporary_file ) ) {
        wp_delete_post( $post_id, true );
        $results[] = array( 'status' => 'error', 'title' => $item['title'], 'message' => '無法準備照片暫存檔' );
        continue;
    }

    $attachment_id = media_handle_sideload(
        array(
            'name'     => basename( $item['image'] ),
            'tmp_name' => $temporary_file,
        ),
        $post_id,
        $item['alt']
    );
    if ( is_wp_error( $attachment_id ) ) {
        @unlink( $temporary_file );
        wp_delete_post( $post_id, true );
        $results[] = array( 'status' => 'error', 'title' => $item['title'], 'message' => $attachment_id->get_error_message() );
        continue;
    }

    update_post_meta( $attachment_id, '_wp_attachment_image_alt', $item['alt'] );
    set_post_thumbnail( $post_id, $attachment_id );
    $publish_result = wp_update_post(
        array(
            'ID'            => $post_id,
            'post_status'   => 'publish',
            'post_date'     => $item['date'],
            'post_date_gmt' => get_gmt_from_date( $item['date'] ),
            'edit_date'     => true,
        ),
        true
    );
    if ( is_wp_error( $publish_result ) ) {
        $results[] = array(
            'status'  => 'error',
            'post_id' => (int) $post_id,
            'title'   => $item['title'],
            'message' => '文章發布失敗：' . $publish_result->get_error_message(),
        );
        continue;
    }

    $results[] = array(
        'status'        => 'imported',
        'post_id'       => (int) $post_id,
        'attachment_id' => (int) $attachment_id,
        'title'         => $item['title'],
        'url'           => get_permalink( $post_id ),
    );
}

delete_transient( 'fwdignity_thenews_facet_counts_v1' );

echo wp_json_encode( $results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
