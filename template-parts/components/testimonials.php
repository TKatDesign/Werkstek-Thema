<?php
$theme_uri = get_template_directory_uri();
$message_icon = $theme_uri . '/resources/images/testimonials/streamline-freehand-color_conversation-question-text-1.svg';

$testimonials_query = new WP_Query([
    'post_type' => 'testimonial',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'orderby' => 'menu_order date',
    'order' => 'ASC',
    'no_found_rows' => true,
]);

$testimonials = [];

if ($testimonials_query->have_posts()) {
    while ($testimonials_query->have_posts()) {
        $testimonials_query->the_post();

        $post_id = get_the_ID();
        $logo = function_exists('get_field') ? get_field('logo', $post_id) : get_post_meta($post_id, 'logo', true);
        $naam = function_exists('get_field') ? get_field('naam_persoon', $post_id) : get_post_meta($post_id, 'naam_persoon', true);
        $functietitel = function_exists('get_field') ? get_field('functietitel', $post_id) : get_post_meta($post_id, 'functietitel', true);
        $logo_url = '';
        $logo_alt = '';

        if (is_array($logo)) {
            $logo_url = $logo['url'] ?? '';
            $logo_alt = $logo['alt'] ?? '';
        } elseif (is_numeric($logo)) {
            $logo_url = wp_get_attachment_image_url((int) $logo, 'medium') ?: wp_get_attachment_url((int) $logo);
            $logo_alt = get_post_meta((int) $logo, '_wp_attachment_image_alt', true);
        } elseif (is_string($logo)) {
            $logo_url = $logo;
        }

        $testimonials[] = [
            'image' => get_the_post_thumbnail_url($post_id, 'large'),
            'image_alt' => get_the_title($post_id),
            'logo' => $logo_url,
            'logo_alt' => $logo_alt,
            'quote' => get_the_content(null, false, $post_id),
            'name' => $naam ?: get_the_title($post_id),
            'role' => $functietitel,
        ];
    }

    wp_reset_postdata();
}

if (empty($testimonials)) {
    return;
}

$component_id = 'testimonials-' . wp_unique_id();
?>

<section class="overflow-hidden py-20 sm:py-24 lg:py-36" data-testimonials>
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="relative lg:min-h-[520px]" data-testimonials-slides>
            <?php foreach ($testimonials as $index => $testimonial): ?>
                <?php $is_active = $index === 0; ?>
                <article
                    id="<?php echo esc_attr($component_id . '-slide-' . $index); ?>"
                    class="<?php echo $is_active ? 'opacity-100' : 'pointer-events-none hidden opacity-0 lg:grid'; ?> grid content-start gap-10 transition-opacity duration-500 ease-out lg:absolute lg:inset-0 lg:grid-cols-[minmax(0,1.08fr)_minmax(360px,0.72fr)] lg:items-center lg:gap-16"
                    data-testimonial-slide
                    aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>"
                >
                    <div class="relative">
                        <div class="overflow-hidden rounded-[0.75rem] bg-slate-100">
                            <?php if ($testimonial['image']): ?>
                                <img
                                    src="<?php echo esc_url($testimonial['image']); ?>"
                                    alt="<?php echo esc_attr($testimonial['image_alt']); ?>"
                                    class="aspect-[1.47/1] w-full object-cover lg:aspect-[1.5/1]"
                                    loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                >
                            <?php else: ?>
                                <div class="aspect-[1.47/1] bg-slate-100 lg:aspect-[1.5/1]"></div>
                            <?php endif; ?>
                        </div>

                        <span class="absolute right-5 top-5 inline-flex h-14 w-14 items-center justify-center rounded-full bg-surface-200 text-green-accent lg:right-6 lg:top-6">
                            <img src="<?php echo esc_url($message_icon); ?>"></img>
                        </span>
                    </div>

                    <div class="max-w-xl lg:pt-4">
                        <?php if ($testimonial['logo']): ?>
                            <img
                                src="<?php echo esc_url($testimonial['logo']); ?>"
                                alt="<?php echo esc_attr($testimonial['logo_alt']); ?>"
                                class="max-h-12 w-auto object-contain"
                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                            >
                        <?php endif; ?>

                        <?php if ($testimonial['quote']): ?>
                            <blockquote class="mt-9 text-[1.75rem] font-bold leading-tight text-slate-900 sm:text-4xl lg:text-[2rem]">
                                &ldquo;<?php echo esc_html(wp_strip_all_tags($testimonial['quote'])); ?>&rdquo;
                            </blockquote>
                        <?php endif; ?>

                        <div class="mt-8 text-base font-normal leading-6 text-slate-900">
                            <p><?php echo esc_html($testimonial['name']); ?></p>
                            <?php if ($testimonial['role']): ?>
                                <p><?php echo esc_html($testimonial['role']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="relative z-20 mt-8 flex gap-3">
                            <button
                                type="button"
                                class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-surface-200 text-dark-main transition hover:bg-white hover:text-slate-900 disabled:pointer-events-none disabled:opacity-55"
                                data-testimonials-prev
                                aria-label="Vorige testimonial"
                                 >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <path d="M19 12H5"></path>
                                    <path d="m12 19-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-surface-200 text-dark-main transition hover:bg-white disabled:pointer-events-none disabled:opacity-55"
                                data-testimonials-next
                                aria-label="Volgende testimonial"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </button>
                         </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
