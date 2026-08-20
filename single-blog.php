<?php get_header(); ?>

<?php
while (have_posts()) {
    the_post();

    $post_id = get_the_ID();
    $title = get_the_title();
    $blog_archive_url = get_post_type_archive_link('blog') ?: home_url('/blog/');
    $featured_image = get_the_post_thumbnail_url($post_id, 'large') ?: get_template_directory_uri() . '/resources/images/large.webp';
    $featured_image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: $title;
    $terms = get_the_terms($post_id, 'blog_categorie');
    $primary_term = (! empty($terms) && ! is_wp_error($terms)) ? array_values($terms)[0] : null;
    $term_url = $primary_term instanceof WP_Term ? get_term_link($primary_term) : '';
    $term_url = is_wp_error($term_url) ? '' : $term_url;
    $term_icon = $primary_term instanceof WP_Term && function_exists('werkstek_get_blog_term_icon_url')
        ? werkstek_get_blog_term_icon_url($primary_term)
        : '';
    $kantoorruimte_archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
    $cta_image = get_template_directory_uri() . '/resources/images/blog/cta_man.png';
    $cta_image_2 = get_template_directory_uri() . '/resources/images/blog/cta_2.png';
    $blog_image_path = get_template_directory_uri() . '/resources/images/blog/';
    $post_url = get_permalink($post_id);
    $encoded_post_url = rawurlencode($post_url);
    $encoded_title = rawurlencode($title);
    $encoded_featured_image = rawurlencode($featured_image);
    $share_links = [
        [
            'label' => 'Facebook',
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_post_url,
            'icon' => $blog_image_path . 'facebook.svg',
        ],
        [
            'label' => 'LinkedIn',
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_post_url . '&title=' . $encoded_title,
            'icon' => $blog_image_path . 'linkedin.svg',
        ],
        [
            'label' => 'X',
            'url' => 'https://twitter.com/intent/tweet?url=' . $encoded_post_url . '&text=' . $encoded_title,
            'icon' => $blog_image_path . 'twitter.svg',
        ],
        [
            'label' => 'Pinterest',
            'url' => 'https://pinterest.com/pin/create/button/?url=' . $encoded_post_url . '&media=' . $encoded_featured_image . '&description=' . $encoded_title,
            'icon' => $blog_image_path . 'pinterest.svg',
        ],
    ];
    ?>

    <nav class="px-5 py-4 sm:px-6 lg:px-12" aria-label="Breadcrumb">
        <div class="lg:px-10 mx-auto flex max-w-[1440px] items-center gap-3 overflow-hidden text-xs font-medium text-slate-400">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-slate-700 transition hover:text-orange-accent">
                <span class="sr-only">Home</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2 stroke-text-dark">
                    <path d="m3 11 9-8 9 8"></path>
                    <path d="M5 10v10h14V10"></path>
                    <path d="M9 20v-6h6v6"></path>
                </svg>
            </a>
            <span class="shrink-0" aria-hidden="true">&rsaquo;</span>
            <a href="<?php echo esc_url($blog_archive_url); ?>" class="shrink-0 text-text-dark transition hover:text-orange-accent">Blogs</a>
            <span class="shrink-0" aria-hidden="true">&rsaquo;</span>
            <span class="min-w-0 flex-1 truncate text-text-dark">
                <?php echo esc_html($title); ?>
            </span>
        </div>
    </nav>

    <main>
        <section class="bg-surface pb-0 pt-8 lg:pt-24">
            <div class="mx-auto max-w-4xl px-5 sm:px-6">
                <?php if ($primary_term instanceof WP_Term): ?>
                    <?php if ($term_url): ?>
                        <a href="<?php echo esc_url($term_url); ?>" class="inline-flex min-h-8 items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold text-slate-900 transition hover:-translate-y-0.5 bg-surface-200">
                    <?php else: ?>
                        <span class="inline-flex min-h-8 items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold text-slate-900 bg-surface-200">
                    <?php endif; ?>
                            <?php if ($term_icon): ?>
                                <img src="<?php echo esc_url($term_icon); ?>" alt="" class="h-4 w-4 object-contain" aria-hidden="true">
                            <?php endif; ?>
                            <span><?php echo esc_html($primary_term->name); ?></span>
                    <?php if ($term_url): ?>
                        </a>
                    <?php else: ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>

                <h1 class="mt-4 max-w-3xl text-2xl font-bold text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                    <?php echo esc_html($title); ?>
                </h1>

                <div class="mt-10 h-[250px] max-h-[250px] overflow-hidden rounded-[1.5rem] bg-slate-100 sm:h-[450px] sm:max-h-none lg:mt-14">
                    <img
                        src="<?php echo esc_url($featured_image); ?>"
                        alt="<?php echo esc_attr($featured_image_alt); ?>"
                        class="h-full w-full object-cover"
                        loading="eager"
                    >
                </div>
            </div>
        </section>

        <?php
        $blog_components = function_exists('get_field') ? get_field('componenten', $post_id) : get_post_meta($post_id, 'componenten', true);

        if (empty($blog_components) && function_exists('get_field')) {
            $blog_components = get_field('Componenten', $post_id);
        }

        if (! is_array($blog_components)) {
            $blog_components = [];
        }

        $related_blogs = [];
        $related_query = new WP_Query([
            'post_type' => 'blog',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'orderby' => 'rand',
            'no_found_rows' => true,
        ]);

        if ($related_query->have_posts() && function_exists('werkstek_get_blog_card_data')) {
            while ($related_query->have_posts()) {
                $related_query->the_post();
                $related_blogs[] = werkstek_get_blog_card_data(get_the_ID());
            }
        }

        wp_reset_postdata();
        ?>

        <?php if (! empty($blog_components)): ?>
            <section class="pb-16 lg:pb-4">
                <div class="mx-auto max-w-4xl px-5 sm:px-6">
                    <?php foreach ($blog_components as $component): ?>
                        <?php
                        if (! is_array($component)) {
                            continue;
                        }

                        $layout = $component['acf_fc_layout'] ?? '';
                        $normalized_layout = str_replace('-', '_', strtolower((string) $layout));
                        ?>

                        <?php if (in_array($normalized_layout, ['content', 'alinea'], true)): ?>
                            <?php
                            $paragraph = $component['alinea']
                                ?? $component['Alinea']
                                ?? $component['content']
                                ?? $component['Content']
                                ?? [];

                            $paragraph_title = '';
                            $paragraph_text = '';

                            if (is_array($paragraph)) {
                                $paragraph_title = trim((string) ($paragraph['titel'] ?? $paragraph['Titel'] ?? ''));
                                $paragraph_text = trim((string) ($paragraph['tekst'] ?? $paragraph['Tekst'] ?? ''));
                            }

                            $paragraph_title = $paragraph_title ?: trim((string) (
                                $component['titel']
                                ?? $component['Titel']
                                ?? $component['alinea_titel']
                                ?? $component['content_titel']
                                ?? ''
                            ));
                            $paragraph_text = $paragraph_text ?: trim((string) (
                                $component['tekst']
                                ?? $component['Tekst']
                                ?? $component['alinea_tekst']
                                ?? $component['content_tekst']
                                ?? ''
                            ));

                            if ($paragraph_title === '' && $paragraph_text === '') {
                                continue;
                            }
                            ?>

                            <div class="mx-auto max-w-3xl py-12 text-slate-900">
                                <?php if ($paragraph_title): ?>
                                    <h2 class="text-2xl font-bold leading-tight text-slate-900 sm:text-4xl">
                                        <?php echo esc_html($paragraph_title); ?>
                                    </h2>
                                <?php endif; ?>

                                <?php if ($paragraph_text): ?>
                                    <div class="<?php echo $paragraph_title ? 'mt-5' : ''; ?> max-w-none text-sm lg:text-lg lg:leading-9 font-medium leading-7 text-slate-900">
                                        <?php echo wp_kses_post(wpautop($paragraph_text)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($normalized_layout, ['cta_blok', 'cta_block'], true)): ?>
                            <div class="my-2 overflow-hidden rounded-[1.25rem] lg:my-14 bg-surface-200">
                                <div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-10">
                                    <div class="px-8 py-8 sm:px-12 lg:px-14">
                                        <h2 class="max-w-md text-2xl font-bold leading-[1.05] text-slate-900 sm:text-[40px]">
                                            Jouw eigen kantoorruimte?
                                        </h2>

                                        <ul class="mt-6 space-y-3">
                                            <li class="flex items-center gap-3 lg:text-lg text-md font-medium text-slate-900">
                                                <span class="inline-flex h-4 w-4 lg:h-5 lg:w-5 shrink-0 items-center justify-center rounded-full border border-green-accent text-green-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.5]">
                                                        <path d="m6 12 4 4 8-8"></path>
                                                    </svg>
                                                </span>
                                                <span>Binnen no-time geregeld</span>
                                            </li>
                                            <li class="flex items-center gap-3 lg:text-lg text-md font-medium text-slate-900">
                                                <span class="inline-flex h-4 w-4 lg:h-5 lg:w-5 shrink-0 items-center justify-center rounded-full border border-green-accent text-green-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.5]">
                                                        <path d="m6 12 4 4 8-8"></path>
                                                    </svg>
                                                </span>
                                                <span>In heel Nederland</span>
                                            </li>
                                        </ul>

                                        <a href="<?php echo esc_url($kantoorruimte_archive_url); ?>" class="mt-7 inline-flex items-center gap-4 rounded-full bg-slate-900 py-2 pl-6 pr-2 text-base font-medium text-white transition hover:bg-slate-800">
                                            <span>Vind kantoorruimte</span>
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-orange-accent text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>

                                    <div class="hidden justify-center lg:flex lg:justify-end">
                                        <img
                                            src="<?php echo esc_url($cta_image); ?>"
                                            alt=""
                                            class="max-h-[360px] w-auto object-contain"
                                            loading="lazy"
                                            aria-hidden="true"
                                        >
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($normalized_layout, ['cta_blok_2', 'cta_block_2'], true)): ?>
                            <div class="overflow-hidden rounded-[1.25rem] lg:my-14 bg-surface-200">
                                <div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-10">
                                    <div class="px-8 py-8 sm:px-12 lg:px-14">
                                        <h2 class="max-w-md text-2xl lg:text-[40px] font-bold leading-[1.05] text-slate-900 sm:text-[40px]">
                                            Een uitgebreid netwerk?
                                        </h2>

                                        <ul class="mt-6 space-y-3">
                                            <li class="flex items-center gap-3 lg:text-lg text-md font-medium text-slate-900">
                                                <span class="inline-flex h-4 w-4 lg:h-5 lg:w-5 shrink-0 items-center justify-center rounded-full border border-green-accent text-green-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.5]">
                                                        <path d="m6 12 4 4 8-8"></path>
                                                    </svg>
                                                </span>
                                                <span>Events &amp; bijeenkomsten</span>
                                            </li>
                                            <li class="flex items-center gap-3 lg:text-lg text-md font-medium text-slate-900">
                                                <span class="inline-flex h-4 w-4 lg:h-5 lg:w-5 shrink-0 items-center justify-center rounded-full border border-green-accent text-green-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.5]">
                                                        <path d="m6 12 4 4 8-8"></path>
                                                    </svg>
                                                </span>
                                                <span>Diverse ondernemers</span>
                                            </li>
                                        </ul>

                                        <a href="<?php echo esc_url($kantoorruimte_archive_url); ?>" class="mt-7 inline-flex items-center gap-4 rounded-full bg-slate-900 py-2 pl-6 pr-2 text-base font-medium text-white transition hover:bg-slate-800">
                                            <span>Vind kantoorruimte</span>
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-orange-accent text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>

                                    <div class="hidden justify-center lg:flex lg:justify-end">
                                        <img
                                            src="<?php echo esc_url($cta_image_2); ?>"
                                            alt=""
                                            class="max-h-[360px] w-auto object-contain"
                                            loading="lazy"
                                            aria-hidden="true"
                                        >
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="">
            <div class="mx-auto max-w-3xl px-5 sm:px-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="mr-1 text-lg font-bold text-slate-900">Delen</span>

                    <?php foreach ($share_links as $share_link): ?>
                        <a
                            href="<?php echo esc_url($share_link['url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-200 text-slate-900 transition hover:bg-white"
                            aria-label="<?php echo esc_attr($share_link['label']); ?> delen"
                        >
                            <img
                                src="<?php echo esc_url($share_link['icon']); ?>"
                                alt=""
                                class="h-5 w-5 object-contain"
                                loading="lazy"
                                aria-hidden="true"
                            >
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>

            <?php if (! empty($related_blogs)): ?>
                <div class="mx-auto mt-16 max-w-7xl px-5 sm:px-6 lg:mt-20">
                    <h2 class="text-center text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">
                        Meer blogs
                    </h2>

                    <div class="mt-12 grid gap-x-8 gap-y-12 md:grid-cols-2 lg:grid-cols-3 lg:gap-x-10">
                        <?php foreach ($related_blogs as $blog): ?>
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

                                    <h3 class="mt-4 truncate text-xl font-bold leading-tight text-slate-900 transition group-hover:text-orange-accent">
                                        <?php echo esc_html($blog['title']); ?>
                                    </h3>
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
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php
}
?>

<?php get_footer(); ?>
