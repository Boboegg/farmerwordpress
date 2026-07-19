<?php
/**
 * Template Name: 研究與行動
 * Slug: research-results
 * From Research to Action landing page.
 *
 * @package fwdignity
 */

get_header();

$topic_registry = function_exists( 'fwdignity_topic_registry' ) ? fwdignity_topic_registry() : array();
$topic_index     = 0;
$advocacy_url    = fwdignity_get_public_page_url( 'dignity-farming-initiative' );

$action_links = array(
    array(
        'eyebrow' => 'Field & Learning',
        'title'   => '田野誌',
        'text'    => '研究問題回到田間，透過田野紀錄、共同學習與課程成果持續修正。',
        'url'     => home_url( '/field-learning/' ),
        'icon'    => 'fa-people-group',
    ),
    array(
        'eyebrow' => 'Audio & Video',
        'title'   => '聽見田裡的聲音',
        'text'    => '把訪談、現場經驗與專業知識轉成可聽、可看、可繼續討論的內容。',
        'url'     => home_url( '/podcast/' ),
        'icon'    => 'fa-headphones',
    ),
);

if ( $advocacy_url ) {
    $action_links[] = array(
        'eyebrow' => 'Advocacy',
        'title'   => '尊嚴農業倡議',
        'text'    => '將田野與研究中反覆出現的制度問題，帶進公共討論與倡議。',
        'url'     => $advocacy_url,
        'icon'    => 'fa-bullhorn',
    );
}

$output_links = array(
    array(
        'title' => '研究出版',
        'text'  => '整理正式發表、研究出版與季會紀錄。',
        'url'   => home_url( '/research-results/research-publications/' ),
        'meta'  => 'Publications',
    ),
    array(
        'title' => '相關資源',
        'text'  => '依來源與用途整理可延伸查閱的外部資料。',
        'url'   => home_url( '/research-results/related-resources/' ),
        'meta'  => 'Resources',
    ),
    array(
        'title' => '研究團隊',
        'text'  => '查看參與本計畫的跨領域研究與實務團隊。',
        'url'   => home_url( '/about/researchteam/' ),
        'meta'  => 'Team',
    ),
);
?>

<div class="fw-page-layout fw-page-layout--full">
<main class="ra-page" id="main">
    <nav class="ra-breadcrumb" aria-label="麵包屑">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page"><?php the_title(); ?></span>
    </nav>

    <header class="ra-intro">
        <p class="ra-kicker">Research in Action</p>
        <h1><?php the_title(); ?></h1>
        <p class="ra-lead">尊嚴農業的研究從農業工作者面對的真實問題開始。田野經驗進入調查與共同理解，再透過培力、知識轉譯、公共討論與倡議回到現場。</p>
    </header>

    <?php if ( $topic_registry ) : ?>
    <section class="ra-section ra-questions" aria-labelledby="ra-questions-title">
        <div class="ra-section-head">
            <div>
                <p class="ra-kicker">Questions</p>
                <h2 id="ra-questions-title">研究從問題開始</h2>
            </div>
            <p>五個問題不是彼此分開的研究分類，而是理解身體、工作、家庭、制度與知識如何互相影響的入口。</p>
        </div>
        <div class="ra-topic-list">
            <?php foreach ( $topic_registry as $topic_slug => $topic ) : ?>
                <?php $topic_index++; ?>
                <a data-research-topic href="<?php echo esc_url( fwdignity_get_topic_page_url( $topic_slug ) ); ?>">
                    <span class="ra-topic-number"><?php echo esc_html( str_pad( (string) $topic_index, 2, '0', STR_PAD_LEFT ) ); ?></span>
                    <div class="ra-topic-copy">
                        <h3><?php echo esc_html( $topic['label'] ); ?></h3>
                        <p><?php echo esc_html( $topic['core'] ); ?></p>
                    </div>
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="ra-section ra-actions" aria-labelledby="ra-actions-title">
        <div class="ra-section-head">
            <div>
                <p class="ra-kicker">Ways of Working</p>
                <h2 id="ra-actions-title">行動如何發生</h2>
            </div>
            <p>行動不是研究結束後才附加的宣傳，而是把問題帶回現場、讓不同知識彼此校正的過程。</p>
        </div>
        <div class="ra-action-grid">
            <?php foreach ( $action_links as $action ) : ?>
            <a href="<?php echo esc_url( $action['url'] ); ?>" class="ra-action-link">
                <i class="fa-solid <?php echo esc_attr( $action['icon'] ); ?>" aria-hidden="true"></i>
                <span>
                    <small><?php echo esc_html( $action['eyebrow'] ); ?></small>
                    <strong><?php echo esc_html( $action['title'] ); ?></strong>
                    <span><?php echo esc_html( $action['text'] ); ?></span>
                </span>
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="ra-section ra-outputs" aria-labelledby="ra-outputs-title">
        <div class="ra-section-head">
            <div>
                <p class="ra-kicker">Open Work</p>
                <h2 id="ra-outputs-title">公開成果與資源</h2>
            </div>
            <p>只呈現已公開的頁面與資料；正式出版資訊會在書目與連結逐筆核對後持續補入。</p>
        </div>
        <div class="ra-output-grid">
            <?php foreach ( $output_links as $output ) : ?>
            <a href="<?php echo esc_url( $output['url'] ); ?>">
                <small><?php echo esc_html( $output['meta'] ); ?></small>
                <strong><?php echo esc_html( $output['title'] ); ?></strong>
                <span><?php echo esc_html( $output['text'] ); ?></span>
                <b>前往查看 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></b>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</div>

<?php get_footer();
