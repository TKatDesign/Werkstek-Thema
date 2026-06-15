<?php get_header(); ?>

<?php
$blogs = [];
$queried_object = get_queried_object();
$archive_title = $queried_object instanceof WP_Term ? $queried_object->name : 'Blog';

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $blogs[] = werkstek_get_blog_card_data(get_the_ID());
    }
}

get_template_part('template-parts/blog-archive-layout', null, [
    'title' => $archive_title,
    'items' => $blogs,
    'query' => $GLOBALS['wp_query'],
]);
?>

<?php get_footer(); ?>
