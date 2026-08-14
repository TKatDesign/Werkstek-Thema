<?php
$blokken = ['blok_1', 'blok_2', 'blok_3'];
$standaard_titels = ['Kantoorruimtes', 'Vergaderruimtes', 'Werkplekken'];
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
        'url' => $link_url ?: '#',
        'target' => $link_target,
        'icon' => $icoon_url,
        'icon_alt' => $icoon_alt,
    ];
}
?>

<section class="py-12 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-6">
        <div class="grid gap-5 md:grid-cols-3 lg:gap-7">
            <?php foreach ($kantoor_types as $type): ?>
                <article>
                    <a
                        href="<?php echo esc_url($type['url']); ?>"
                        target="<?php echo esc_attr($type['target']); ?>"
                        rel="<?php echo $type['target'] === '_blank' ? 'noopener noreferrer' : ''; ?>"
                        class="group flex min-h-26 items-center gap-5 rounded-[1.75rem] bg-surface-200 p-5 pr-6 text-dark-main transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_45px_rgba(15,41,58,0.08)] focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-accent focus-visible:ring-offset-2 sm:gap-6 sm:p-6 sm:pr-7"
                    >
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-br-[1.5rem] rounded-tl-[1.5rem] bg-surface">
                            <?php if ($type['icon']): ?>
                                <img src="<?php echo esc_url($type['icon']); ?>" alt="<?php echo esc_attr($type['icon_alt']); ?>" class="h-6 w-6 object-contain" loading="lazy">
                            <?php endif; ?>
                        </span>

                        <h3 class="min-w-0 flex-1 text-xl font-bold leading-tight text-dark-main lg:text-[1.15rem]">
                            <?php echo esc_html($type['title']); ?>
                        </h3>

                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center text-dark-main transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true">
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
