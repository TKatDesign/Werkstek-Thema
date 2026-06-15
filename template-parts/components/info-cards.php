<?php
$items = [];

if (have_rows('items')) {
    while (have_rows('items')) {
        the_row();

        $titel = trim((string) get_sub_field('titel'));
        $tekst = trim((string) get_sub_field('tekst'));
        $afbeelding = get_sub_field('afbeelding');
        $afbeelding_data = function_exists('werkstek_normalize_acf_image')
            ? werkstek_normalize_acf_image($afbeelding, 'large')
            : ['url' => '', 'alt' => ''];

        if ($titel === '' || $tekst === '' || empty($afbeelding_data['url'])) {
            continue;
        }

        $items[] = [
            'title' => $titel,
            'text' => $tekst,
            'image' => $afbeelding_data['url'],
            'alt' => $afbeelding_data['alt'] ?: $titel,
        ];
    }
}

if (empty($items)) {
    return;
}

$grid_classes = 'mt-10 grid gap-6 lg:mt-14';

if (count($items) === 2) {
    $grid_classes .= ' lg:mx-auto lg:max-w-[860px] lg:grid-cols-2 lg:justify-center';
} elseif (count($items) === 3) {
    $grid_classes .= ' lg:grid-cols-3';
} else {
    $grid_classes .= ' lg:grid-cols-3';
}
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-4xl font-bold text-slate-900 sm:text-5xl">
                Wij regelen het
            </h2>
        </div>

        <div class="<?php echo esc_attr($grid_classes); ?>">
            <?php foreach ($items as $item): ?>
                <article class="group relative w-full overflow-hidden rounded-[2rem] bg-slate-200 shadow-[0_24px_50px_rgba(15,23,42,0.08)]">
                    <div class="relative min-h-[420px] overflow-hidden bg-slate-200 lg:min-h-[500px]">
                        <img
                            src="<?php echo esc_url($item['image']); ?>"
                            alt="<?php echo esc_attr($item['alt']); ?>"
                            class="absolute inset-0 h-full w-full object-cover object-center"
                            loading="lazy"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/15 via-transparent to-transparent"></div>
                    </div>

                    <div class="absolute inset-x-4 bottom-4 rounded-[1.75rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.08)] lg:inset-x-5 lg:bottom-5 lg:p-7">
                        <h3 class="text-[1.7rem] font-bold leading-tight text-slate-900">
                            <?php echo esc_html($item['title']); ?>
                        </h3>

                        <div class="mt-3 max-w-none text-base leading-7 text-slate-900">
                            <?php echo wp_kses_post(wpautop($item['text'])); ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
