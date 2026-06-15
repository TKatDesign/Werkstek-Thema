<?php get_header(); ?>

<?php
$kantoorruimtes = werkstek_get_kantoorruimte_archive_items();
$archive_description = get_the_archive_description();

get_template_part('template-parts/kantoorruimte-archive-layout', null, [
    'context' => 'archive',
    'title' => 'Kantoorruimte huren',
    'description' => $archive_description,
    'items' => $kantoorruimtes,
]);
?>

<?php get_footer(); ?>
