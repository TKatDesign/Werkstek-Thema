<?php get_header(); ?>

<?php
$term = get_queried_object();
$kantoorruimtes = werkstek_get_kantoorruimte_archive_items();
$term_description = $term instanceof WP_Term ? html_entity_decode($term->description, ENT_QUOTES | ENT_HTML5, get_bloginfo('charset')) : '';

get_template_part('template-parts/kantoorruimte-archive-layout', null, [
    'context' => 'locatie',
    'title' => $term instanceof WP_Term ? $term->name : single_term_title('', false),
    'description' => '',
    'seo_content' => $term_description,
    'items' => $kantoorruimtes,
]);
?>

<?php get_footer(); ?>
