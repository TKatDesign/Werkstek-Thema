<?php
$blokken = ['blok_1', 'blok_2', 'blok_3'];
$kantoor_types = [];

foreach ($blokken as $blok_key) {
    $blok = get_sub_field($blok_key);

    if (!$blok || empty($blok['titel']) || empty($blok['afbeelding'])) {
        continue;
    }

    $afbeelding = $blok['afbeelding'];
    $afbeelding_url = '';

    if (is_array($afbeelding)) {
        $afbeelding_url = $afbeelding['url'] ?? '';
    } elseif (is_numeric($afbeelding)) {
        $afbeelding_url = wp_get_attachment_image_url((int) $afbeelding, 'full');
    } elseif (is_string($afbeelding)) {
        $afbeelding_url = $afbeelding;
    }

    if (!$afbeelding_url) {
        continue;
    }

    $kantoor_types[] = [
        'title' => $blok['titel'],
        'image' => $afbeelding_url,
        'url' => '#',
        'label' => 'Per direct',
        'count' => '21 objecten',
    ];
}

if (empty($kantoor_types)) {
    return;
}
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                Wij maken werken weer leuk
            </span>
            <h2 class="mt-5 text-4xl font-bold text-slate-900 sm:text-5xl">
                Alles wat je nodig hebt
            </h2>
        </div>

        <div class="mt-10 grid gap-6 lg:mt-14 lg:grid-cols-3">
            <?php foreach ($kantoor_types as $type): ?>
                <a
                    href="<?php echo esc_url($type['url']); ?>"
                    class="group relative block min-h-[380px] overflow-hidden rounded-[2rem] bg-slate-200 shadow-[0_24px_50px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_32px_70px_rgba(15,23,42,0.14)] lg:min-h-[440px]"
                    style="
                        background-image:
                            linear-gradient(to top, rgba(15, 23, 42, 0.18), rgba(15, 23, 42, 0.02)),
                            url('<?php echo esc_url($type['image']); ?>');
                        background-size: cover;
                        background-position: center center;
                    "
                >
                    <span class="absolute right-5 top-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-orange-500 shadow-sm transition group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>

                    <div class="absolute inset-x-4 bottom-4 rounded-[1.75rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.08)] lg:inset-x-5 lg:bottom-5">
                        <h3 class="text-2xl font-bold text-slate-900">
                            <?php echo esc_html($type['title']); ?>
                        </h3>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span class="inline-flex rounded-full bg-green-accent px-4 py-1.5 text-sm font-medium text-white">
                                <?php echo esc_html($type['label']); ?>
                            </span>
                            <span class="inline-flex rounded-full bg-slate-100 px-4 py-1.5 text-sm font-medium text-slate-600">
                                <?php echo esc_html($type['count']); ?>
                            </span>
                        </div>

                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
