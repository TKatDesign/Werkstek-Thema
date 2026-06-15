<?php if (have_rows('componenten')): ?>
    <?php while (have_rows('componenten')): the_row(); ?>

        <?php if (get_row_layout() === 'hero'): ?>
            <?php get_template_part('template-parts/components/hero'); ?>
        <?php endif; ?>

        <?php if (in_array(get_row_layout(), ['contact_hero', 'contact-hero'], true)): ?>
            <?php get_template_part('template-parts/components/contact-hero'); ?>
        <?php endif; ?>

        <?php if (in_array(get_row_layout(), ['hero_detail', 'detail_hero'], true)): ?>
            <?php get_template_part('template-parts/components/detail-hero'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'kantoor_types'): ?>
            <?php get_template_part('template-parts/components/kantoor-types'); ?>
        <?php endif; ?>

        <?php if (in_array(get_row_layout(), ['info_cards', 'info-cards'], true)): ?>
            <?php get_template_part('template-parts/components/info-cards'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'kantoorruimtes'): ?>
            <?php get_template_part('template-parts/components/kantoorruimtes'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'reviews'): ?>
            <?php get_template_part('template-parts/components/reviews'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'counters'): ?>
            <?php get_template_part('template-parts/components/counters'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'testimonials'): ?>
            <?php get_template_part('template-parts/components/testimonials'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'accordion'): ?>
            <?php get_template_part('template-parts/components/accordion'); ?>
        <?php endif; ?>

        <?php if (in_array(get_row_layout(), ['faq', 'FAQ'], true)): ?>
            <?php get_template_part('template-parts/components/faq'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'locaties'): ?>
            <?php get_template_part('template-parts/components/locaties'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'kaart'): ?>
            <?php get_template_part('template-parts/components/kaart'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'projecten'): ?>
            <?php get_template_part('template-parts/components/projecten'); ?>
        <?php endif; ?>

        <?php if (in_array(get_row_layout(), ['cta_banner', 'cta-banner'], true)): ?>
            <?php get_template_part('template-parts/components/cta-banner'); ?>
        <?php endif; ?>

        <?php if (get_row_layout() === 'community_videos'): ?>
            <?php get_template_part('template-parts/components/community-videos'); ?>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>
