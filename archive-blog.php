<?php get_header(); ?>

<?php
$blogs = [];

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $blogs[] = werkstek_get_blog_card_data(get_the_ID());
    }
}

get_template_part('template-parts/blog-archive-layout', null, [
    'title' => 'Blog',
    'items' => $blogs,
    'query' => $GLOBALS['wp_query'],
]);
?>

<?php get_footer(); ?>
