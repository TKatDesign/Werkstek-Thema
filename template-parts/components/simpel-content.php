<?php
$editor = get_sub_field('editor');

if (! is_string($editor) || trim($editor) === '') {
    return;
}
?>

<section class="py-12 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="simpel-content mx-auto w-full lg:w-[70%]">
            <?php echo wp_kses_post($editor); ?>
        </div>
    </div>
</section>
