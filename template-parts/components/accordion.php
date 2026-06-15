<?php
$titel = get_sub_field('titel') ?: 'Waarom een werkplek bij Werkstek?';
$afbeelding = get_sub_field('afbeelding');
$button_tekst = get_sub_field('button_tekst') ?: '';
$button_link = get_sub_field('button_link');
$button_url = '#';
$button_target = '_self';
$afbeelding_url = '';
$afbeelding_alt = $titel;
$items = [];

if (is_array($button_link)) {
    $button_url = $button_link['url'] ?? '#';
    $button_target = $button_link['target'] ?? '_self';
} elseif (is_string($button_link) && $button_link !== '') {
    $button_url = $button_link;
}

if (is_array($afbeelding)) {
    $afbeelding_url = $afbeelding['sizes']['large'] ?? $afbeelding['url'] ?? '';
    $afbeelding_alt = $afbeelding['alt'] ?? $titel;
} elseif (is_numeric($afbeelding)) {
    $afbeelding_url = wp_get_attachment_image_url((int) $afbeelding, 'large') ?: '';
    $afbeelding_alt = get_post_meta((int) $afbeelding, '_wp_attachment_image_alt', true) ?: $titel;
} elseif (is_string($afbeelding)) {
    $afbeelding_url = $afbeelding;
}

if (have_rows('items')) {
    while (have_rows('items')) {
        the_row();

        $item = get_sub_field('item');
        $item_titel = '';
        $item_tekst = '';

        if (is_array($item)) {
            $item_titel = $item['titel'] ?? '';
            $item_tekst = $item['tekst'] ?? '';
        }

        $item_titel = $item_titel ?: get_sub_field('titel');
        $item_tekst = $item_tekst ?: get_sub_field('tekst');

        if (! $item_titel) {
            continue;
        }

        $items[] = [
            'title' => $item_titel,
            'text' => $item_tekst,
        ];
    }
}

if (empty($items)) {
    return;
}

$component_id = 'accordion-' . wp_unique_id();
?>

<section class="bg-white py-14 lg:py-24">
    <div class="mx-5 rounded-[0.75rem] bg-[#f4f3f1] px-5 py-16 sm:mx-6 sm:px-8 lg:mx-12 lg:px-24 lg:py-28">
        <h2 class="mx-auto max-w-3xl text-center text-4xl font-bold leading-[1.05] text-slate-900 sm:text-5xl lg:text-[3.25rem]">
            <?php echo nl2br(esc_html($titel)); ?>
        </h2>

        <div class="mx-auto mt-12 grid max-w-[940px] items-start gap-8 lg:mt-16 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.92fr)] lg:gap-12">
            <div class="overflow-hidden rounded-[0.75rem] bg-slate-200">
                <?php if ($afbeelding_url): ?>
                    <img
                        src="<?php echo esc_url($afbeelding_url); ?>"
                        alt="<?php echo esc_attr($afbeelding_alt); ?>"
                        class="aspect-[1.27/1] w-full object-cover"
                        loading="lazy"
                    >
                <?php else: ?>
                    <div class="flex aspect-[1.27/1] items-center justify-center px-6 text-center text-sm font-medium text-slate-500">
                        Voeg een accordion-afbeelding toe
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex flex-col items-start gap-4" data-accordion>
                <?php foreach ($items as $index => $item): ?>
                    <?php
                    $is_open = $index === 0;
                    $button_id = $component_id . '-button-' . $index;
                    $panel_id = $component_id . '-panel-' . $index;
                    ?>
                    <article class="<?php echo $is_open ? 'w-full rounded-[0.75rem] bg-slate-900 text-white' : 'rounded-full bg-[#e8e6e3] text-[#a4a4a4]'; ?> overflow-hidden transition-colors duration-200" data-accordion-item>
                        <button
                            type="button"
                            id="<?php echo esc_attr($button_id); ?>"
                            class="<?php echo $is_open ? 'w-full px-6 pb-3 pt-4 text-left' : 'px-6 py-3'; ?> text-xl font-medium leading-tight"
                            data-accordion-trigger
                            aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                        >
                            <?php echo esc_html($item['title']); ?>
                        </button>

                        <div
                            id="<?php echo esc_attr($panel_id); ?>"
                            class="overflow-hidden transition-[height] duration-300 ease-out"
                            data-accordion-panel
                            role="region"
                            aria-labelledby="<?php echo esc_attr($button_id); ?>"
                            style="height: <?php echo $is_open ? 'auto' : '0px'; ?>;"
                        >
                            <div class="px-6 pb-6 text-lg font-normal leading-8 text-white">
                                <?php echo wp_kses_post(wpautop($item['text'])); ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($button_tekst && $button_url): ?>
            <div class="mt-12 flex justify-center lg:mt-16">
                <a
                    href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"
                    rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                    class="inline-flex h-14 items-center gap-5 rounded-full bg-slate-900 py-1.5 pl-6 pr-1.5 text-base font-normal text-white transition hover:bg-slate-800"
                >
                    <span><?php echo esc_html($button_tekst); ?></span>
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-green-accent text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
