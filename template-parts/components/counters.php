<?php
$counters = [];

if (have_rows('counter')) {
    while (have_rows('counter')) {
        the_row();

        $icoon = get_sub_field('icoon');
        $getal = get_sub_field('getal');
        $tekst = get_sub_field('tekst');
        $icoon_url = '';
        $icoon_alt = '';

        if (is_array($icoon)) {
            $icoon_url = $icoon['url'] ?? '';
            $icoon_alt = $icoon['alt'] ?? '';
        } elseif (is_numeric($icoon)) {
            $icoon_url = wp_get_attachment_image_url((int) $icoon, 'medium') ?: wp_get_attachment_url((int) $icoon);
            $icoon_alt = get_post_meta((int) $icoon, '_wp_attachment_image_alt', true);
        } elseif (is_string($icoon)) {
            $icoon_url = $icoon;
        }

        if ($getal === '' || $getal === null || ! $tekst) {
            continue;
        }

        $counters[] = [
            'icon' => $icoon_url,
            'icon_alt' => $icoon_alt,
            'number' => (int) $getal,
            'text' => $tekst,
        ];
    }
}

if (empty($counters)) {
    return;
}
?>

<section class="py-20 sm:py-16 lg:py-16" data-counters>
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <div class="flex justify-center gap-2 text-[#f3cf55]" aria-hidden="true">
                <img src="<?php bloginfo( 'template_url' ) ?>/resources/images/stars.svg" class="w-40 lg:w-40">
            </div>

            <h2 class="mt-7 text-4xl font-bold leading-[1.05] text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                De leukste werkplekken<br class="hidden sm:block"> van Nederland
            </h2>
        </div>

        <div class="mx-auto mt-9 flex max-w-[760px] flex-wrap justify-center gap-5 sm:mt-12 lg:max-w-[920px] lg:gap-6">
            <?php foreach ($counters as $counter): ?>
                <article class="flex min-h-[175px] w-[220px] flex-col items-center justify-center rounded-[1.25rem] bg-surface-200 p-5 text-center sm:min-h-[220px] sm:max-w-[280px] sm:flex-1 sm:items-start sm:p-10 sm:text-left">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-surface text-orange-accent sm:h-16 sm:w-16">
                        <?php if ($counter['icon']): ?>
                            <img
                                src="<?php echo esc_url($counter['icon']); ?>"
                                alt="<?php echo esc_attr($counter['icon_alt']); ?>"
                                class="h-8 w-8 object-contain"
                                loading="lazy"
                            >
                        <?php endif; ?>
                    </div>

                    <div class="mt-5 text-[2rem] font-bold leading-none text-slate-900 sm:mt-8 sm:text-[2.5rem]">
                        <span data-counter-number data-counter-target="<?php echo esc_attr($counter['number']); ?>">0</span><span>+</span>
                    </div>
                    <p class="mt-2 text-base font-normal leading-tight text-slate-900">
                        <?php echo esc_html($counter['text']); ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
