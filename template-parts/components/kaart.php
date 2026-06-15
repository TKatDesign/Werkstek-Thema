<?php
$locations = [];

$clamp_coordinate = static function ($value): float {
    $value = str_replace(',', '.', trim(str_replace('%', '', (string) $value)));

    if ($value === '' || ! is_numeric($value)) {
        return 50.0;
    }

    return max(0, min(100, (float) $value));
};

if (have_rows('locatie')) {
    while (have_rows('locatie')) {
        the_row();

        $name = trim((string) get_sub_field('locatie_naam'));

        if ($name === '') {
            continue;
        }

        $locations[] = [
            'name' => $name,
            'x' => $clamp_coordinate(get_sub_field('x_coordinaten')),
            'y' => $clamp_coordinate(get_sub_field('y_coordinaten')),
        ];
    }
}

if (empty($locations)) {
    return;
}

$title = trim((string) get_sub_field('titel'));
$text = trim((string) (get_sub_field('tekst') ?: get_sub_field('beschrijving')));

if ($title === '') {
    $title = 'Over geheel Nederland';
}

if ($text === '') {
    $text = 'Transistorstraat 31 biedt een uitstekende locatie voor bedrijven, gelegen nabij afrit A6 en op slechts 20 minuten rijden van zowel Amsterdam als Hilversum. Dit kantoorverzamelgebouw is gunstig bereikbaar per auto en openbaar vervoer, met diverse metrages beschikbaar voor huurders van elke omvang.';
}

$component_id = 'kaart-' . wp_unique_id();
$map_url = get_template_directory_uri() . '/resources/images/verhuren/kaart-01.svg';
$pin_active_url = get_template_directory_uri() . '/resources/images/verhuren/pin-active.svg';
$pin_inactive_url = get_template_directory_uri() . '/resources/images/verhuren/pin-inactive.svg';
?>

<section class="py-14 lg:py-24" data-location-map="<?php echo esc_attr($component_id); ?>">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 sm:px-6 lg:grid-cols-[minmax(0,1fr)_minmax(28rem,39.75rem)] lg:gap-14">
        <div>
            <h2 class="max-w-3xl text-4xl font-bold leading-tight text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                <?php echo esc_html($title); ?>
            </h2>

            <div class="mt-5 max-w-2xl text-base font-medium leading-7 text-slate-900 sm:text-lg">
                <?php echo wp_kses_post(wpautop($text)); ?>
            </div>

            <div class="mt-8 flex flex-wrap gap-3" role="tablist" aria-label="<?php echo esc_attr($title); ?>">
                <?php foreach ($locations as $index => $location): ?>
                    <?php
                    $is_active = $index === 0;
                    $location_id = $component_id . '-location-' . $index;
                    ?>
                    <button
                        type="button"
                        class="inline-flex min-h-10 items-center rounded-full px-5 py-2 text-base font-medium leading-none transition <?php echo $is_active ? 'bg-orange-accent text-white' : 'bg-[#eeeeee] text-slate-900 hover:bg-slate-200'; ?>"
                        id="<?php echo esc_attr($location_id . '-tab'); ?>"
                        data-location-map-pill="<?php echo esc_attr($index); ?>"
                        aria-controls="<?php echo esc_attr($location_id . '-pin'); ?>"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        role="tab"
                    >
                        <?php echo esc_html($location['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-[39.75rem]" aria-label="<?php echo esc_attr('Kaart met locaties voor ' . $title); ?>">
            <img
                src="<?php echo esc_url($map_url); ?>"
                alt=""
                class="block h-auto w-full"
                loading="lazy"
                aria-hidden="true"
            >

            <?php foreach ($locations as $index => $location): ?>
                <?php
                $is_active = $index === 0;
                $location_id = $component_id . '-location-' . $index;
                ?>
                <button
                    type="button"
                    class="absolute z-10 h-9 w-7 -translate-x-1/2 -translate-y-full transition duration-200 hover:scale-110 focus:outline-none focus-visible:ring-4 focus-visible:ring-orange-accent/30 lg:h-11 lg:w-9 <?php echo $is_active ? 'scale-110' : 'scale-100'; ?>"
                    id="<?php echo esc_attr($location_id . '-pin'); ?>"
                    style="left: <?php echo esc_attr((string) $location['x']); ?>%; top: <?php echo esc_attr((string) $location['y']); ?>%;"
                    data-location-map-pin="<?php echo esc_attr($index); ?>"
                    data-active-src="<?php echo esc_url($pin_active_url); ?>"
                    data-inactive-src="<?php echo esc_url($pin_inactive_url); ?>"
                    aria-label="<?php echo esc_attr($location['name']); ?>"
                    aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                >
                    <img
                        src="<?php echo esc_url($is_active ? $pin_active_url : $pin_inactive_url); ?>"
                        alt=""
                        class="h-full w-full"
                        aria-hidden="true"
                    >
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
