<?php
$sectie_titel = get_sub_field('titel') ?: 'Jouw nieuwe werkplek?';
$button_tekst = get_sub_field('button_tekst') ?: 'Alle kantoorruimtes';
$button_link = get_sub_field('button_link');
$button_url = get_post_type_archive_link('kantoorruimte') ?: '#';
$button_target = '_self';

if (is_array($button_link)) {
    $button_url = $button_link['url'] ?? '#';
    $button_target = $button_link['target'] ?? '_self';
} elseif (is_string($button_link) && $button_link !== '') {
    $button_url = $button_link;
}

$kantoorruimtes_query = new WP_Query([
    'post_type' => 'kantoorruimte',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'orderby' => [
        'menu_order' => 'ASC',
        'date' => 'DESC',
    ],
]);

$kantoorruimtes = [];

if ($kantoorruimtes_query->have_posts()) {
    while ($kantoorruimtes_query->have_posts()) {
        $kantoorruimtes_query->the_post();
        $kantoorruimtes[] = werkstek_get_kantoorruimte_card_data(get_the_ID());
    }

    wp_reset_postdata();
}
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center text-orange-500">
                <img src="<?php bloginfo( 'template_url' ) ?>/resources/images/branding/werkstek-icon-01.png" alt="">
            </div>
            <h2 class="mt-5 text-4xl font-bold text-slate-900 sm:text-5xl">
                <?php echo esc_html($sectie_titel); ?>
            </h2>
        </div>

        <div class="mt-10 lg:mt-14">
            <div class="relative">
                <button
                    type="button"
                    data-slider-prev="kantoorruimtes-slider"
                    aria-label="Vorige kantoorruimte"
                    class="hidden lg:inline-flex absolute left-0 top-[9.75rem] z-10 h-12 w-12 -translate-x-1/2 -translate-y-10 items-center justify-center rounded-full bg-slate-900 text-white shadow-[0_16px_30px_rgba(15,23,42,0.16)] transition hover:bg-slate-800"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                </button>

                <?php if (! empty($kantoorruimtes)): ?>
                    <div
                        id="kantoorruimtes-slider"
                        data-slider
                        class="no-scrollbar flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth pb-2 lg:gap-6"
                    >
                        <?php foreach ($kantoorruimtes as $ruimte): ?>
                            <article class="min-w-[82%] snap-start sm:min-w-[420px] lg:min-w-[calc((100%-3rem)/3)] lg:flex-1">
                                <a href="<?php echo esc_url($ruimte['url']); ?>" class="group block">
                                    <div
                                        class="relative min-h-[250px] overflow-hidden rounded-[1.75rem] bg-slate-200 shadow-[0_20px_45px_rgba(15,23,42,0.08)] transition duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_28px_60px_rgba(15,23,42,0.14)] lg:min-h-[255px]"
                                        style="
                                            background-image:
                                                linear-gradient(to top, rgba(15, 23, 42, 0.1), rgba(15, 23, 42, 0.02)),
                                                url('<?php echo esc_url($ruimte['image']); ?>');
                                            background-size: cover;
                                            <?php echo esc_attr($ruimte['position']); ?>
                                        "
                                    >
                                        <?php if ($ruimte['is_new']): ?>
                                            <span class="absolute left-4 top-4 inline-flex rounded-full bg-yellow-300 px-4 py-1.5 text-sm font-semibold text-slate-900">
                                                Nieuw
                                            </span>
                                        <?php endif; ?>

                                        <span class="absolute bottom-4 right-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm transition group-hover:scale-105 group-hover:text-orange-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                                <path d="M7 17 17 7"></path>
                                                <path d="M9 7h8v8"></path>
                                            </svg>
                                        </span>
                                    </div>

                                    <div class="pt-4">
                                        <div class="flex items-center gap-2 text-base font-medium text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-none stroke-current stroke-2 text-slate-400">
                                                <path d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"></path>
                                                <circle cx="12" cy="11" r="2.5"></circle>
                                            </svg>
                                            <span><?php echo esc_html($ruimte['title']); ?></span>
                                        </div>

                                        <?php if ($ruimte['price'] || $ruimte['availability']): ?>
                                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                                <?php if ($ruimte['price']): ?>
                                                    <span class="inline-flex rounded-full bg-green-accent px-4 py-1.5 text-sm font-semibold text-white">
                                                        Vanaf €<?php echo esc_html($ruimte['price']); ?>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if ($ruimte['availability']): ?>
                                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-1.5 text-sm font-medium text-slate-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2 text-slate-400">
                                                            <rect x="3.5" y="5.5" width="17" height="15" rx="2"></rect>
                                                            <path d="M7 3.5v4"></path>
                                                            <path d="M17 3.5v4"></path>
                                                            <path d="M3.5 9.5h17"></path>
                                                        </svg>
                                                        <span><?php echo esc_html($ruimte['availability']); ?></span>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-[1.75rem] border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">
                        Er zijn nog geen kantoorruimtes toegevoegd.
                    </div>
                <?php endif; ?>

                <button
                    type="button"
                    data-slider-next="kantoorruimtes-slider"
                    aria-label="Volgende kantoorruimte"
                    class="hidden lg:inline-flex absolute right-0 top-[9.75rem] z-10 h-12 w-12 translate-x-1/2 -translate-y-10 items-center justify-center rounded-full bg-slate-900 text-white shadow-[0_16px_30px_rgba(15,23,42,0.16)] transition hover:bg-slate-800"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <path d="m9 6 6 6-6 6"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-10 flex justify-center">
                <a
                    href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"
                    rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                    class="inline-flex items-center gap-3 rounded-full bg-slate-900 px-6 pr-3 py-2.5 text-base font-medium text-white transition hover:bg-slate-800"
                >
                    <span><?php echo esc_html($button_tekst); ?></span>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
