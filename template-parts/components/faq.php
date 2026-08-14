<?php
$subtitel = trim((string) get_sub_field('subtitel')) ?: 'Veelgestelde vragen';
$titel = trim((string) get_sub_field('titel')) ?: 'Heb je nog vragen?';
$items = [];

if (have_rows('onderdeel')) {
    while (have_rows('onderdeel')) {
        the_row();

        $vraag = trim((string) get_sub_field('vraag'));
        $antwoord = trim((string) get_sub_field('antwoord'));

        if ($vraag === '') {
            continue;
        }

        $items[] = [
            'question' => $vraag,
            'answer' => $antwoord,
        ];
    }
}

if (empty($items)) {
    return;
}

$component_id = 'faq-' . wp_unique_id();
?>

<section class="mt-32">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-6 lg:px-12">
        <div class="mx-auto max-w-[696px] text-center">
            <?php if ($subtitel): ?>
                <p class="text-xl font-medium leading-tight text-slate-900 sm:text-1xl">
                    <?php echo esc_html($subtitel); ?>
                </p>
            <?php endif; ?>

            <h2 class="<?php echo $subtitel ? 'mt-5' : ''; ?> text-4xl font-bold leading-[1.05] text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                <?php echo esc_html($titel); ?>
            </h2>
        </div>

        <div class="mx-auto mt-10 flex max-w-[696px] flex-col gap-5 lg:mt-11" data-faq>
            <?php foreach ($items as $index => $item): ?>
                <?php $is_open = $index === 0; ?>
                <details
                    class="group overflow-hidden rounded-[0.75rem] bg-[#EDD1B5] text-left text-dark-main/50 transition open:bg-surface-200 open:text-slate-900 open:shadow-[0_10px_16px_rgba(15,23,42,0.03)]"
                    name="<?php echo esc_attr($component_id); ?>"
                    <?php echo $is_open ? 'open' : ''; ?>
                >
                    <summary class="flex min-h-[72px] cursor-pointer list-none items-center justify-between gap-5 px-6 py-5 text-xl font-bold leading-tight marker:hidden [&::-webkit-details-marker]:hidden sm:px-7">
                        <span><?php echo esc_html($item['question']); ?></span>
                        <span class="relative h-5 w-5 shrink-0 text-current" aria-hidden="true">
                            <span class="absolute left-1/2 top-1/2 h-0.5 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full bg-current"></span>
                            <span class="absolute left-1/2 top-1/2 h-4 w-0.5 -translate-x-1/2 -translate-y-1/2 rounded-full bg-current transition group-open:opacity-0"></span>
                        </span>
                    </summary>

                    <?php if ($item['answer'] !== ''): ?>
                        <div class="px-6 pb-6 text-base font-normal leading-7 text-slate-900 sm:px-7 sm:pb-7 sm:leading-8">
                            <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                        </div>
                    <?php endif; ?>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
