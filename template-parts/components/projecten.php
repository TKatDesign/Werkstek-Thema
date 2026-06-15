<?php
$projects = [];

$normalize_link = static function ($link): array {
    if (is_array($link)) {
        return [
            'url' => $link['url'] ?? '',
            'target' => $link['target'] ?? '_self',
        ];
    }

    return [
        'url' => is_string($link) ? trim($link) : '',
        'target' => '_self',
    ];
};

if (have_rows('project')) {
    while (have_rows('project')) {
        the_row();

        $image = get_sub_field('afbeelding');
        $image_data = function_exists('werkstek_normalize_acf_image')
            ? werkstek_normalize_acf_image($image, 'large')
            : ['url' => '', 'alt' => ''];
        $address = trim((string) get_sub_field('adres'));
        $city = trim((string) get_sub_field('plaatsnaam'));
        $link = $normalize_link(get_sub_field('button_url'));
        $benefits = array_filter([
            trim((string) get_sub_field('voordeel_1')),
            trim((string) get_sub_field('voordeel_2')),
            trim((string) get_sub_field('voordeel_3')),
        ]);

        if (empty($image_data['url']) || $address === '') {
            continue;
        }

        $projects[] = [
            'image' => $image_data['url'],
            'image_alt' => $image_data['alt'] ?: trim($address . ' ' . $city),
            'address' => $address,
            'city' => $city,
            'url' => $link['url'],
            'target' => $link['target'],
            'benefits' => $benefits,
        ];
    }
}

if (empty($projects)) {
    return;
}

$section_title = trim((string) get_sub_field('titel'));

if ($section_title === '') {
    $section_title = 'Een aantal projecten';
}

$component_id = 'projecten-' . wp_unique_id();
$has_slider = count($projects) > 1;
?>

<section class="overflow-hidden py-14 lg:py-24" data-projects-slider>
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-4xl font-bold leading-tight text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>

        <div class="relative mt-10 lg:mt-14 lg:grid lg:grid-cols-[3.5rem_minmax(0,1fr)_3.5rem] lg:items-center lg:gap-6">
            <button
                type="button"
                class="<?php echo $has_slider ? 'hidden lg:inline-flex' : 'hidden'; ?> h-14 w-14 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-300 shadow-sm transition hover:border-slate-300 hover:text-slate-900 disabled:pointer-events-none disabled:opacity-45"
                data-projects-prev
                aria-label="Vorig project"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                    <path d="M19 12H5"></path>
                    <path d="m12 19-7-7 7-7"></path>
                </svg>
            </button>

            <div class="relative cursor-grab select-none overflow-hidden rounded-[1.5rem] bg-slate-100 touch-pan-y active:cursor-grabbing lg:rounded-[1.75rem]" data-projects-slides>
                <?php foreach ($projects as $index => $project): ?>
                    <?php $is_active = $index === 0; ?>
                    <article
                        id="<?php echo esc_attr($component_id . '-slide-' . $index); ?>"
                        class="<?php echo $is_active ? 'opacity-100' : 'pointer-events-none absolute inset-0 opacity-0'; ?> transition-opacity duration-500 ease-out"
                        data-projects-slide
                        aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>"
                    >
                        <div class="relative min-h-[620px] sm:min-h-[680px] lg:min-h-[620px]">
                            <img
                                src="<?php echo esc_url($project['image']); ?>"
                                alt="<?php echo esc_attr($project['image_alt']); ?>"
                                class="pointer-events-none absolute inset-0 h-full w-full select-none object-cover"
                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                draggable="false"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/10 via-transparent to-transparent"></div>

                            <div class="absolute bottom-5 left-5 right-5 max-w-[26rem] rounded-[1.25rem] bg-white p-6 shadow-[0_24px_55px_rgba(15,23,42,0.14)] sm:bottom-10 sm:left-10 sm:p-8 lg:bottom-12 lg:left-12">
                                <h3 class="text-2xl font-bold leading-tight text-slate-900 lg:text-[1.65rem]">
                                    <?php echo esc_html($project['address']); ?>
                                </h3>

                                <?php if ($project['city']): ?>
                                    <p class="mt-1 text-base font-medium leading-6 text-slate-400">
                                        <?php echo esc_html($project['city']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (! empty($project['benefits'])): ?>
                                    <ul class="mt-5 space-y-2.5">
                                        <?php foreach ($project['benefits'] as $benefit): ?>
                                            <li class="flex items-start gap-3 text-base font-medium leading-6 text-slate-900 sm:text-lg">
                                                <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-green-accent text-green-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.5]">
                                                        <path d="m6 12 4 4 8-8"></path>
                                                    </svg>
                                                </span>
                                                <span><?php echo esc_html($benefit); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if ($project['url']): ?>
                                    <a
                                        href="<?php echo esc_url($project['url']); ?>"
                                        target="<?php echo esc_attr($project['target']); ?>"
                                        rel="<?php echo $project['target'] === '_blank' ? 'noopener noreferrer' : ''; ?>"
                                        class="mt-7 inline-flex items-center gap-4 rounded-full bg-[#e8e6e3] py-2 pl-6 pr-2 text-base font-medium text-slate-900 transition hover:bg-slate-200"
                                    >
                                        <span>Bekijk kantoor</span>
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                                <path d="M7 17 17 7"></path>
                                                <path d="M9 7h8v8"></path>
                                            </svg>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <button
                type="button"
                class="<?php echo $has_slider ? 'hidden lg:inline-flex' : 'hidden'; ?> h-14 w-14 items-center justify-center rounded-full bg-[#e8e6e3] text-slate-900 shadow-sm transition hover:bg-slate-200 disabled:pointer-events-none disabled:opacity-45"
                data-projects-next
                aria-label="Volgend project"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </button>

            <?php if ($has_slider): ?>
                <div class="mt-5 flex justify-center gap-3 lg:hidden" data-projects-dots>
                    <?php foreach ($projects as $index => $project): ?>
                        <button
                            type="button"
                            class="h-2.5 w-2.5 rounded-full bg-slate-300 transition <?php echo $index === 0 ? 'scale-125 bg-slate-900' : ''; ?>"
                            data-projects-dot="<?php echo esc_attr($index); ?>"
                            data-projects-dot-theme="dark"
                            aria-label="<?php echo esc_attr('Toon project ' . ($index + 1)); ?>"
                            aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        ></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
