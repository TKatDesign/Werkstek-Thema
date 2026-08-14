<?php
$sectie_label = get_sub_field('label') ?: 'Pak je kans!';
$sectie_titel = get_sub_field('titel') ?: 'De mooiste locaties';
$button_tekst = get_sub_field('button_tekst') ?: 'Alle locaties';
$button_link = get_sub_field('button_link');
$button_url = get_post_type_archive_link('kantoorruimte') ?: '#';
$button_target = '_self';

if (is_array($button_link)) {
    $button_url = $button_link['url'] ?? '#';
    $button_target = $button_link['target'] ?? '_self';
} elseif (is_string($button_link) && $button_link !== '') {
    $button_url = $button_link;
}

$terms = get_terms([
    'taxonomy' => 'locatie',
    'hide_empty' => true,
    'number' => 9,
    'orderby' => 'count',
    'order' => 'DESC',
]);

if (empty($terms) || is_wp_error($terms)) {
    return;
}

$locaties = array_map('werkstek_get_locatie_card_data', $terms);
$locaties = array_filter($locaties);

if (empty($locaties)) {
    return;
}
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <span class="inline-flex items-center rounded-full bg-surface-200 px-4 py-2 text-sm font-medium text-slate-600">
                    <?php echo esc_html($sectie_label); ?>
                </span>
                <h2 class="mt-5 text-4xl font-bold text-slate-900 sm:text-5xl">
                    <?php echo esc_html($sectie_titel); ?>
                </h2>
            </div>

            <div>
                <a
                    href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"
                    rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                    class="inline-flex items-center gap-1 rounded-full text-base font-medium text-green-accent transition"
                >
                    <span class="underline"><?php echo esc_html($button_tekst); ?></span>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-green-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>

        <div class="mt-10 grid gap-5 lg:mt-14 lg:grid-cols-3 sm:grid-cols-2">
            <?php foreach ($locaties as $locatie): ?>
                <a
                    href="<?php echo esc_url($locatie['url']); ?>"
                    class="group flex items-center gap-4 rounded-[1.75rem] bg-surface-200 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(15,23,42,0.1)]"
                >
                    <img
                        src="<?php echo esc_url($locatie['image']); ?>"
                        alt="<?php echo esc_attr($locatie['name']); ?>"
                        class="h-20 w-20 shrink-0 rounded-[1.25rem] object-cover"
                    >

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-xl font-bold text-slate-900">
                            <?php echo esc_html($locatie['name']); ?>
                        </h3>
                        <p class="mt-2 text-base text-slate-400">
                            <?php echo esc_html($locatie['count_label']); ?>
                        </p>
                    </div>

                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-600 transition group-hover:bg-slate-100 group-hover:text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
