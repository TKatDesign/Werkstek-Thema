<?php
$titel = get_sub_field('titel') ?: 'Onze community';
$tekst = get_sub_field('tekst') ?: '';
$is_een_lijst = (bool) get_sub_field('is_een_lijst');
$lijst = get_sub_field('lijst');
$button_tekst = get_sub_field('button_tekst') ?: 'Sluit je aan';
$button_url_field = get_sub_field('button_url');
$afbeelding = get_sub_field('afbeelding');
$button_url = '#';
$button_target = '_self';
$afbeelding_url = '';
$afbeelding_alt = '';
$check_icon_url = get_stylesheet_directory_uri() . '/resources/images/kantoorruimte/solar_check-circle-outline.svg';

if (is_array($button_url_field)) {
    $button_url = $button_url_field['url'] ?? '#';
    $button_target = $button_url_field['target'] ?? '_self';
} elseif (is_string($button_url_field) && $button_url_field !== '') {
    $button_url = $button_url_field;
}

if (is_array($afbeelding)) {
    $afbeelding_url = $afbeelding['sizes']['large'] ?? $afbeelding['url'] ?? '';
    $afbeelding_alt = $afbeelding['alt'] ?? $titel;
} elseif (is_numeric($afbeelding)) {
    $afbeelding_url = wp_get_attachment_image_url((int) $afbeelding, 'large') ?: '';
    $afbeelding_alt = get_post_meta((int) $afbeelding, '_wp_attachment_image_alt', true) ?: $titel;
} elseif (is_string($afbeelding)) {
    $afbeelding_url = $afbeelding;
    $afbeelding_alt = $titel;
}
?>

<section class="overflow-hidden bg-surface">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="relative grid min-h-[calc(100vh-6rem)] items-center gap-10 py-16 sm:py-20 lg:grid-cols-[minmax(0,0.9fr)_minmax(32rem,1.1fr)] lg:gap-8 lg:py-12">
            <div class="relative z-10 lg:pb-4">
                <h1 class="max-w-[9ch] text-[3.5rem] font-bold text-dark-main sm:text-6xl lg:text-[4.25rem]">
                    <?php echo nl2br(esc_html($titel)); ?>
                </h1>

                <?php if ($is_een_lijst && is_array($lijst) && ! empty($lijst)): ?>
                    <ul class="mt-10 max-w-[33rem] space-y-3 text-lg font-normal leading-6 text-dark-main lg:mt-12 lg:text-xl">
                        <?php foreach ($lijst as $item): ?>
                            <?php
                            $item_tekst = '';

                            if (is_array($item)) {
                                $item_tekst = trim((string) ($item['tekst'] ?? ''));
                            } elseif (is_string($item)) {
                                $item_tekst = trim($item);
                            }

                            if ($item_tekst === '') {
                                continue;
                            }
                            ?>
                            <li class="flex items-start gap-3">
                                <img src="<?php echo esc_url($check_icon_url); ?>" alt="" class="mt-0.5 h-5 w-5 shrink-0 object-contain" loading="lazy">
                                <span><?php echo esc_html($item_tekst); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif ($tekst): ?>
                    <div class="mt-10 max-w-[33rem] text-lg font-normal leading-8 text-dark-main lg:mt-12 lg:text-xl">
                        <?php echo wp_kses_post(wpautop($tekst)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($button_tekst && $button_url): ?>
                    <div class="mt-10">
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
                <?php endif; ?>
            </div>

            <div class="relative mx-auto aspect-square w-full max-w-[39rem] self-center lg:justify-self-end">
                <?php if ($afbeelding_url): ?>
                    <img
                        src="<?php echo esc_url($afbeelding_url); ?>"
                        alt="<?php echo esc_attr($afbeelding_alt); ?>"
                        class="h-full w-full object-contain object-center"
                        loading="eager"
                        fetchpriority="high"
                    >
                <?php else: ?>
                    <div class="flex h-full w-full items-center justify-center rounded-full bg-surface-200 px-8 text-center text-sm font-medium text-slate-500">
                        Voeg een detail hero-afbeelding toe
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
