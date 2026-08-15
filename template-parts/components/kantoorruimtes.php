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

<section class="py-16 sm:py-20 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8 lg:px-16">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-[2.5rem] font-bold leading-tight text-dark-main sm:text-5xl lg:text-[3rem]">
                <?php echo esc_html($sectie_titel); ?>
            </h2>
        </div>

        <div class="mt-8 lg:mt-8">
            <div class="relative">
                <button
                    type="button"
                    data-slider-prev="kantoorruimtes-slider"
                    aria-label="Vorige kantoorruimte"
                    class="hidden lg:inline-flex absolute left-0 top-[8.5rem] z-10 h-16 w-16 -translate-x-1/2 items-center justify-center rounded-full border border-[#e6d9cd] bg-[#FCF8F3] text-slate-800 shadow-sm transition hover:bg-white disabled:pointer-events-none disabled:opacity-40"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                </button>

                <?php if (! empty($kantoorruimtes)): ?>
                    <div
                        id="kantoorruimtes-slider"
                        data-slider
                        class="no-scrollbar -mx-5 flex snap-x snap-mandatory gap-5 overflow-x-auto px-[7vw] pb-2 scroll-smooth sm:-mx-8 sm:px-[calc((100vw-420px)/2)] lg:mx-0 lg:gap-8 lg:px-0"
                    >
                        <?php foreach ($kantoorruimtes as $ruimte): ?>
                            <?php
                            $card_location = $ruimte['address'] ?: $ruimte['title'];
                            $card_price = preg_replace('/^vanaf\s*/i', '', (string) $ruimte['price']);
                            $show_new_label = $ruimte['is_new'] || strtolower(trim((string) $ruimte['availability'])) === 'nieuw';

                            if ($card_price !== '' && strpos($card_price, '€') === false && preg_match('/^\d/', $card_price)) {
                                $card_price = '€' . $card_price;
                            }

                            if ($ruimte['location'] && stripos($card_location, $ruimte['location']) === false) {
                                $card_location .= ', ' . $ruimte['location'];
                            }
                            ?>
                            <article class="min-w-[86vw] snap-center sm:min-w-[420px] lg:min-w-[calc((100%_-_4rem)/3)] lg:snap-start lg:flex-1">
                                <a href="<?php echo esc_url($ruimte['url']); ?>" class="group block overflow-hidden rounded-[1.75rem] bg-[#FCF8F3] transition duration-300 hover:-translate-y-1">
                                        <div class="relative h-[255px] overflow-hidden sm:h-[250px] lg:h-[250px]">
                                            <img src="<?php echo esc_url($ruimte['image']); ?>" alt="<?php echo esc_attr($ruimte['title']); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">

                                            <?php if ($show_new_label): ?>
                                                <span class="absolute left-5 top-5 inline-flex rounded-full bg-[#fff000] px-4 py-2 text-base font-semibold text-slate-900">
                                                    Nieuw
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="px-6 py-7 sm:px-8">
                                            <div class="flex items-center gap-1 text-base font-bold text-slate-800 sm:text-base">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 flex-none fill-none stroke-current stroke-[1.7] text-slate-600" aria-hidden="true">
                                                    <path d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"></path>
                                                    <circle cx="12" cy="11" r="2.5"></circle>
                                                </svg>
                                                <span class="truncate"><?php echo esc_html($card_location); ?></span>
                                            </div>

                                            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-base font-medium text-slate-400">
                                                <?php if ($ruimte['price']): ?>
                                                    <span>
                                                        Vanaf <span class="text-[#fc6321]"><?php echo esc_html($card_price); ?></span>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if ($ruimte['price'] && $ruimte['surface']): ?>
                                                    <span aria-hidden="true">•</span>
                                                <?php endif; ?>

                                                <?php if ($ruimte['surface']): ?>
                                                    <span>
                                                        <?php echo esc_html(preg_match('/^\d+$/', $ruimte['surface']) ? $ruimte['surface'] . 'm²' : $ruimte['surface']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
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
                    class="hidden lg:inline-flex absolute right-0 top-[8.5rem] z-10 h-16 w-16 translate-x-1/2 items-center justify-center rounded-full border border-[#e6d9cd] bg-[#FCF8F3] text-slate-800 shadow-sm transition hover:bg-white disabled:pointer-events-none disabled:opacity-40"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <path d="m9 6 6 6-6 6"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-8 flex justify-center lg:mt-8">
                <a
                    href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"
                    rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                    class="group inline-flex items-center gap-4 rounded-full bg-dark-main py-2 pl-7 pr-2 text-base font-medium text-white transition hover:bg-orange-accent"
                >
                    <span><?php echo esc_html($button_tekst); ?></span>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FCF8F3] text-dark-main shadow-sm">
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
