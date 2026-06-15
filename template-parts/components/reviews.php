<?php
$sectie_titel = get_sub_field('titel') ?: 'Wat huurders over ons zeggen';
$reviews_query = new WP_Query([
    'post_type' => 'review',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
]);

$reviews = [];

if ($reviews_query->have_posts()) {
    while ($reviews_query->have_posts()) {
        $reviews_query->the_post();
        $review = werkstek_get_review_card_data(get_the_ID());

        if (! $review['review']) {
            continue;
        }

        $reviews[] = $review;
    }

    wp_reset_postdata();
}

if (empty($reviews)) {
    return;
}

$top_row = [];
$bottom_row = [];

foreach ($reviews as $index => $review) {
    if ($index % 2 === 0) {
        $top_row[] = $review;
    } else {
        $bottom_row[] = $review;
    }
}

if (empty($top_row)) {
    $top_row = $reviews;
}

if (empty($bottom_row)) {
    $bottom_row = $reviews;
}

$expand_row = static function ($items, $minimum = 6) {
    $expanded = $items;

    while (count($expanded) < $minimum) {
        $expanded = array_merge($expanded, $items);
    }

    return $expanded;
};

$top_row = $expand_row($top_row);
$bottom_row = $expand_row($bottom_row);
?>

<section class="mb-12">
    <!-- <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center rounded-full bg-orange-100 px-4 py-2 text-sm font-medium text-orange-600">
                Reviews
            </span>
            <h2 class="mt-5 text-4xl font-bold text-slate-900 sm:text-5xl">
                <?php echo esc_html($sectie_titel); ?>
            </h2>
        </div>
    </div> -->

    <div class="space-y-4 px-5 py-6 lg:mt-14 lg:space-y-5 lg:px-0">
        <div class="reviews-marquee">
            <div class="reviews-marquee__track reviews-marquee__track--reverse">
                <?php foreach (array_merge($top_row, $top_row) as $review): ?>
                    <article class="reviews-card">
                        <img src="<?php echo esc_url($review['image']); ?>" alt="<?php echo esc_attr($review['name']); ?>" class="reviews-card__image">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 text-sm leading-none">
                                <span class="truncate font-normal text-slate-400"><?php echo esc_html($review['name']); ?></span>
                                <?php if ($review['rating']): ?>
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-orange-500 text-orange-500">
                                            <path d="m12 2.5 2.93 5.94 6.57.95-4.75 4.63 1.12 6.54L12 17.48 6.13 20.56l1.12-6.54L2.5 9.39l6.57-.95L12 2.5Z"></path>
                                        </svg>
                                        <span><?php echo esc_html($review['rating']); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 truncate text-base font-medium text-slate-900">
                                <?php echo esc_html($review['review']); ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="reviews-marquee">
            <div class="reviews-marquee__track">
                <?php foreach (array_merge($bottom_row, $bottom_row) as $review): ?>
                    <article class="reviews-card">
                        <img src="<?php echo esc_url($review['image']); ?>" alt="<?php echo esc_attr($review['name']); ?>" class="reviews-card__image">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 text-sm leading-none">
                                <span class="truncate font-normal text-slate-400"><?php echo esc_html($review['name']); ?></span>
                                <?php if ($review['rating']): ?>
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-orange-500 text-orange-500">
                                            <path d="m12 2.5 2.93 5.94 6.57.95-4.75 4.63 1.12 6.54L12 17.48 6.13 20.56l1.12-6.54L2.5 9.39l6.57-.95L12 2.5Z"></path>
                                        </svg>
                                        <span><?php echo esc_html($review['rating']); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 truncate text-base font-medium text-slate-900">
                                <?php echo esc_html($review['review']); ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
