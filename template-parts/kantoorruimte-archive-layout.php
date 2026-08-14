<?php
$archive_context = $args['context'] ?? 'archive';
$archive_title = $args['title'] ?? 'Kantoorruimte huren';
$archive_description = $args['description'] ?? '';
$seo_content = $args['seo_content'] ?? '';
$kantoorruimtes = $args['items'] ?? [];
$archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
$pagination = function_exists('werkstek_get_kantoorruimte_archive_pagination') ? werkstek_get_kantoorruimte_archive_pagination() : [
    'current_page' => 1,
    'total_pages' => 1,
    'per_page' => 8,
    'total_items' => count($kantoorruimtes),
];
$total_results = $pagination['total_items'] ?? count($kantoorruimtes);
$current_page = max(1, (int) ($pagination['current_page'] ?? 1));
$total_pages = max(1, (int) ($pagination['total_pages'] ?? 1));
$current_location = $archive_context === 'locatie' ? $archive_title : '';
$current_sort = isset($_GET['sort']) && sanitize_key(wp_unslash($_GET['sort'])) === 'nieuw' ? 'nieuw' : 'populair';
$current_min_price = isset($_GET['prijs_min']) ? sanitize_text_field(wp_unslash($_GET['prijs_min'])) : '';
$current_max_price = isset($_GET['prijs_max']) ? sanitize_text_field(wp_unslash($_GET['prijs_max'])) : '';
$locatie_items = function_exists('werkstek_get_locatie_search_items') ? werkstek_get_locatie_search_items() : [];
$queried_object = get_queried_object();
$filter_action_url = $archive_url;

if ($archive_context === 'locatie' && $queried_object instanceof WP_Term) {
    $term_url = get_term_link($queried_object);
    $filter_action_url = is_wp_error($term_url) ? $archive_url : $term_url;
}

$sort_url = function ($sort) use ($filter_action_url, $current_min_price, $current_max_price) {
    $args = ['sort' => $sort];

    if ($current_min_price !== '') {
        $args['prijs_min'] = $current_min_price;
    }

    if ($current_max_price !== '') {
        $args['prijs_max'] = $current_max_price;
    }

    return add_query_arg($args, $filter_action_url);
};
$pagination_url = function ($page) use ($filter_action_url, $current_sort, $current_min_price, $current_max_price) {
    $args = [
        'paged' => max(1, (int) $page),
        'sort' => $current_sort,
    ];

    if ($current_min_price !== '') {
        $args['prijs_min'] = $current_min_price;
    }

    if ($current_max_price !== '') {
        $args['prijs_max'] = $current_max_price;
    }

    return add_query_arg($args, $filter_action_url);
};
$map_center = $current_location ?: 'Nederland';
$map_query = $current_location ? 'kantoorruimte ' . $current_location : 'kantoorruimte huren Nederland';
$map_items = array_map(function ($ruimte) {
    return [
        'id' => $ruimte['id'],
        'title' => $ruimte['title'],
        'url' => $ruimte['url'],
        'price' => $ruimte['price'],
        'location' => $ruimte['location'],
        'address' => $ruimte['address'],
        'lat' => $ruimte['latitude'],
        'lng' => $ruimte['longitude'],
        'query' => trim(implode(' ', array_filter([
            $ruimte['address'],
            $ruimte['title'],
            $ruimte['location'],
            'Nederland',
        ]))),
    ];
}, $kantoorruimtes);
?>

<section class="bg-surface" data-kantoorruimte-archive data-archive-view="list">
    <div class="grid xl:grid-cols-[minmax(0,56%)_minmax(420px,44%)]">
        <div class="px-5 py-10 sm:px-8 lg:px-12 lg:py-14" data-archive-content>
            <div class="ml-auto max-w-[700px]">
            <nav class="flex flex-wrap items-center gap-4 text-sm font-medium text-[#B28D74]" aria-label="Breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-[#B28D74] text-[#B28D74] transition hover:text-slate-700">
                    <span class="sr-only">Home</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-2">
                        <path d="M5 12h14"></path>
                    </svg>
                </a>
                <span aria-hidden="true">&rsaquo;</span>
                <a href="<?php echo esc_url($archive_url); ?>" class="transition hover:text-slate-700">Kantoorruimte huren</a>
                <?php if ($current_location): ?>
                    <span aria-hidden="true">&rsaquo;</span>
                    <span><?php echo esc_html($current_location); ?></span>
                <?php endif; ?>
            </nav>

            <div class="mt-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold leading-tight text-slate-950 sm:text-4xl">
                        <?php echo esc_html($archive_context === 'locatie' ? 'Kantoorruimte in ' . $archive_title : $archive_title); ?>
                    </h1>
                    <?php if ($archive_description): ?>
                        <div class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                            <?php echo wp_kses_post($archive_description); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4 sm:justify-between">
                <button
                    type="button"
                    data-filter-open
                    aria-controls="kantoorruimte-filter-panel"
                    aria-expanded="false"
                    class="inline-flex h-12 flex-1 items-center justify-center gap-3 rounded-full bg-[#FCF8F3] px-6 text-base font-semibold text-slate-900 transition hover:border-slate-300 hover:bg-slate-50 sm:flex-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <path d="M4 6h16"></path>
                        <path d="M7 12h10"></path>
                        <path d="M10 18h4"></path>
                    </svg>
                    <span>Filters</span>
                </button>

                <button
                    type="button"
                    class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-[#FCF8F3] text-slate-900 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 xl:hidden"
                    data-archive-view-toggle
                    aria-label="Toon kaart"
                    aria-pressed="false"
                >
                    <span data-archive-view-icon="map">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                            <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3z"></path>
                            <path d="M9 3v15"></path>
                            <path d="M15 6v15"></path>
                        </svg>
                    </span>
                    <span class="hidden" data-archive-view-icon="list">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                            <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                            <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                            <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                            <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                        </svg>
                    </span>
                </button>

                <div class="hidden w-max items-center rounded-full bg-surface-200 p-2 sm:inline-flex gap-2" data-archive-sort-toggle>
                    <a href="<?php echo esc_url($sort_url('populair')); ?>" class="rounded-full px-5 py-2.5 text-sm font-semibold <?php echo $current_sort === 'populair' ? 'bg-slate-950 text-white' : 'bg-[#FCF8F3] text-slate-700'; ?>">
                        Populair
                    </a>
                    <a href="<?php echo esc_url($sort_url('nieuw')); ?>" class="rounded-full px-5 py-2.5 text-sm font-semibold <?php echo $current_sort === 'nieuw' ? 'bg-slate-950 text-white' : 'bg-[#FCF8F3] text-slate-700'; ?>">
                        Nieuw
                    </a>
                </div>
            </div>

            <div class="mt-5 inline-flex w-full items-center gap-2 rounded-full bg-surface-200 p-2 sm:hidden" data-archive-sort-toggle>
                <a href="<?php echo esc_url($sort_url('populair')); ?>" class="flex-1 rounded-full px-5 py-2.5 text-center text-sm font-semibold <?php echo $current_sort === 'populair' ? 'bg-slate-950 text-white' : 'bg-[#FCF8F3] text-slate-700'; ?>">
                    Populair
                </a>
                <a href="<?php echo esc_url($sort_url('nieuw')); ?>" class="flex-1 rounded-full px-5 py-2.5 text-center text-sm font-semibold <?php echo $current_sort === 'nieuw' ? 'bg-slate-950 text-white' : 'bg-[#FCF8F3] text-slate-700'; ?>">
                    Nieuw
                </a>
            </div>

            <div class="mt-6 space-y-4" data-archive-results>
                <?php if (! empty($kantoorruimtes)): ?>
                    <?php foreach ($kantoorruimtes as $index => $ruimte): ?>
                        <article
                            class="group overflow-hidden rounded-[1.5rem] bg-[#FCF8F3] transition hover:-translate-y-0.5 hover:shadow-[0_20px_48px_rgba(15,23,42,0.09)] sm:h-[200px]"
                            data-map-card="<?php echo esc_attr($ruimte['id']); ?>"
                        >
                            <a href="<?php echo esc_url($ruimte['url']); ?>" class="grid h-full gap-0 sm:grid-cols-[250px_minmax(0,1fr)]">
                                <div class="relative min-h-[190px] bg-surface-200 sm:min-h-full">
                                    <img src="<?php echo esc_url($ruimte['image']); ?>" alt="<?php echo esc_attr($ruimte['title']); ?>" class="absolute inset-0 h-full w-full object-cover">
                                </div>

                                <div class="flex min-w-0 flex-col justify-center p-5 sm:p-7">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <span class="inline-flex rounded-full bg-surface px-3 py-1 text-xs font-semibold text-slate-700">
                                                Kantoorruimte
                                            </span>
                                            <h2 class="mt-3 text-xl font-bold leading-tight text-slate-950">
                                                <?php echo esc_html($ruimte['title']); ?><?php echo $ruimte['location'] ? ', ' . esc_html($ruimte['location']) : ''; ?>
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-4 text-base font-semibold text-slate-950">
                                        <?php if ($ruimte['price']): ?>
                                            <span>
                                                vanaf <span class="text-orange-500">€<?php echo esc_html($ruimte['price']); ?></span>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($ruimte['surface']): ?>
                                            <span>
                                                <?php echo esc_html($ruimte['surface']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (! empty($ruimte['facilities'])): ?>
                                        <div class="mt-6 flex flex-wrap items-center gap-2">
                                            <?php foreach ($ruimte['facilities'] as $facility): ?>
                                                <span class="inline-flex h-6 w-6 items-center justify-center" title="<?php echo esc_attr($facility['label']); ?>">
                                                    <img
                                                        src="<?php echo esc_url($facility['icon']); ?>"
                                                        alt="<?php echo esc_attr($facility['label']); ?>"
                                                        class="h-6 w-6 object-contain opacity-60"
                                                        loading="lazy"
                                                    >
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-[#FCF8F3] px-6 py-12 text-center text-slate-500">
                        Er zijn geen kantoorruimtes gevonden die passen bij je filters.
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav class="mt-8 grid grid-cols-[1fr_auto_1fr] items-center gap-4" aria-label="Kantoorruimtes paginering">
                    <div class="justify-self-start">
                        <?php if ($current_page > 1): ?>
                            <a href="<?php echo esc_url($pagination_url($current_page - 1)); ?>" class="inline-flex h-12 min-w-[124px] items-center justify-center rounded-full border border-slate-200 bg-[#FCF8F3] px-7 text-base font-medium text-slate-900 transition hover:border-slate-300 hover:bg-slate-50">
                                Vorige
                            </a>
                        <?php else: ?>
                            <span class="inline-flex h-12 min-w-[124px] items-center justify-center rounded-full border border-slate-200 bg-[#FCF8F3] px-7 text-base font-medium text-slate-400" aria-disabled="true">
                                Vorige
                            </span>
                        <?php endif; ?>
                    </div>

                    <span class="text-base font-medium text-slate-900">
                        Pagina <?php echo esc_html($current_page); ?>
                    </span>

                    <div class="justify-self-end">
                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?php echo esc_url($pagination_url($current_page + 1)); ?>" class="inline-flex h-12 min-w-[124px] items-center justify-center rounded-full bg-slate-950 px-7 text-base font-medium text-white transition hover:bg-slate-800">
                                Volgende
                            </a>
                        <?php else: ?>
                            <span class="inline-flex h-12 min-w-[124px] items-center justify-center rounded-full bg-slate-200 px-7 text-base font-medium text-slate-400" aria-disabled="true">
                                Volgende
                            </span>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

            <?php if ($seo_content): ?>
                <div class="seo-content mt-14 lg:mt-20">
                    <?php echo wp_kses_post($seo_content); ?>
                </div>
            <?php endif; ?>
            </div>
        </div>

        <aside class="hidden h-[calc(100vh-18.5rem)] min-h-[420px] xl:sticky xl:top-0 xl:block xl:h-[calc(100vh-7.5rem)]" data-archive-map-panel>
            <div
                class="h-full w-full bg-slate-100"
                data-kantoorruimte-map
                data-map-center="<?php echo esc_attr($map_center); ?>"
                data-map-query="<?php echo esc_attr($map_query); ?>"
                data-map-items="<?php echo esc_attr(wp_json_encode($map_items)); ?>"
                role="application"
                aria-label="<?php echo esc_attr('Google Maps kaart voor ' . $map_center); ?>"
            ></div>
        </aside>
    </div>

    <div
        class="filter-overlay pointer-events-none fixed inset-0 z-50"
        data-filter-root
        aria-hidden="true"
    >
        <button
            type="button"
            aria-label="Sluit filters"
            class="filter-overlay__backdrop"
            data-filter-close
        ></button>

        <aside
            id="kantoorruimte-filter-panel"
            class="filter-overlay__panel flex h-full w-full max-w-[425px] flex-col bg-[#FCF8F3] shadow-[-20px_0_60px_rgba(15,23,42,0.18)]"
            aria-modal="true"
            aria-label="Filters"
            role="dialog"
            tabindex="-1"
        >
            <div class="flex items-center justify-between border-b border-slate-200 px-10 py-6 max-sm:px-6">
                <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                <button
                    type="button"
                    aria-label="Sluit filters"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-900 transition hover:bg-slate-100"
                    data-filter-close
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-7 w-7 fill-none stroke-current stroke-2">
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6 6 18"></path>
                    </svg>
                </button>
            </div>

            <form action="<?php echo esc_url($filter_action_url); ?>" method="get" class="flex min-h-0 flex-1 flex-col">
                <div class="flex-1 overflow-y-auto px-10 py-12 max-sm:px-6">
                    <fieldset>
                        <legend class="text-base font-bold text-slate-400">Sorteer op</legend>
                        <div class="mt-4 flex flex-col gap-2">
                            <label class="filter-radio">
                                <input type="radio" name="sort" value="populair" <?php checked($current_sort, 'populair'); ?>>
                                <span>Populair</span>
                            </label>
                            <label class="filter-radio">
                                <input type="radio" name="sort" value="nieuw" <?php checked($current_sort, 'nieuw'); ?>>
                                <span>Nieuw</span>
                            </label>
                        </div>
                    </fieldset>

                    <div class="mt-14">
                        <h3 class="text-base font-bold text-slate-400">Locatie</h3>
                        <div class="mt-5 flex flex-col items-start gap-2.5 text-base font-bold text-slate-900" data-filter-locations>
                            <a href="<?php echo esc_url($archive_url); ?>" class="transition hover:text-orange-500 <?php echo $archive_context === 'archive' ? 'text-orange-500' : ''; ?>">Alle locaties</a>
                            <?php foreach ($locatie_items as $index => $locatie): ?>
                                <?php $is_current_filter_location = $current_location === $locatie['name']; ?>
                                <a
                                    href="<?php echo esc_url($locatie['url']); ?>"
                                    class="transition hover:text-orange-500 <?php echo $index >= 4 && ! $is_current_filter_location ? 'hidden' : ''; ?> <?php echo $is_current_filter_location ? 'text-orange-500' : ''; ?>"
                                    data-filter-location-extra="<?php echo $index >= 4 && ! $is_current_filter_location ? 'true' : 'false'; ?>"
                                >
                                    <?php echo esc_html($locatie['name']); ?>
                                </a>
                            <?php endforeach; ?>

                            <?php if (count($locatie_items) > 4): ?>
                                <button type="button" class="mt-1 inline-flex items-center gap-1 font-bold text-slate-400 underline underline-offset-2 transition hover:text-orange-500" data-filter-locations-toggle>
                                    <span data-filter-more-label>+ Meer tonen</span>
                                    <span class="hidden" data-filter-less-label>- Minder tonen</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <fieldset class="mt-14">
                        <legend class="text-base font-bold text-slate-400">Prijs</legend>
                        <div class="mt-5 flex items-center gap-4">
                            <label class="filter-price-input">
                                <span>&euro;</span>
                                <input type="number" min="0" step="1" name="prijs_min" value="<?php echo esc_attr($current_min_price); ?>" placeholder="25">
                            </label>
                            <span class="text-base font-bold text-slate-900">Tot</span>
                            <label class="filter-price-input">
                                <span>&euro;</span>
                                <input type="number" min="0" step="1" name="prijs_max" value="<?php echo esc_attr($current_max_price); ?>" placeholder="250">
                            </label>
                        </div>
                    </fieldset>
                </div>

                <div class="px-10 pb-10 pt-5 max-sm:px-6">
                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center rounded-full bg-orange-500 px-8 text-base font-bold text-white shadow-[0_18px_35px_rgba(249,115,22,0.22)] transition hover:bg-orange-600">
                        Toon resultaten
                    </button>
                </div>
            </form>
        </aside>
    </div>
</section>
