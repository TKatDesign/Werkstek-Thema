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

<section class="overflow-hidden bg-light-gray pt-20 sm:py-0 lg:bg-white lg:pt-10 lg:pb-40">
    <div class="mx-auto max-w-7xl lg:px-6">
        <div class="relative overflow-hidden lg:rounded-[0.75rem] lg:bg-[#f4f3f1]">
            <div class="relative z-10 px-5 sm:px-8 lg:flex lg:w-[56%] lg:flex-col lg:justify-center lg:px-16 lg:py-20">
                <h1 class="max-w-[9ch] text-[3.25rem] font-bold leading-[0.98] text-slate-900 sm:text-6xl lg:text-[4.75rem]">
                    <?php echo nl2br(esc_html($titel)); ?>
                </h1>

                <?php if ($is_een_lijst && is_array($lijst) && ! empty($lijst)): ?>
                    <ul class="mt-7 max-w-[33rem] space-y-3 text-lg font-normal leading-5 text-slate-900 lg:text-lg">
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
                            <li class="flex items-start gap-2">
                                <img src="<?php echo esc_url($check_icon_url); ?>" alt="" class="mt-[-2px] h-5 w-5 shrink-0 object-contain" loading="lazy">
                                <span><?php echo esc_html($item_tekst); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif ($tekst): ?>
                    <div class="mt-7 max-w-[33rem] text-lg font-normal leading-8 text-slate-900 lg:text-lg">
                        <?php echo wp_kses_post(wpautop($tekst)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($button_tekst && $button_url): ?>
                    <div class="mt-8">
                        <a
                            href="<?php echo esc_url($button_url); ?>"
                            target="<?php echo esc_attr($button_target); ?>"
                            rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                            class="inline-flex h-14 items-center gap-5 rounded-full bg-slate-900 py-1.5 pl-6 pr-3 text-base font-normal text-white transition hover:bg-slate-800"
                        >
                            <span><?php echo esc_html($button_tekst); ?></span>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-accent text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <path d="M7 17 17 7"></path>
                                    <path d="M9 7h8v8"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-5 h-[445px] w-[135%] -translate-x-[18%] overflow-hidden sm:h-[520px] lg:absolute lg:inset-y-0 lg:right-0 lg:mt-0 lg:h-full lg:w-[47%] lg:translate-x-0">
                <?php if ($afbeelding_url): ?>
                    <img
                        src="<?php echo esc_url($afbeelding_url); ?>"
                        alt="<?php echo esc_attr($afbeelding_alt); ?>"
                        class="h-full w-full object-cover object-top lg:object-center"
                        loading="eager"
                    >
                <?php else: ?>
                    <div class="flex h-full w-full items-center justify-center bg-slate-200 px-8 text-center text-sm font-medium text-slate-500">
                        Voeg een detail hero-afbeelding toe
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
