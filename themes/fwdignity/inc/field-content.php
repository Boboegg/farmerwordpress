<?php
/**
 * 田野誌共用資料 helper 與文章後台欄位。
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fwdignity_field_topic_slugs() {
    return array( 'agri-school', 'education-farmer', 'event', 'environment', 'greenhouse' );
}

function fwdignity_field_tax_clause( $slugs = array() ) {
    $slugs = $slugs ? $slugs : fwdignity_field_topic_slugs();
    return array(
        'taxonomy'         => 'category',
        'field'            => 'slug',
        'terms'            => array_values( array_unique( array_map( 'sanitize_title', $slugs ) ) ),
        'operator'         => 'IN',
        'include_children' => true,
    );
}

function fwdignity_field_query( $overrides = array() ) {
    $defaults = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 6,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
        'tax_query'           => array( fwdignity_field_tax_clause() ),
    );
    return new WP_Query( wp_parse_args( $overrides, $defaults ) );
}

function fwdignity_get_field_date_raw( $post_id ) {
    $date = sanitize_text_field( get_post_meta( $post_id, '_fwdignity_field_date', true ) );
    return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ? $date : get_the_date( 'Y-m-d', $post_id );
}

function fwdignity_get_field_date( $post_id, $format = 'Y.m.d' ) {
    $timestamp = strtotime( fwdignity_get_field_date_raw( $post_id ) . ' 12:00:00' );
    return $timestamp ? wp_date( $format, $timestamp ) : get_the_date( $format, $post_id );
}

function fwdignity_get_field_location( $post_id ) {
    return sanitize_text_field( get_post_meta( $post_id, '_fwdignity_field_location', true ) );
}

function fwdignity_get_field_context( $post_id ) {
    return sanitize_text_field( get_post_meta( $post_id, '_fwdignity_field_context', true ) );
}

function fwdignity_get_featured_field_post() {
    $featured = fwdignity_field_query(
        array(
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => '_fwdignity_field_featured',
                    'value' => '1',
                ),
            ),
        )
    );
    if ( $featured->have_posts() ) {
        return $featured->posts[0];
    }

    $latest = fwdignity_field_query( array( 'posts_per_page' => 1 ) );
    return $latest->have_posts() ? $latest->posts[0] : null;
}

function fwdignity_get_event_status( $post_id ) {
    $manual = sanitize_key( get_post_meta( $post_id, '_fwdignity_event_status', true ) );
    $labels = array(
        'planned'   => '即將開始',
        'open'      => '公開報名',
        'full'      => '已額滿',
        'postponed' => '延期',
        'cancelled' => '已取消',
        'ended'     => '已結束',
    );
    if ( isset( $labels[ $manual ] ) ) {
        return array( 'slug' => $manual, 'label' => $labels[ $manual ] );
    }

    $start = get_post_meta( $post_id, '_fwdignity_event_start', true );
    if ( $start && strtotime( $start ) < current_time( 'timestamp' ) ) {
        return array( 'slug' => 'ended', 'label' => '已結束' );
    }
    return array( 'slug' => 'planned', 'label' => '即將開始' );
}

function fwdignity_register_field_meta_box() {
    add_meta_box(
        'fwdignity-field-data',
        '田野誌與活動資料',
        'fwdignity_render_field_meta_box',
        'post',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'fwdignity_register_field_meta_box' );

function fwdignity_render_field_meta_box( $post ) {
    wp_nonce_field( 'fwdignity_save_field_data', 'fwdignity_field_nonce' );
    $featured = get_post_meta( $post->ID, '_fwdignity_field_featured', true );
    $date     = get_post_meta( $post->ID, '_fwdignity_field_date', true );
    $location = get_post_meta( $post->ID, '_fwdignity_field_location', true );
    $context  = get_post_meta( $post->ID, '_fwdignity_field_context', true );
    $public   = get_post_meta( $post->ID, '_fwdignity_event_public', true );
    $start    = get_post_meta( $post->ID, '_fwdignity_event_start', true );
    $end      = get_post_meta( $post->ID, '_fwdignity_event_end', true );
    $status   = get_post_meta( $post->ID, '_fwdignity_event_status', true );
    $register = get_post_meta( $post->ID, '_fwdignity_event_registration_url', true );
    ?>
    <p><label><input type="checkbox" name="fwdignity_field_featured" value="1" <?php checked( $featured, '1' ); ?>> 設為本期田野</label></p>
    <p><label for="fwdignity_field_date"><strong>田野日期</strong></label><br><input id="fwdignity_field_date" type="date" name="fwdignity_field_date" value="<?php echo esc_attr( $date ); ?>" style="width:100%"></p>
    <p><label for="fwdignity_field_location"><strong>地點</strong></label><br><input id="fwdignity_field_location" type="text" name="fwdignity_field_location" value="<?php echo esc_attr( $location ); ?>" style="width:100%"></p>
    <p><label for="fwdignity_field_context"><strong>場域／農作</strong></label><br><input id="fwdignity_field_context" type="text" name="fwdignity_field_context" value="<?php echo esc_attr( $context ); ?>" style="width:100%"></p>
    <hr>
    <p><label><input type="checkbox" name="fwdignity_event_public" value="1" <?php checked( $public, '1' ); ?>> 未來公開活動</label></p>
    <p><label for="fwdignity_event_start"><strong>活動開始</strong></label><br><input id="fwdignity_event_start" type="datetime-local" name="fwdignity_event_start" value="<?php echo esc_attr( $start ); ?>" style="width:100%"></p>
    <p><label for="fwdignity_event_end"><strong>活動結束</strong></label><br><input id="fwdignity_event_end" type="datetime-local" name="fwdignity_event_end" value="<?php echo esc_attr( $end ); ?>" style="width:100%"></p>
    <p><label for="fwdignity_event_status"><strong>活動狀態</strong></label><br>
      <select id="fwdignity_event_status" name="fwdignity_event_status" style="width:100%">
        <option value="">依日期判斷</option>
        <?php foreach ( array( 'planned' => '即將開始', 'open' => '公開報名', 'full' => '已額滿', 'postponed' => '延期', 'cancelled' => '已取消', 'ended' => '已結束' ) as $value => $label ) : ?>
          <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p><label for="fwdignity_event_registration_url"><strong>公開資訊／報名網址</strong></label><br><input id="fwdignity_event_registration_url" type="url" name="fwdignity_event_registration_url" value="<?php echo esc_attr( $register ); ?>" style="width:100%"></p>
    <?php
}

function fwdignity_save_field_meta_box( $post_id ) {
    if ( ! isset( $_POST['fwdignity_field_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fwdignity_field_nonce'] ) ), 'fwdignity_save_field_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $text_fields = array(
        '_fwdignity_field_date'     => 'fwdignity_field_date',
        '_fwdignity_field_location' => 'fwdignity_field_location',
        '_fwdignity_field_context'  => 'fwdignity_field_context',
        '_fwdignity_event_start'    => 'fwdignity_event_start',
        '_fwdignity_event_end'      => 'fwdignity_event_end',
        '_fwdignity_event_status'   => 'fwdignity_event_status',
    );
    foreach ( $text_fields as $meta_key => $field_name ) {
        $value = isset( $_POST[ $field_name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) : '';
        $value ? update_post_meta( $post_id, $meta_key, $value ) : delete_post_meta( $post_id, $meta_key );
    }

    $url = isset( $_POST['fwdignity_event_registration_url'] ) ? esc_url_raw( wp_unslash( $_POST['fwdignity_event_registration_url'] ) ) : '';
    $url ? update_post_meta( $post_id, '_fwdignity_event_registration_url', $url ) : delete_post_meta( $post_id, '_fwdignity_event_registration_url' );

    foreach ( array( '_fwdignity_field_featured' => 'fwdignity_field_featured', '_fwdignity_event_public' => 'fwdignity_event_public' ) as $meta_key => $field_name ) {
        isset( $_POST[ $field_name ] ) ? update_post_meta( $post_id, $meta_key, '1' ) : delete_post_meta( $post_id, $meta_key );
    }
}
add_action( 'save_post_post', 'fwdignity_save_field_meta_box' );
