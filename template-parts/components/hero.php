<?php
$afbeelding = get_sub_field('afbeelding');
$video = get_sub_field('video') ?: get_sub_field('hero_video') ?: get_sub_field('video_bestand');
$video_url = '';
$video_mime = '';

if (is_array($video)) {
    $video_id = isset($video['ID']) ? (int) $video['ID'] : (isset($video['id']) ? (int) $video['id'] : 0);
    $video_url = $video['url'] ?? ($video_id ? wp_get_attachment_url($video_id) : '');
    $video_mime = $video['mime_type'] ?? '';

    if (! $video_mime && $video_id) {
        $video_mime = get_post_mime_type($video_id) ?: '';
    }
} elseif (is_numeric($video)) {
    $video_url = wp_get_attachment_url((int) $video) ?: '';
    $video_mime = get_post_mime_type((int) $video) ?: '';
} elseif (is_string($video)) {
    $video_url = trim($video);
}

$video_poster_url = is_array($afbeelding) ? ($afbeelding['url'] ?? '') : '';
$titel = get_sub_field('titel') ?: 'De leukste plek, voor een werkstek';
$kantoorruimte_archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
$leaf_one_url = get_template_directory_uri() . '/resources/images/branding/leaf-1.svg';
$leaf_two_url = get_template_directory_uri() . '/resources/images/branding/leaf-2.svg';
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
                <h2 class="text-4xl font-bold leading-[1.08] text-slate-900 sm:text-5xl lg:max-w-xl lg:text-6xl">
                    <?php echo esc_html($titel); ?>
                </h2>

                <form class="relative z-20 mt-8 lg:mt-10" action="<?php echo esc_url($kantoorruimte_archive_url); ?>" method="get" data-location-search>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label class="relative block flex-1">
                            <span class="sr-only">Zoek plaats of adres</span>
                            <input
                                type="text"
                                name="locatie"
                                placeholder="Zoek plaats of adres..."
                                autocomplete="off"
                                role="combobox"
                                aria-autocomplete="list"
                                aria-expanded="false"
                                aria-controls="hero-kantoorruimte-locaties"
                                class="h-12 w-full rounded-full border border-slate-200 bg-[#FCF8F3] px-5 text-[15px] text-slate-700 shadow-[0_10px_30px_rgba(15,23,42,0.08)] outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200 lg:px-6 lg:text-base"
                            >
                            <div id="hero-kantoorruimte-locaties" class="absolute left-0 right-0 top-[calc(100%+0.75rem)] hidden max-h-80 overflow-y-auto rounded-[1.25rem] bg-surface-200 px-2 py-3 shadow-[0_20px_50px_rgba(15,41,58,0.16)]" data-location-results role="listbox">
                                <?php foreach ($locatie_search_items as $locatie): ?>
                                    <a href="<?php echo esc_url($locatie['url']); ?>" class="block rounded-xl px-4 py-2.5 text-base font-medium text-dark-main transition hover:text-orange-accent focus:bg-white/60 focus:text-orange-accent focus:outline-none" data-location-option data-name="<?php echo esc_attr($locatie['name']); ?>" data-slug="<?php echo esc_attr($locatie['slug']); ?>" role="option">
                                        <?php echo esc_html($locatie['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                                <p class="hidden px-4 py-3 text-sm text-dark-main/60" data-location-empty>Geen locaties gevonden.</p>
                            </div>
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
                            class="inline-flex items-center rounded-full bg-surface-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200"
                        >
                            <?php echo esc_html($tag['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-4 text-slate-700 lg:mt-10">
                    <div class="flex -space-x-3">
                        <div class="flex -space-x-3">
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar1.png)] rounded-full bg-cover bg-center border-2 border-surface"></div>
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar2.png)] rounded-full bg-cover bg-center border-2 border-surface"></div>
                            <div class="h-8 w-8 bg-[url(/resources/images/avatar3.png)] rounded-full bg-cover bg-center border-2 border-surface"></div>
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
                <img
                    src="<?php echo esc_url($leaf_one_url); ?>"
                    alt=""
                    aria-hidden="true"
                    class="absolute left-3 top-7 h-8 w-auto lg:left-6 lg:top-10 lg:h-[53px]"
                >
                <img
                    src="<?php echo esc_url($leaf_two_url); ?>"
                    alt=""
                    aria-hidden="true"
                    class="absolute bottom-8 right-1 h-6 w-auto lg:-right-1 lg:bottom-14 lg:h-[34px]"
                >

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
                    <?php if ($video_url): ?>
                        <video
                            autoplay
                            muted
                            loop
                            playsinline
                            preload="metadata"
                            <?php if ($video_poster_url): ?>
                                poster="<?php echo esc_url($video_poster_url); ?>"
                            <?php endif; ?>
                            class="h-full w-full object-cover"
                        >
                            <source src="<?php echo esc_url($video_url); ?>"<?php echo $video_mime ? ' type="' . esc_attr($video_mime) . '"' : ''; ?>>
                        </video>
                    <?php elseif ($afbeelding): ?>
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
