<?php
get_header();
$municipality = get_term(get_queried_object());
$data = get_term_meta($municipality->term_id, 'municipality_data', true);
$data['partners'] = array_filter($data['partners'], function ($it) { return !empty($it['name']); });
$allowed_html = [
    'strong' => [],
    'em' => [],
    'ins' => [],
    'del' => [],
    'b' => [],
    'i' => [],
    'br' => []
];
?>
	<main class="py-6 pt-md-0">
		<?php if (!empty($data['bg-image'])) { ?>
        <section class="mx-0 my-0 hero--municipality">
            <div class="hero--municipality__img" style="background-image: url(<?php echo esc_attr(wp_get_attachment_url($data['bg-image'])); ?>);" role="img"></div>
            <h2 class="page-title my-2 mx-2 my-md-6 mx-md-4 mx-lg-6 my-lg-7"><span><?php echo esc_html($data['long_title']); ?></span></h2>
        </section>
		<?php } ?>
        <section class="mt-3 mx-2 mt-md-6 mx-md-4 mx-lg-6 municipality">
            <?php if (empty($data['bg-image'])) { ?><h2 class="page-title mt-0 mb-4 mb-md-8"><span><?php echo esc_html($data['long_title']); ?></span></h2><?php } ?>
            <div class="municipality__info mb-md-6">
                <?php $desc = nl2br(trim(wp_kses($municipality->description, $allowed_html)));;
                $desc = preg_split("/<br(?: \/)?>\r?\n<br(?: \/)>/", $desc);
                foreach ($desc as $paragraph) { ?>
                    <p><?php echo $paragraph ?></p>
                <?php } ?>
            </div>

            <details class="municipality__partners">
                <summary><h3 class="section-title"><?php esc_html_e(sizeof($data['partners']) > 1 ? 'Deine Ansprechpartner' : 'Dein Ansprechpartner', 'nostrasponte') ?></h3></summary>
                <div class="municipality__partners__wrapper" data-num="<?php echo sizeof($data['partners']); ?>">
	                <?php foreach ($data['partners'] as $partner) { ?>
                    <article class="card card--municipality-partner">
                        <div class="card--municipality-partner__top-wrapper">
                            <div class="card--municipality-partner__avatar" role="img" style="background-image: url('<?php echo esc_attr(wp_get_attachment_url($partner['avatar'])); ?>');"></div>
                            <header class="card--municipality-partner__head">
                                <h3><?php echo esc_html($partner['name']); ?></h3>
		                        <?php if (!empty($partner['tags'])) { ?>
                                <ul class="card--municipality-partner__tags">
                                    <?php foreach ($partner['tags'] as $tag) { ?><li class="card--municipality-partner__tag"><?php echo esc_html($tag); ?></li><?php } ?>
                                </ul>
		                        <?php } ?>
                            </header>
	                        <?php if (!empty($partner['bio'])) { ?>
                            <p class="card--municipality-partner__bio mx-1 mb-2"><?php echo nl2br(esc_html($partner['bio'])); ?></p>
	                        <?php } ?>
                        </div>
                        <footer class="card--municipality-partner__footer">
                            <a href="mailto:<?php echo esc_attr($partner['email']); ?>" class="card--municipality-partner__email link--no-mark" title="<?php echo esc_attr($partner['email']); ?>"><?php echo esc_html($partner['email']); ?></a>
                        </footer>
                    </article>
	                <?php } ?>
                </div>
            </details>

            <?php // ToDo: Anträge ?>
        </section>
        <section class="mt-6 mx-2 mt-md-10 mx-md-4 mx-lg-6 posts">
            <h2 class="section-title mt-0 mb-2 mb-md-3 mb-lg-5"><?php printf(_x( 'Artikel zu %s', 'Municipality Posts Section', 'nostrasponte' ), $municipality->name); ?></h2>
            <?php if (empty($wp_query->post_count)) { ?>
            <p class="center"><em><?php printf(__('Derzeit keine Posts aus %s', 'nostrasponte'), $municipality->name); ?></em></p>
            <?php } else { ?>
            <div class="post-grid" data-num="<?php echo esc_attr( $wp_query->post_count ); ?>" data-total="<?php echo esc_attr($wp_query->max_num_pages); ?>">
                <?php while (have_posts()) { the_post(); ?>
                <article class="card card--post">
                    <div class="card--post__img"<?php if (has_post_thumbnail()) { ?> style="background-image: url('<?php the_post_thumbnail_url(); ?>')"<?php } ?> role="img"></div>
                    <header class="card--post__head">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </header>
                    <p class="card--post__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18, '&hellip;'); ?></p>
                    <footer class="card--post__foot">
                        <div class="card--post__foot__category">
                            <i class="feather icon-bookmark" title="<?php esc_attr_e( 'Kategorien', 'nostrasponte' ); ?>"></i>
                            <ul>
                                <?php wp_list_categories( [ 'title_li' => '' ] ); ?>
                            </ul>
                        </div>
                        <?php if ( has_tag() ) { ?>
                            <div class="card--post__foot__tags">
                                <i class="feather icon-tag" title="<?php esc_attr_e( 'Tags', 'nostrasponte' ); ?>"></i>
                                <ul>
                                    <?php foreach ( get_tags() as $tag ) { ?>
                                        <li><a href="<?php echo get_tag_link( $tag->term_id ); ?>" <?php if ( !empty($tag->description) ) { ?>title="<?php echo esc_attr($tag->description); ?>"<?php } ?>><?php echo esc_html( $tag->name ); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php }
                        if( has_term( '', 'municipality' ) ) { ?>
                            <div class="card--post__foot__municipalities">
                                <i class="feather icon-home" title="<?php esc_attr_e( 'Kommune', 'nostrasponte' ); ?>"></i>
                                <ul>
                                    <?php foreach ( get_tags( [ 'taxonomy' => 'municipality' ] ) as $tag ) { ?>
                                        <li><a href="<?php echo get_tag_link( $tag->term_id ); ?>" <?php if ( !empty($tag->description) ) { ?>title="<?php echo esc_attr($tag->description); ?>"<?php } ?>><?php echo esc_html( $tag->name ); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <div class="card--post__foot__date">
                            <i class="feather icon-calendar" title="<?php esc_attr_e( 'Datum', 'nostrasponte' ); ?>"></i>
                            <a href="#"><time datetime="<?php the_date( 'Y-m-d' ); ?>"><?php printf('%s, %s. %s %s',
                                        NS_DAY_OF_WEEK[ intval( get_the_date( 'w' ) ) ],
                                        get_the_date( 'j' ),
                                        NS_MONTH[ intval( get_the_date( 'n' ) ) ],
                                        get_the_date( 'Y' ) ); ?></time></a>
                        </div>
                        <div class="card--post__foot__author">
                            <i class="feather icon-user" title="<?php esc_attr_e('Autor', 'nostrasponte' ); ?>"></i><?php the_author_link(); ?>
                        </div>
                    </footer>
                </article>
                <?php } ?>
            </div>
            <?php ns_pagination($wp_query, 'center mt-7 mb-5 mt-md-10 mb-md-12 mt-lg-8 mb-lg-11');
            } ?>
        </section>
	</main>
<?php get_sidebar(); ?>
<?php get_footer();