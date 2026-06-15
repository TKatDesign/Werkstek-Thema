<?php
function werkstek_single_image_from_field($image, $size = 'large') {
    if (is_numeric($image)) {
        return [
            'url' => wp_get_attachment_image_url((int) $image, $size) ?: '',
            'alt' => get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: '',
        ];
    }

    if (is_array($image)) {
        $id = isset($image['ID']) ? (int) $image['ID'] : (isset($image['id']) ? (int) $image['id'] : 0);
        $url = $image['sizes'][$size] ?? $image['url'] ?? ($id ? wp_get_attachment_image_url($id, $size) : '');

        return [
            'url' => $url,
            'alt' => $image['alt'] ?? ($id ? get_post_meta($id, '_wp_attachment_image_alt', true) : ''),
        ];
    }

    if (is_string($image) && trim($image) !== '') {
        return [
            'url' => trim($image),
            'alt' => '',
        ];
    }

    return [
        'url' => '',
        'alt' => '',
    ];
}

function werkstek_single_get_gallery_images($post_id, $featured_url, $title) {
    $images = [];

    if ($featured_url) {
        $images[] = [
            'url' => $featured_url,
            'alt' => get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: $title,
        ];
    }

    $gallery = function_exists('get_field') ? get_field('galerij', $post_id) : get_post_meta($post_id, 'galerij', true);

    if (! empty($gallery)) {
        if (! is_array($gallery)) {
            $gallery = [$gallery];
        }

        foreach ($gallery as $gallery_image) {
            $image = werkstek_single_image_from_field($gallery_image, 'large');

            if (! $image['url']) {
                continue;
            }

            $images[] = [
                'url' => $image['url'],
                'alt' => $image['alt'] ?: $title,
            ];
        }
    }

    $unique_images = [];

    foreach ($images as $image) {
        if (isset($unique_images[$image['url']])) {
            continue;
        }

        $unique_images[$image['url']] = $image;
    }

    return array_values($unique_images);
}

function werkstek_single_get_file_url($post_id, $field_name) {
    $file = function_exists('get_field') ? get_field($field_name, $post_id) : get_post_meta($post_id, $field_name, true);

    if (is_numeric($file)) {
        return wp_get_attachment_url((int) $file) ?: '';
    }

    if (is_array($file)) {
        return $file['url'] ?? '';
    }

    return is_string($file) ? trim($file) : '';
}

function werkstek_single_related_kantoorruimtes($post_id, $term_id) {
    if (! $term_id) {
        return [];
    }

    $related_query = new WP_Query([
        'post_type' => 'kantoorruimte',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'post__not_in' => [$post_id],
        'orderby' => [
            'menu_order' => 'ASC',
            'date' => 'DESC',
        ],
        'tax_query' => [
            [
                'taxonomy' => 'locatie',
                'field' => 'term_id',
                'terms' => $term_id,
            ],
        ],
    ]);

    $items = [];

    if ($related_query->have_posts()) {
        while ($related_query->have_posts()) {
            $related_query->the_post();
            $items[] = werkstek_get_kantoorruimte_card_data(get_the_ID());
        }
    }

    wp_reset_postdata();

    return $items;
}
?>

<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $ruimte = werkstek_get_kantoorruimte_card_data($post_id);
        $title = get_the_title();
        $gallery_images = werkstek_single_get_gallery_images($post_id, $ruimte['image'], $title);
        $visible_images = array_slice($gallery_images, 0, 5);
        $facilities = $ruimte['facilities'] ?? [];
        $plattegrond_url = werkstek_single_get_file_url($post_id, 'plattegrond');
        $terms = get_the_terms($post_id, 'locatie');
        $primary_term = (! empty($terms) && ! is_wp_error($terms)) ? array_shift($terms) : null;
        $primary_term_url = '';

        if ($primary_term instanceof WP_Term) {
            $term_link = get_term_link($primary_term);
            $primary_term_url = is_wp_error($term_link) ? '' : $term_link;
        }

        $related_items = werkstek_single_related_kantoorruimtes($post_id, $primary_term instanceof WP_Term ? $primary_term->term_id : 0);
        $related_title = $primary_term instanceof WP_Term ? 'Meer in ' . $primary_term->name : 'Meer kantoorruimtes';
        $related_count = count($related_items);
        $related_slider_is_active = $related_count > 3;
        $archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
        $vastgoedconsultant = function_exists('werkstek_get_selected_vastgoedconsultant') ? werkstek_get_selected_vastgoedconsultant($post_id) : null;
        $rondleiding_status = isset($_GET['rondleiding']) ? sanitize_key($_GET['rondleiding']) : '';
        ?>

        <main class="bg-white">
            <section class="pt-7 pb-14 lg:pt-10 lg:pb-20">
                <div class="mx-auto max-w-7xl px-5 sm:px-6">
                    <nav class="flex items-center gap-3 text-sm font-medium text-slate-300" aria-label="Breadcrumb">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="transition hover:text-slate-500" aria-label="Home">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2">
                                <path d="m3 10 9-7 9 7"></path>
                                <path d="M5 10v10h14V10"></path>
                            </svg>
                        </a>
                        <span>/</span>
                        <a href="<?php echo esc_url($archive_url); ?>" class="transition hover:text-slate-500">Kantoorruimte huren</a>
                        <?php if ($primary_term instanceof WP_Term && $primary_term_url): ?>
                            <span>/</span>
                            <a href="<?php echo esc_url($primary_term_url); ?>" class="transition hover:text-slate-500"><?php echo esc_html($primary_term->name); ?></a>
                        <?php endif; ?>
                    </nav>

                    <span class="mt-8 inline-flex rounded-full bg-green-accent px-4 py-1.5 text-sm font-normal text-white">
                      Vanaf €<?php echo esc_html($ruimte['price']); ?>
                    </span>
                    <h1 class="mt-1 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl lg:text-[2.65rem]"> 
                    <?php echo esc_html($title); ?>
                    </h1>

                    <?php if (! empty($visible_images)): ?>
                        <div class="mt-8 grid gap-5 <?php echo count($visible_images) > 1 ? 'lg:grid-cols-[1.05fr_1fr]' : ''; ?>">
                            <button type="button" data-gallery-open class="group relative h-[320px] overflow-hidden rounded-2xl bg-slate-100 lg:h-[450px]" aria-label="Open alle foto's">
                                <img src="<?php echo esc_url($visible_images[0]['url']); ?>" alt="<?php echo esc_attr($visible_images[0]['alt']); ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            </button>

                            <?php if (count($visible_images) > 1): ?>
                                <div class="grid grid-cols-2 gap-5">
                                    <?php foreach (array_slice($visible_images, 1, 4) as $image_index => $image): ?>
                                        <button type="button" data-gallery-open class="group relative h-[150px] overflow-hidden rounded-2xl bg-slate-100 lg:h-[215px]" aria-label="Open alle foto's">
                                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

                                            <?php if ($image_index === min(count($visible_images) - 2, 3) && count($gallery_images) > 1): ?>
                                                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/35">
                                                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-slate-800 shadow-[0_16px_35px_rgba(15,23,42,0.18)]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2 text-slate-500">
                                                            <path d="M14.5 4h-5L8 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-3l-1.5-2Z"></path>
                                                            <circle cx="12" cy="13" r="3"></circle>
                                                        </svg>
                                                        <span>Alle foto's</span>
                                                    </span>
                                                </span>
                                            <?php endif; ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-12 grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start xl:grid-cols-[minmax(0,41rem)_21rem] xl:justify-center xl:gap-24">
                        <div>
                            <?php if (! empty($facilities)): ?>
                                <section>
                                    <h2 class="text-2xl font-black text-slate-900">Pluspunten</h2>
                                    <div class="mt-5 flex flex-wrap gap-3">
                                        <?php foreach ($facilities as $facility): ?>
                                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-900">
                                                <img src="<?php echo esc_url($facility['icon']); ?>" alt="" class="h-5 w-5" loading="lazy">
                                                <span><?php echo esc_html($facility['label']); ?></span>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endif; ?>

                            <div class="<?php echo ! empty($facilities) ? 'mt-10 border-t border-slate-200 pt-10' : ''; ?> kantoorruimte-content max-w-none">
                                <?php the_content(); ?>
                            </div>

                            <?php if ($plattegrond_url): ?>
                                <div class="mt-9">
                                    <a href="<?php echo esc_url($plattegrond_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white px-5 py-3 text-base font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2 text-slate-500">
                                            <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3V6Z"></path>
                                            <path d="M9 3v15"></path>
                                            <path d="M15 6v15"></path>
                                        </svg>
                                        <span>Bekijk plattegrond</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <aside class="lg:sticky lg:top-28">
                            <div class="rounded-2xl border border-slate-200 bg-white p-10 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
                                <h2 class="text-3xl font-bold text-slate-900">Interesse?</h2>
                                <p class="mt-2 text-base font-medium text-slate-900">We staan voor je klaar!</p>

                                <ul class="mt-7 space-y-2 text-sm font-medium text-slate-900">
                                    <li class="flex items-center gap-3">
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full text-lime-500">
                                            <img src="<?php echo '/wp-content/themes/werkstek-thema/resources/images/kantoorruimte/solar_check-circle-outline.svg'; ?>" class="h-5 w-5 object-contain" loading="lazy">
                                        </span>
                                        <span>Gratis & vrijblijvend</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full text-lime-500">
                                            <img src="<?php echo '/wp-content/themes/werkstek-thema/resources/images/kantoorruimte/solar_check-circle-outline.svg'; ?>" class="h-5 w-5 object-contain" loading="lazy">
                                        </span>
                                        <span>Een aanbod binnen een uur</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full text-lime-500">
                                            <img src="<?php echo '/wp-content/themes/werkstek-thema/resources/images/kantoorruimte/solar_check-circle-outline.svg'; ?>" class="h-5 w-5 object-contain" loading="lazy">
                                        </span>
                                        <span>Persoonlijke service</span>
                                    </li>
                                </ul>

                                <?php if ($rondleiding_status === 'success'): ?>
                                    <p class="mt-6 rounded-2xl bg-lime-50 px-4 py-3 text-sm font-medium text-lime-700">
                                        Bedankt, je aanvraag is verstuurd.
                                    </p>
                                <?php elseif ($rondleiding_status === 'error'): ?>
                                    <p class="mt-6 rounded-2xl bg-orange-100 px-4 py-3 text-sm font-medium text-orange-600">
                                        Vul alle verplichte velden correct in.
                                    </p>
                                <?php endif; ?>

                                <button type="button" data-tour-open class="mt-7 inline-flex w-full items-center justify-center rounded-full bg-orange-500 px-6 py-3 text-base font-medium text-white transition hover:bg-orange-600">
                                    Rondleiding aanvragen
                                </button>
                            </div>

                            <?php if (! empty($vastgoedconsultant)): ?>
                                <div class="mt-12 flex items-center gap-5">
                                    <?php if (! empty($vastgoedconsultant['image'])): ?>
                                        <img src="<?php echo esc_url($vastgoedconsultant['image']); ?>" alt="<?php echo esc_attr($vastgoedconsultant['image_alt']); ?>" class="h-20 w-20 shrink-0 rounded-full object-cover" loading="lazy">
                                    <?php endif; ?>

                                    <div class="min-w-0">
                                        <?php if (! empty($vastgoedconsultant['name'])): ?>
                                            <p class="text-lg font-bold leading-tight text-slate-900"><?php echo esc_html($vastgoedconsultant['name']); ?></p>
                                        <?php endif; ?>

                                        <p class="mt-1 text-sm font-medium text-slate-900">Vastgoedconsultant</p>

                                        <?php if (! empty($vastgoedconsultant['phone'])): ?>
                                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $vastgoedconsultant['phone'])); ?>" class="mt-3 inline-flex text-sm font-medium text-slate-900 underline underline-offset-2 transition hover:text-orange-500">
                                                <?php echo esc_html($vastgoedconsultant['phone']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </aside>
                    </div>
                </div>
            </section>

            <?php if (! empty($related_items)): ?>
                <section class="pb-16 lg:pb-28">
                    <div class="mx-auto max-w-7xl px-5 sm:px-6">
                        <h2 class="text-center text-4xl font-bold text-slate-900 sm:text-5xl">
                            <?php echo esc_html($related_title); ?>
                        </h2>

                        <div class="mt-10 lg:mt-14">
                            <div class="relative">
                                <?php if ($related_slider_is_active): ?>
                                    <button type="button" data-slider-prev="related-kantoorruimtes-slider" aria-label="Vorige kantoorruimte" class="hidden lg:inline-flex absolute left-0 top-[9.75rem] z-10 h-12 w-12 -translate-x-1/2 items-center justify-center rounded-full bg-slate-900 text-white shadow-[0_16px_30px_rgba(15,23,42,0.16)] transition hover:bg-slate-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                            <path d="m15 18-6-6 6-6"></path>
                                        </svg>
                                    </button>
                                <?php endif; ?>

                                <div
                                    id="related-kantoorruimtes-slider"
                                    <?php echo $related_slider_is_active ? 'data-slider' : ''; ?>
                                    class="no-scrollbar flex gap-5 pb-2 lg:gap-6 <?php echo $related_slider_is_active ? 'snap-x snap-mandatory overflow-x-auto scroll-smooth' : 'flex-col overflow-visible sm:flex-row lg:justify-center'; ?>"
                                >
                                    <?php foreach ($related_items as $related): ?>
                                        <article class="<?php echo $related_slider_is_active ? 'min-w-[82%] snap-start sm:min-w-[420px]' : 'w-full sm:w-[calc((100%_-_1.25rem)_/_2)]'; ?> lg:min-w-[calc((100%_-_3rem)_/_3)] lg:max-w-[calc((100%_-_3rem)_/_3)] lg:flex-none">
                                            <a href="<?php echo esc_url($related['url']); ?>" class="group block">
                                                <div class="relative min-h-[250px] overflow-hidden rounded-[1.75rem] bg-slate-200 shadow-[0_20px_45px_rgba(15,23,42,0.08)] transition duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_28px_60px_rgba(15,23,42,0.14)] lg:min-h-[255px]" style="background-image: linear-gradient(to top, rgba(15, 23, 42, 0.1), rgba(15, 23, 42, 0.02)), url('<?php echo esc_url($related['image']); ?>'); background-size: cover; <?php echo esc_attr($related['position']); ?>">
                                                    <?php if ($related['is_new']): ?>
                                                        <span class="absolute left-4 top-4 inline-flex rounded-full bg-yellow-300 px-4 py-1.5 text-sm font-semibold text-slate-900">Nieuw</span>
                                                    <?php endif; ?>

                                                    <span class="absolute bottom-4 right-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm transition group-hover:scale-105 group-hover:text-orange-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                                            <path d="M7 17 17 7"></path>
                                                            <path d="M9 7h8v8"></path>
                                                        </svg>
                                                    </span>
                                                </div>

                                                <div class="pt-4">
                                                    <div class="flex items-center gap-2 text-base font-medium text-slate-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-none stroke-current stroke-2 text-slate-400">
                                                            <path d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"></path>
                                                            <circle cx="12" cy="11" r="2.5"></circle>
                                                        </svg>
                                                        <span><?php echo esc_html($related['title']); ?></span>
                                                    </div>

                                                    <?php if ($related['price'] || $related['availability']): ?>
                                                        <div class="mt-4 flex flex-wrap items-center gap-3">
                                                            <?php if ($related['price']): ?>
                                                                <span class="inline-flex rounded-full bg-lime-500 px-4 py-1.5 text-sm font-semibold text-white"><?php echo esc_html($related['price']); ?></span>
                                                            <?php endif; ?>

                                                            <?php if ($related['availability']): ?>
                                                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-1.5 text-sm font-medium text-slate-600">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2 text-slate-400">
                                                                        <rect x="3.5" y="5.5" width="17" height="15" rx="2"></rect>
                                                                        <path d="M7 3.5v4"></path>
                                                                        <path d="M17 3.5v4"></path>
                                                                        <path d="M3.5 9.5h17"></path>
                                                                    </svg>
                                                                    <span><?php echo esc_html($related['availability']); ?></span>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        </article>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($related_slider_is_active): ?>
                                    <button type="button" data-slider-next="related-kantoorruimtes-slider" aria-label="Volgende kantoorruimte" class="hidden lg:inline-flex absolute right-0 top-[9.75rem] z-10 h-12 w-12 translate-x-1/2 items-center justify-center rounded-full bg-slate-900 text-white shadow-[0_16px_30px_rgba(15,23,42,0.16)] transition hover:bg-slate-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                            <path d="m9 6 6 6-6 6"></path>
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </main>

        <div data-tour-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-5 py-8" aria-hidden="true">
            <div class="relative w-full max-w-[34rem] rounded-2xl bg-white px-6 py-8 shadow-[0_24px_80px_rgba(15,23,42,0.24)] sm:px-10">
                <div class="flex items-start justify-between gap-5">
                    <h2 class="text-2xl font-bold text-slate-900">Rondleiding aanvragen</h2>
                    <button type="button" data-tour-close class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-slate-900 transition hover:bg-slate-100" aria-label="Sluit rondleiding formulier">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current stroke-2">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <form class="mt-8 space-y-7" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="werkstek_rondleiding_aanvraag">
                    <input type="hidden" name="kantoorruimte_id" value="<?php echo esc_attr($post_id); ?>">
                    <?php wp_nonce_field('werkstek_rondleiding_aanvraag_' . $post_id, 'werkstek_rondleiding_nonce'); ?>

                    <label class="block">
                        <span class="text-base font-medium text-slate-900">Naam <span class="text-orange-500">*</span></span>
                        <input type="text" name="naam" required autocomplete="name" class="mt-3 h-12 w-full rounded-lg border border-slate-200 bg-white px-5 text-base text-slate-900 shadow-sm outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                    </label>

                    <label class="block">
                        <span class="text-base font-medium text-slate-900">E-mailadres <span class="text-orange-500">*</span></span>
                        <input type="email" name="emailadres" required autocomplete="email" class="mt-3 h-12 w-full rounded-lg border border-slate-200 bg-white px-5 text-base text-slate-900 shadow-sm outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                    </label>

                    <label class="block">
                        <span class="text-base font-medium text-slate-900">Telefoonnummer <span class="text-orange-500">*</span></span>
                        <input type="tel" name="telefoonnummer" required autocomplete="tel" class="mt-3 h-12 w-full rounded-lg border border-slate-200 bg-white px-5 text-base text-slate-900 shadow-sm outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                    </label>

                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center rounded-full bg-orange-500 px-6 text-base font-medium text-white transition hover:bg-orange-600">
                        Rondleiding aanvragen
                    </button>
                </form>
            </div>
        </div>

        <?php if (! empty($gallery_images)): ?>
            <div data-gallery-modal class="gallery-modal fixed inset-0 z-50 hidden bg-white" aria-hidden="true">
                <div class="flex h-full flex-col">
                    <div class="flex min-h-16 items-center justify-between border-b border-slate-200 bg-white px-5 sm:px-8">
                        <div class="flex h-full items-center gap-6 text-sm font-medium text-slate-600">
                            <span class="inline-flex h-16 items-center gap-2 border-b-4 border-orange-500 font-bold text-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <rect x="3" y="3" width="14" height="14" rx="1"></rect>
                                    <path d="M7 7h14v14H7z"></path>
                                </svg>
                                <span>Foto's</span>
                            </span>
                        </div>

                        <button type="button" data-gallery-close class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-900 transition hover:bg-slate-100" aria-label="Sluit foto's">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current stroke-2">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="no-scrollbar md:grid flex-1 auto-rows-auto content-start grid-cols-1 gap-1 overflow-y-auto bg-white pb-1 md:grid-cols-6 max-lg:gap-4">
                        <?php foreach ($gallery_images as $image_index => $image): ?>
                            <?php $gallery_item_is_wide = $image_index % 5 >= 3; ?>
                            <figure class="relative overflow-hidden bg-slate-100 <?php echo $gallery_item_is_wide ? 'md:col-span-3' : 'md:col-span-2'; ?> max-lg:aspect-3/2 max-lg:mb-2">
                                <span class="block w-full" style="padding-top: 56.25%;" aria-hidden="true"></span>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="absolute inset-0 h-full w-full object-cover">
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
