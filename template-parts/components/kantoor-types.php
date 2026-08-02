<?php
$blokken = ['blok_1', 'blok_2', 'blok_3'];
$standaard_titels = ['Kantoorruimtes', 'Vergaderruimtes', 'Werkplekken'];
$standaard_beschrijving = 'Van grote tot kleine ruimtes, met alle faciliteiten die je nodig hebt.';
$kantoor_types = [];

foreach ($blokken as $index => $blok_key) {
    $blok = get_sub_field($blok_key);

    if (! is_array($blok)) {
        $blok = [];
    }

    $link = $blok['link'] ?? $blok['button_link'] ?? $blok['url'] ?? '#';
    $link_url = is_array($link) ? ($link['url'] ?? '#') : $link;
    $link_target = is_array($link) ? ($link['target'] ?? '_self') : '_self';
    $icoon = $blok['afbeelding'] ?? null;
    $icoon_url = '';
    $icoon_alt = '';

    if (is_array($icoon)) {
        $icoon_url = $icoon['sizes']['thumbnail'] ?? $icoon['url'] ?? '';
        $icoon_alt = $icoon['alt'] ?? '';
    } elseif (is_numeric($icoon)) {
        $icoon_url = wp_get_attachment_image_url((int) $icoon, 'thumbnail') ?: '';
        $icoon_alt = get_post_meta((int) $icoon, '_wp_attachment_image_alt', true);
    } elseif (is_string($icoon)) {
        $icoon_url = $icoon;
    }

    $kantoor_types[] = [
        'title' => $blok['titel'] ?? $standaard_titels[$index],
        'description' => $blok['beschrijving'] ?? $blok['tekst'] ?? $standaard_beschrijving,
        'url' => $link_url ?: '#',
        'target' => $link_target,
        'icon' => $icoon_url,
        'icon_alt' => $icoon_alt,
    ];
}
?>

<section class="py-16 sm:py-20 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-6">
        <h2 class="text-center text-[2.5rem] font-bold leading-tight text-slate-900 sm:text-5xl">
            Alles in 1 bij Werkstek
        </h2>

        <div class="mt-12 grid gap-6 md:grid-cols-3 lg:mt-12">
            <?php foreach ($kantoor_types as $index => $type): ?>
                <article class="flex min-h-[400px] flex-col items-center rounded-[1.5rem] bg-[#f3f2f1] px-7 py-16 text-center sm:px-9 md:min-h-[448px] lg:px-12">
                    <div class="flex h-20 w-20 items-center justify-center rounded-br-[2.5rem] bg-white text-[#ff5a32]">
                        <?php if ($type['icon']): ?>
                            <img src="<?php echo esc_url($type['icon']); ?>" alt="<?php echo esc_attr($type['icon_alt']); ?>" class="h-8 w-8 object-contain" loading="lazy">
                        <?php elseif ($index === 0): ?>
                            <svg viewBox="0 0 32 32" class="h-7 w-7 fill-none stroke-current" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7.5 27.5V10.5h6v17m0-21h10.5v21M10.5 14h1m-1 4h1m-1 4h1m6-11h2m-2 4h2m-2 4h2m-2 4h2M5 27.5h22" />
                                <path d="M16.5 27.5v-21h7.5" />
                            </svg>
                        <?php elseif ($index === 1): ?>
                            <svg viewBox="0 0 32 32" class="h-7 w-7 fill-none stroke-current" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="16" cy="10" r="4" /><path d="M9.5 21.5c0-3.1 2.9-5.5 6.5-5.5s6.5 2.4 6.5 5.5-2.9 4-6.5 4-6.5-.9-6.5-4Z" />
                                <path d="M9 8.5a3.2 3.2 0 1 0 0 6.4M23 8.5a3.2 3.2 0 1 1 0 6.4M6.5 17c-2.3.5-4 2-4 3.8 0 2 2 3.1 4.8 3.1M25.5 17c2.3.5 4 2 4 3.8 0 2-2 3.1-4.8 3.1" />
                            </svg>
                        <?php else: ?>
                            <svg viewBox="0 0 32 32" class="h-7 w-7 fill-none stroke-current" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M10 16.5V9a6 6 0 0 1 12 0v7.5M7 16.5h18v2.2a4.3 4.3 0 0 1-4.3 4.3h-9.4A4.3 4.3 0 0 1 7 18.7v-2.2Z" />
                                <path d="M11 23v3.5m10-3.5v3.5M8.5 27h5m5 0h5" />
                            </svg>
                        <?php endif; ?>
                    </div>

                    <h3 class="mt-12 text-2xl font-bold leading-tight text-slate-900">
                        <?php echo esc_html($type['title']); ?>
                    </h3>
                    <p class="mt-6 max-w-[300px] text-base leading-6 text-slate-900">
                        <?php echo esc_html($type['description']); ?>
                    </p>

                    <a href="<?php echo esc_url($type['url']); ?>" target="<?php echo esc_attr($type['target']); ?>" class="group mt-auto inline-flex items-center rounded-full bg-[#4b743f] py-2 pl-6 pr-2 text-base font-medium text-white transition hover:bg-[#3e6334] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4b743f] focus-visible:ring-offset-2">
                        Bekijken
                        <span class="ml-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-900 transition group-hover:translate-x-0.5" aria-hidden="true">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 17 17 7M9 7h8v8" />
                            </svg>
                        </span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
