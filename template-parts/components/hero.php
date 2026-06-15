<?php
$afbeelding = get_sub_field('afbeelding');
$titel = get_sub_field('titel') ?: 'De leukste plek, voor een werkstek';
$kantoorruimte_archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
$locatie_search_items = function_exists('werkstek_get_locatie_search_items') ? werkstek_get_locatie_search_items() : [];
$snel_naar_tags = ! empty($locatie_search_items) ? array_slice($locatie_search_items, 0, 3) : [
    ['name' => 'Amsterdam', 'url' => $kantoorruimte_archive_url],
    ['name' => 'Utrecht', 'url' => $kantoorruimte_archive_url],
    ['name' => 'Rotterdam', 'url' => $kantoorruimte_archive_url],
];
$review_placeholders = ['A', 'B', 'C'];
?>

<section class="overflow-hidden py-8 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="grid items-center gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(320px,560px)] lg:gap-16">
            <div class="order-2 max-w-xl lg:order-1">
                <h2 class="max-w-[12ch] text-4xl font-bold leading-[1.08] text-slate-900 sm:text-5xl lg:max-w-xl lg:text-6xl">
                    <?php echo esc_html($titel); ?>
                </h2>

                <form class="mt-8 lg:mt-10" action="<?php echo esc_url($kantoorruimte_archive_url); ?>" method="get" data-location-search>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label class="relative block flex-1">
                            <span class="sr-only">Zoek plaats of adres</span>
                            <input
                                type="text"
                                name="locatie"
                                list="hero-kantoorruimte-locaties"
                                placeholder="Zoek plaats of adres..."
                                class="h-12 w-full rounded-full border border-slate-200 bg-white px-5 text-[15px] text-slate-700 shadow-[0_10px_30px_rgba(15,23,42,0.08)] outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200 lg:px-6 lg:text-base"
                            >
                            <datalist id="hero-kantoorruimte-locaties">
                                <?php foreach ($locatie_search_items as $locatie): ?>
                                    <option
                                        value="<?php echo esc_attr($locatie['name']); ?>"
                                        data-slug="<?php echo esc_attr($locatie['slug']); ?>"
                                        data-url="<?php echo esc_url($locatie['url']); ?>"
                                    ></option>
                                <?php endforeach; ?>
                            </datalist>
                        </label>

                        <div class="flex items-center gap-1 sm:w-auto">
                            <button
                                type="submit"
                                class="inline-flex h-12 w-full items-center justify-center rounded-full bg-orange-500 px-7 text-base font-semibold text-white transition hover:bg-orange-600 sm:w-auto"
                            >
                                Zoeken
                            </button>

                            <button
                                type="button"
                                aria-label="Open zoeken"
                                class="hidden h-12 w-12 items-center justify-center rounded-full bg-orange-500 text-white transition hover:bg-orange-600 sm:inline-flex"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <span class="w-full text-sm font-medium text-slate-600 sm:w-auto">Snel naar:</span>
                    <?php foreach ($snel_naar_tags as $tag): ?>
                        <a
                            href="<?php echo esc_url($tag['url']); ?>"
                            class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200"
                        >
                            <?php echo esc_html($tag['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-4 text-slate-700 lg:mt-10">
                    <div class="flex -space-x-3">
                        <div class="flex -space-x-3">
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar1.png)] rounded-full bg-cover bg-center border-2 border-white"></div>
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar2.png)] rounded-full bg-cover bg-center border-2 border-white"></div>
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar3.png)] rounded-full bg-cover bg-center border-2 border-white"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-base font-semibold">
                        <span>5/5</span>
                        <span class="text-orange-500">&#9733;</span>
                        <span class="font-medium text-slate-600">85 reviews</span>
                    </div>
                </div>
            </div>

            <div class="order-1 relative mx-auto w-full max-w-[300px] lg:order-2 lg:max-w-[560px]">
                <div
                    aria-hidden="true"
                    class="absolute left-3 top-7 h-7 w-4 rounded-[999px_999px_999px_0] bg-lime-500/90 lg:left-6 lg:top-10 lg:h-10 lg:w-6"
                    style="transform: rotate(-28deg);"
                ></div>
                <div
                    aria-hidden="true"
                    class="absolute right-1 bottom-8 h-8 w-5 rounded-[999px_999px_999px_0] bg-lime-600/90 lg:-right-1 lg:bottom-14 lg:h-12 lg:w-7"
                    style="transform: rotate(38deg);"
                ></div>

                <div
                    class="aspect-[1/1.04] overflow-hidden"
                    style="
                        -webkit-mask-image: url('<?php echo esc_url(get_template_directory_uri() . '/resources/images/Rectangle32.svg'); ?>');
                        mask-image: url('<?php echo esc_url(get_template_directory_uri() . '/resources/images/Rectangle32.svg'); ?>');
                        -webkit-mask-repeat: no-repeat;
                        mask-repeat: no-repeat;
                        -webkit-mask-position: center;
                        mask-position: center;
                        -webkit-mask-size: contain;
                        mask-size: contain;
                    "
                >
                    <?php if ($afbeelding): ?>
                        <img
                            src="<?php echo esc_url($afbeelding['url']); ?>"
                            alt="<?php echo esc_attr($afbeelding['alt']); ?>"
                            class="h-full w-full object-cover"
                        >
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center bg-slate-200 text-sm font-medium text-slate-500">
                            Voeg een hero-afbeelding toe
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
