<?php
$archive_title = $args['title'] ?? 'Blog';
$posts = $args['items'] ?? [];
$pagination_query = $args['query'] ?? $GLOBALS['wp_query'];
$current_page = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$total_pages = isset($pagination_query->max_num_pages) ? max(1, (int) $pagination_query->max_num_pages) : 1;

$pagination_url = static function ($page) {
    return get_pagenum_link(max(1, (int) $page));
};
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="text-5xl font-bold leading-tight text-slate-900 sm:text-6xl lg:text-[4.25rem]">
                <?php echo esc_html($archive_title); ?>
            </h1>
        </div>

        <?php if (! empty($posts)): ?>
            <div class="mt-12 grid gap-x-8 gap-y-12 md:grid-cols-2 lg:mt-16 lg:grid-cols-3 lg:gap-x-10 lg:gap-y-16">
                <?php foreach ($posts as $blog): ?>
                    <article class="group min-w-0">
                        <a href="<?php echo esc_url($blog['url']); ?>" class="block">
                            <div class="relative overflow-hidden rounded-[1.25rem] bg-slate-100" style="height: 250px;">
                                <img
                                    src="<?php echo esc_url($blog['image']); ?>"
                                    alt="<?php echo esc_attr($blog['image_alt']); ?>"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                    loading="lazy"
                                >

                                <?php if (! empty($blog['term'])): ?>
                                    <span class="absolute left-5 top-5 inline-flex min-h-10 items-center gap-2 rounded-full bg-dark-main px-4 py-2 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(15,23,42,0.12)]">
                                        <?php if (! empty($blog['term']['icon'])): ?>
                                            <img
                                                src="<?php echo esc_url($blog['term']['icon']); ?>"
                                                alt=""
                                                class="h-4 w-4 object-contain"
                                                aria-hidden="true"
                                            >
                                        <?php endif; ?>
                                        <span><?php echo esc_html($blog['term']['name']); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h2 class="mt-4 truncate text-xl font-bold leading-tight text-slate-900 transition group-hover:text-orange-accent">
                                <?php echo esc_html($blog['title']); ?>
                            </h2>
                        </a>

                        <?php if (! empty($blog['tags'])): ?>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <?php foreach ($blog['tags'] as $tag): ?>
                                    <span class="inline-flex rounded-full bg-surface-200 px-4 py-2 text-base font-medium leading-none text-slate-900">
                                        <?php echo esc_html($tag); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="mx-auto mt-12 max-w-xl rounded-[1.25rem] border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">
                Er zijn nog geen blogposts gepubliceerd.
            </div>
        <?php endif; ?>

        <?php if ($total_pages > 1): ?>
            <nav class="mt-16 flex justify-center lg:mt-20" aria-label="Blog paginering">
                <div class="inline-flex min-h-12 items-center gap-3 rounded-full bg-white px-4 py-2 text-base font-semibold text-slate-400">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo esc_url($pagination_url($current_page - 1)); ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-900 transition hover:bg-[#FCF8F3]" aria-label="Vorige pagina">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg>
                        </a>
                    <?php else: ?>
                        <span class="inline-flex h-8 w-8 items-center justify-center text-slate-300" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg>
                        </span>
                    <?php endif; ?>

                    <?php
                    $page_links = [];

                    if ($total_pages <= 5) {
                        $page_links = range(1, $total_pages);
                    } elseif ($current_page <= 3) {
                        $page_links = [1, 2, 3, 'ellipsis', $total_pages];
                    } elseif ($current_page >= $total_pages - 2) {
                        $page_links = [1, 'ellipsis', $total_pages - 2, $total_pages - 1, $total_pages];
                    } else {
                        $page_links = [1, 'ellipsis', $current_page - 1, $current_page, $current_page + 1, 'ellipsis', $total_pages];
                    }
                    ?>

                    <?php foreach ($page_links as $page_link): ?>
                        <?php if ($page_link === 'ellipsis'): ?>
                            <span aria-hidden="true">...</span>
                            <?php continue; ?>
                        <?php endif; ?>

                        <?php if ((int) $page_link === $current_page): ?>
                            <span class="text-slate-900" aria-current="page"><?php echo esc_html((string) $page_link); ?></span>
                        <?php else: ?>
                            <a href="<?php echo esc_url($pagination_url($page_link)); ?>" class="transition hover:text-slate-900">
                                <?php echo esc_html((string) $page_link); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo esc_url($pagination_url($current_page + 1)); ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-900 transition hover:bg-[#FCF8F3]" aria-label="Volgende pagina">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                <path d="m9 6 6 6-6 6"></path>
                            </svg>
                        </a>
                    <?php else: ?>
                        <span class="inline-flex h-8 w-8 items-center justify-center text-slate-300" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                <path d="m9 6 6 6-6 6"></path>
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
    </div>
</section>
