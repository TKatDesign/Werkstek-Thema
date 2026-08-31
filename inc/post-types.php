<?php
/**
 * Custom post types and related helpers.
 */

function werkstek_theme_setup() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'werkstek_theme_setup');

function werkstek_register_post_types() {
    $labels = [
        'name' => 'Kantoorruimtes',
        'singular_name' => 'Kantoorruimte',
        'menu_name' => 'Kantoorruimtes',
        'name_admin_bar' => 'Kantoorruimte',
        'add_new' => 'Nieuwe toevoegen',
        'add_new_item' => 'Nieuwe kantoorruimte toevoegen',
        'new_item' => 'Nieuwe kantoorruimte',
        'edit_item' => 'Kantoorruimte bewerken',
        'view_item' => 'Kantoorruimte bekijken',
        'all_items' => 'Alle kantoorruimtes',
        'search_items' => 'Zoek kantoorruimtes',
        'not_found' => 'Geen kantoorruimtes gevonden.',
        'not_found_in_trash' => 'Geen kantoorruimtes gevonden in de prullenbak.',
        'archives' => 'Kantoorruimte archief',
    ];

    register_post_type('kantoorruimte', [
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive' => 'kantoorruimte-huren',
        'rewrite' => [
            'slug' => 'kantoorruimte-huren/%locatie%',
            'with_front' => false,
        ],
    ]);

    $taxonomy_labels = [
        'name' => 'Locaties',
        'singular_name' => 'Locatie',
        'search_items' => 'Zoek locaties',
        'all_items' => 'Alle locaties',
        'edit_item' => 'Locatie bewerken',
        'update_item' => 'Locatie bijwerken',
        'add_new_item' => 'Nieuwe locatie toevoegen',
        'new_item_name' => 'Nieuwe locatienaam',
        'menu_name' => 'Locaties',
    ];

    register_taxonomy('locatie', ['kantoorruimte'], [
        'labels' => $taxonomy_labels,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'kantoorruimte-huren',
            'with_front' => false,
        ],
    ]);

    $blog_labels = [
        'name' => 'Blogs',
        'singular_name' => 'Blog',
        'menu_name' => 'Blog',
        'name_admin_bar' => 'Blog',
        'add_new' => 'Nieuwe toevoegen',
        'add_new_item' => 'Nieuwe blog toevoegen',
        'new_item' => 'Nieuwe blog',
        'edit_item' => 'Blog bewerken',
        'view_item' => 'Blog bekijken',
        'all_items' => 'Alle blogs',
        'search_items' => 'Zoek blogs',
        'not_found' => 'Geen blogs gevonden.',
        'not_found_in_trash' => 'Geen blogs gevonden in de prullenbak.',
        'archives' => 'Blog archief',
    ];

    register_post_type('blog', [
        'labels' => $blog_labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive' => 'blog',
        'rewrite' => [
            'slug' => 'blog',
            'with_front' => false,
        ],
    ]);

    $blog_taxonomy_labels = [
        'name' => 'Blog categorieen',
        'singular_name' => 'Blog categorie',
        'search_items' => 'Zoek blog categorieen',
        'all_items' => 'Alle blog categorieen',
        'edit_item' => 'Blog categorie bewerken',
        'update_item' => 'Blog categorie bijwerken',
        'add_new_item' => 'Nieuwe blog categorie toevoegen',
        'new_item_name' => 'Nieuwe blog categorienaam',
        'menu_name' => 'Categorieen',
    ];

    register_taxonomy('blog_categorie', ['blog'], [
        'labels' => $blog_taxonomy_labels,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'blog-categorie',
            'with_front' => false,
        ],
    ]);

    $review_labels = [
        'name' => 'Reviews',
        'singular_name' => 'Review',
        'menu_name' => 'Reviews',
        'name_admin_bar' => 'Review',
        'add_new' => 'Nieuwe toevoegen',
        'add_new_item' => 'Nieuwe review toevoegen',
        'new_item' => 'Nieuwe review',
        'edit_item' => 'Review bewerken',
        'view_item' => 'Review bekijken',
        'all_items' => 'Alle reviews',
        'search_items' => 'Zoek reviews',
        'not_found' => 'Geen reviews gevonden.',
        'not_found_in_trash' => 'Geen reviews gevonden in de prullenbak.',
    ];

    register_post_type('review', [
        'labels' => $review_labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-status',
        'supports' => ['title', 'thumbnail', 'revisions'],
        'has_archive' => false,
        'rewrite' => [
            'slug' => 'reviews',
            'with_front' => false,
        ],
    ]);

    $testimonial_labels = [
        'name' => 'Testimonials',
        'singular_name' => 'Testimonial',
        'menu_name' => 'Testimonials',
        'name_admin_bar' => 'Testimonial',
        'add_new' => 'Nieuwe toevoegen',
        'add_new_item' => 'Nieuwe testimonial toevoegen',
        'new_item' => 'Nieuwe testimonial',
        'edit_item' => 'Testimonial bewerken',
        'view_item' => 'Testimonial bekijken',
        'all_items' => 'Alle testimonials',
        'search_items' => 'Zoek testimonials',
        'not_found' => 'Geen testimonials gevonden.',
        'not_found_in_trash' => 'Geen testimonials gevonden in de prullenbak.',
    ];

    register_post_type('testimonial', [
        'labels' => $testimonial_labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'has_archive' => false,
        'rewrite' => [
            'slug' => 'testimonials',
            'with_front' => false,
        ],
    ]);

    $community_video_labels = [
        'name' => 'Community videos',
        'singular_name' => 'Community video',
        'menu_name' => 'Community videos',
        'name_admin_bar' => 'Community video',
        'add_new' => 'Nieuwe toevoegen',
        'add_new_item' => 'Nieuwe community video toevoegen',
        'new_item' => 'Nieuwe community video',
        'edit_item' => 'Community video bewerken',
        'view_item' => 'Community video bekijken',
        'all_items' => 'Alle community videos',
        'search_items' => 'Zoek community videos',
        'not_found' => 'Geen community videos gevonden.',
        'not_found_in_trash' => 'Geen community videos gevonden in de prullenbak.',
    ];

    register_post_type('community_video', [
        'labels' => $community_video_labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-video-alt3',
        'supports' => ['title', 'thumbnail', 'revisions'],
        'has_archive' => false,
        'rewrite' => [
            'slug' => 'community-videos',
            'with_front' => false,
        ],
    ]);

    $rondleiding_labels = [
        'name' => 'Rondleiding aanvragen',
        'singular_name' => 'Rondleiding aanvraag',
        'menu_name' => 'Rondleiding aanvragen',
        'name_admin_bar' => 'Rondleiding aanvraag',
        'edit_item' => 'Rondleiding aanvraag bekijken',
        'view_item' => 'Rondleiding aanvraag bekijken',
        'all_items' => 'Alle rondleiding aanvragen',
        'search_items' => 'Zoek rondleiding aanvragen',
        'not_found' => 'Geen rondleiding aanvragen gevonden.',
        'not_found_in_trash' => 'Geen rondleiding aanvragen gevonden in de prullenbak.',
    ];

    register_post_type('rondleiding_aanvraag', [
        'labels' => $rondleiding_labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-calendar-alt',
        'capability_type' => 'post',
        'supports' => ['title', 'custom-fields'],
        'has_archive' => false,
        'rewrite' => false,
    ]);
}
add_action('init', 'werkstek_register_post_types');

function werkstek_flush_rewrite_rules_on_switch() {
    werkstek_register_post_types();
    flush_rewrite_rules();
    update_option('werkstek_theme_rewrite_version', '5');
}
add_action('after_switch_theme', 'werkstek_flush_rewrite_rules_on_switch');

function werkstek_maybe_flush_kantoorruimte_rewrite_rules() {
    if (get_option('werkstek_theme_rewrite_version') === '5') {
        return;
    }

    werkstek_register_post_types();
    flush_rewrite_rules();
    update_option('werkstek_theme_rewrite_version', '5');
}
add_action('admin_init', 'werkstek_maybe_flush_kantoorruimte_rewrite_rules');
add_action('init', 'werkstek_maybe_flush_kantoorruimte_rewrite_rules', 20);

function werkstek_set_kantoorruimte_archive_page_size($query) {
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    if ($query->is_post_type_archive('kantoorruimte') || $query->is_tax('locatie')) {
        $query->set('posts_per_page', 8);
    }

    if ($query->is_post_type_archive('blog') || $query->is_tax('blog_categorie')) {
        $query->set('posts_per_page', 9);
    }
}
add_action('pre_get_posts', 'werkstek_set_kantoorruimte_archive_page_size');

function werkstek_locatie_description_editor_settings() {
    return [
        'textarea_name' => 'locatie_description_html',
        'textarea_rows' => 10,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true,
        'tinymce' => [
            'block_formats' => 'Paragraaf=p; Kop 1=h1; Kop 2=h2; Kop 3=h3; Kop 4=h4; Kop 5=h5; Kop 6=h6',
        ],
    ];
}

function werkstek_decode_locatie_description_html($description) {
    return html_entity_decode($description, ENT_QUOTES | ENT_HTML5, get_bloginfo('charset'));
}

function werkstek_locatie_add_description_editor() {
    ?>
    <div class="form-field term-description-html-wrap">
        <label for="locatie-description-html"><?php esc_html_e('Beschrijving', 'werkstek-thema'); ?></label>
        <?php wp_editor('', 'locatie-description-html', werkstek_locatie_description_editor_settings()); ?>
        <p class="description"><?php esc_html_e('Gebruik de editor om HTML-content toe te voegen, inclusief koppen zoals H1, H2 en H3.', 'werkstek-thema'); ?></p>
    </div>
    <?php
}
add_action('locatie_add_form_fields', 'werkstek_locatie_add_description_editor', 5);

function werkstek_locatie_edit_description_editor($term) {
    $description = werkstek_decode_locatie_description_html($term->description);
    ?>
    <tr class="form-field term-description-html-wrap">
        <th scope="row">
            <label for="locatie-description-html"><?php esc_html_e('Beschrijving', 'werkstek-thema'); ?></label>
        </th>
        <td>
            <?php wp_editor($description, 'locatie-description-html', werkstek_locatie_description_editor_settings()); ?>
            <p class="description"><?php esc_html_e('Gebruik de editor om HTML-content toe te voegen, inclusief koppen zoals H1, H2 en H3.', 'werkstek-thema'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('locatie_edit_form_fields', 'werkstek_locatie_edit_description_editor', 5);

function werkstek_save_locatie_description_html($term_id) {
    static $is_saving = false;

    if (
        $is_saving
        || ! current_user_can('edit_term', $term_id)
        || ! isset($_POST['locatie_description_html'])
    ) {
        return;
    }

    $description = werkstek_decode_locatie_description_html(wp_unslash($_POST['locatie_description_html']));
    $description = wp_kses_post($description);
    $is_saving = true;
    wp_update_term($term_id, 'locatie', [
        'description' => $description,
    ]);
    $is_saving = false;
}
add_action('created_locatie', 'werkstek_save_locatie_description_html');
add_action('edited_locatie', 'werkstek_save_locatie_description_html');

function werkstek_filter_term_description_html($description) {
    $taxonomy = isset($_POST['taxonomy']) ? sanitize_key(wp_unslash($_POST['taxonomy'])) : '';

    if ($taxonomy === 'locatie') {
        return wp_kses_post($description);
    }

    return wp_filter_kses($description);
}

function werkstek_allow_locatie_description_html() {
    remove_filter('pre_term_description', 'wp_filter_kses');
    add_filter('pre_term_description', 'werkstek_filter_term_description_html');
}
add_action('init', 'werkstek_allow_locatie_description_html');

function werkstek_hide_default_locatie_description_field() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (! $screen || $screen->taxonomy !== 'locatie') {
        return;
    }

    echo '<style>.taxonomy-locatie .term-description-wrap{display:none;}</style>';
}
add_action('admin_head-edit-tags.php', 'werkstek_hide_default_locatie_description_field');
add_action('admin_head-term.php', 'werkstek_hide_default_locatie_description_field');

function werkstek_get_kantoorruimte_card_data($post_id) {
    $default_image = get_template_directory_uri() . '/resources/images/large.webp';
    $price_label = werkstek_get_post_field_value($post_id, ['prijs_label', 'prijs', 'price']);
    $status_label = function_exists('get_field') ? get_field('status_label', $post_id) : get_post_meta($post_id, 'status_label', true);
    $popular_field = function_exists('get_field') ? get_field('populair', $post_id) : get_post_meta($post_id, 'populair', true);
    $address = werkstek_get_post_field_value($post_id, ['adres', 'address']);
    $surface = werkstek_get_post_field_value($post_id, ['oppervlakte', 'surface', 'm2']);
    $workspaces = werkstek_get_post_field_value($post_id, ['werkplekken', 'aantal_werkplekken', 'plekken']);
    $latitude = werkstek_get_post_field_value($post_id, ['latitude', 'lat', 'breedtegraad']);
    $longitude = werkstek_get_post_field_value($post_id, ['longitude', 'lng', 'long', 'lengtegraad']);

    if (is_array($address)) {
        $latitude = $latitude ?: ($address['lat'] ?? $address['latitude'] ?? '');
        $longitude = $longitude ?: ($address['lng'] ?? $address['longitude'] ?? '');
        $address = $address['address'] ?? $address['formatted_address'] ?? $address['name'] ?? '';
    }

    $facilities = werkstek_get_kantoorruimte_facilities($post_id);
    $terms = get_the_terms($post_id, 'locatie');
    $location = '';
    $location_url = '';

    if (! empty($terms) && ! is_wp_error($terms)) {
        $primary_term = array_shift($terms);
        $term_link = get_term_link($primary_term);
        $location = $primary_term->name;
        $location_url = is_wp_error($term_link) ? '' : $term_link;
    }

    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'price' => is_string($price_label) ? trim($price_label) : '',
        'price_amount' => werkstek_parse_price_amount($price_label),
        'availability' => is_string($status_label) ? trim($status_label) : '',
        'url' => get_permalink($post_id),
        'image' => get_the_post_thumbnail_url($post_id, 'large') ?: $default_image,
        'position' => 'background-position: center center;',
        'is_new' => false,
        'is_popular' => werkstek_is_popular_field_checked($popular_field),
        'date_timestamp' => (int) get_post_time('U', true, $post_id),
        'excerpt' => get_the_excerpt($post_id),
        'address' => $address,
        'surface' => $surface,
        'workspaces' => $workspaces,
        'location' => $location,
        'location_url' => $location_url,
        'latitude' => is_numeric($latitude) ? (float) $latitude : null,
        'longitude' => is_numeric($longitude) ? (float) $longitude : null,
        'facilities' => $facilities,
    ];
}

function werkstek_parse_price_amount($value) {
    if (is_array($value)) {
        $value = $value['label'] ?? $value['value'] ?? reset($value);
    }

    $value = is_scalar($value) ? (string) $value : '';

    if ($value === '') {
        return null;
    }

    preg_match_all('/\d+(?:[.,]\d+)?/', $value, $matches);

    if (empty($matches[0])) {
        return null;
    }

    $number = $matches[0][0];

    if (strpos($number, ',') !== false) {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);
    } elseif (preg_match('/^\d{1,3}(?:\.\d{3})+$/', $number)) {
        $number = str_replace('.', '', $number);
    }

    return is_numeric($number) ? (float) $number : null;
}

function werkstek_is_popular_field_checked($value) {
    if (empty($value)) {
        return false;
    }

    $values = is_array($value) ? $value : [$value];

    foreach ($values as $item) {
        if (is_array($item)) {
            $item = $item['value'] ?? $item['label'] ?? '';
        }

        if (strtolower(trim((string) $item)) === 'populair') {
            return true;
        }
    }

    return false;
}

function werkstek_get_kantoorruimte_facilities($post_id) {
    $facilities = function_exists('get_field') ? get_field('faciliteiten', $post_id) : get_post_meta($post_id, 'faciliteiten', true);

    if (empty($facilities)) {
        return [];
    }

    if (! is_array($facilities)) {
        $facilities = [$facilities];
    }

    $items = [];

    foreach ($facilities as $facility) {
        $label = '';
        $value = '';

        if (is_array($facility)) {
            $label = $facility['label'] ?? $facility['value'] ?? '';
            $value = $facility['value'] ?? $label;
        } else {
            $label = (string) $facility;
            $value = $label;
        }

        $label = trim(wp_strip_all_tags($label));
        $value = trim(wp_strip_all_tags((string) $value));

        if ($label === '') {
            continue;
        }

        $slug = sanitize_title($value ?: $label);

        $items[] = [
            'label' => $label,
            'slug' => $slug,
            'icon' => get_template_directory_uri() . '/resources/images/faciliteiten/' . $slug . '.svg',
        ];
    }

    return $items;
}

function werkstek_get_post_field_value($post_id, $keys) {
    foreach ($keys as $key) {
        $value = function_exists('get_field') ? get_field($key, $post_id) : get_post_meta($post_id, $key, true);

        if (is_array($value)) {
            $value = $value['label'] ?? $value['value'] ?? '';
        }

        if (is_string($value) || is_numeric($value)) {
            $value = trim((string) $value);
        }

        if ($value !== '' && $value !== null) {
            return $value;
        }
    }

    return '';
}

function werkstek_get_kantoorruimte_archive_items() {
    global $werkstek_kantoorruimte_archive_pagination, $werkstek_kantoorruimte_archive_map_items;

    $items = [];
    $werkstek_kantoorruimte_archive_map_items = [];
    $per_page = 8;
    $paged = max(
        1,
        (int) get_query_var('paged'),
        isset($_GET['paged']) ? (int) wp_unslash($_GET['paged']) : 0,
        (int) get_query_var('page')
    );
    $query_args = [
        'post_type' => 'kantoorruimte',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if (is_tax('locatie')) {
        $term = get_queried_object();

        if ($term instanceof WP_Term) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => 'locatie',
                    'field' => 'term_id',
                    'terms' => $term->term_id,
                ],
            ];
        }
    }

    $kantoorruimtes_query = new WP_Query($query_args);

    if (! $kantoorruimtes_query->have_posts()) {
        $werkstek_kantoorruimte_archive_pagination = [
            'current_page' => 1,
            'total_pages' => 1,
            'per_page' => $per_page,
            'total_items' => 0,
        ];

        return $items;
    }

    while ($kantoorruimtes_query->have_posts()) {
        $kantoorruimtes_query->the_post();
        $items[] = werkstek_get_kantoorruimte_card_data(get_the_ID());
    }

    wp_reset_postdata();

    $min_price = isset($_GET['prijs_min']) ? werkstek_parse_price_amount(wp_unslash($_GET['prijs_min'])) : null;
    $max_price = isset($_GET['prijs_max']) ? werkstek_parse_price_amount(wp_unslash($_GET['prijs_max'])) : null;

    if ($min_price !== null || $max_price !== null) {
        $items = array_values(array_filter($items, function ($item) use ($min_price, $max_price) {
            if ($item['price_amount'] === null) {
                return false;
            }

            if ($min_price !== null && $item['price_amount'] < $min_price) {
                return false;
            }

            if ($max_price !== null && $item['price_amount'] > $max_price) {
                return false;
            }

            return true;
        }));
    }

    $sort = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'populair';

    usort($items, function ($first, $second) use ($sort) {
        if ($sort === 'nieuw') {
            return $second['date_timestamp'] <=> $first['date_timestamp'];
        }

        if ($first['is_popular'] !== $second['is_popular']) {
            return $second['is_popular'] <=> $first['is_popular'];
        }

        return $second['date_timestamp'] <=> $first['date_timestamp'];
    });

    // The list is paginated below, but the map should always contain every
    // office that matches the current location and price filters.
    $werkstek_kantoorruimte_archive_map_items = $items;

    $total_items = count($items);
    $total_pages = max(1, (int) ceil($total_items / $per_page));
    $paged = min($paged, $total_pages);

    $werkstek_kantoorruimte_archive_pagination = [
        'current_page' => $paged,
        'total_pages' => $total_pages,
        'per_page' => $per_page,
        'total_items' => $total_items,
    ];

    return array_slice($items, ($paged - 1) * $per_page, $per_page);
}

function werkstek_get_kantoorruimte_archive_map_items() {
    global $werkstek_kantoorruimte_archive_map_items;

    return is_array($werkstek_kantoorruimte_archive_map_items)
        ? $werkstek_kantoorruimte_archive_map_items
        : [];
}

function werkstek_get_kantoorruimte_archive_pagination() {
    global $werkstek_kantoorruimte_archive_pagination;

    return is_array($werkstek_kantoorruimte_archive_pagination) ? $werkstek_kantoorruimte_archive_pagination : [
        'current_page' => 1,
        'total_pages' => 1,
        'per_page' => 8,
        'total_items' => 0,
    ];
}

function werkstek_get_locatie_search_items() {
    $terms = get_terms([
        'taxonomy' => 'locatie',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return [];
    }

    $items = [];

    foreach ($terms as $term) {
        $term_link = get_term_link($term);

        if (is_wp_error($term_link)) {
            continue;
        }

        $items[] = [
            'name' => $term->name,
            'slug' => $term->slug,
            'url' => $term_link,
        ];
    }

    return $items;
}

function werkstek_get_review_card_data($post_id) {
    $default_image = get_template_directory_uri() . '/resources/images/medium.webp';
    $review_text = function_exists('get_field') ? get_field('recensie', $post_id) : get_post_meta($post_id, 'recensie', true);
    $rating = function_exists('get_field') ? get_field('waardering', $post_id) : get_post_meta($post_id, 'waardering', true);

    return [
        'name' => get_the_title($post_id),
        'review' => is_string($review_text) ? trim(wp_strip_all_tags($review_text)) : '',
        'rating' => is_string($rating) ? trim(wp_strip_all_tags($rating)) : '',
        'image' => get_the_post_thumbnail_url($post_id, 'thumbnail') ?: $default_image,
    ];
}

function werkstek_get_locatie_card_data($term) {
    if (! $term instanceof WP_Term) {
        return null;
    }

    $default_image = get_template_directory_uri() . '/resources/images/large.webp';
    $image = function_exists('get_field') ? get_field('afbeelding', 'locatie_' . $term->term_id) : get_term_meta($term->term_id, 'afbeelding', true);
    $image_url = $default_image;

    if (is_array($image)) {
        $image_url = $image['sizes']['medium'] ?? $image['url'] ?? $default_image;
    } elseif (is_numeric($image)) {
        $image_url = wp_get_attachment_image_url((int) $image, 'medium') ?: $default_image;
    } elseif (is_string($image) && $image !== '') {
        $image_url = $image;
    }

    $count = (int) $term->count;
    $term_link = get_term_link($term);

    if (is_wp_error($term_link)) {
        $term_link = '#';
    }

    return [
        'name' => $term->name,
        'url' => $term_link,
        'image' => $image_url,
        'count' => $count,
        'count_label' => sprintf(
            _n('%d kantoorruimte', '%d kantoorruimtes', $count, 'werkstek-thema'),
            $count
        ),
    ];
}

function werkstek_get_youtube_video_id($value) {
    if (! is_string($value) || trim($value) === '') {
        return '';
    }

    $value = html_entity_decode(trim($value), ENT_QUOTES, 'UTF-8');

    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $value)) {
        return $value;
    }

    if (preg_match('#<iframe[^>]+src=["\']([^"\']+)["\']#i', $value, $matches)) {
        $value = $matches[1];
    }

    if (! preg_match('#^https?://#i', $value)) {
        $value = 'https://' . ltrim($value, '/');
    }

    $parts = wp_parse_url($value);
    $host = strtolower($parts['host'] ?? '');
    $host = preg_replace('/^www\./', '', $host);
    $path = trim($parts['path'] ?? '', '/');
    $video_id = '';

    if ($host === 'youtu.be') {
        $video_id = explode('/', $path)[0] ?? '';
    } elseif (in_array($host, ['youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com'], true)) {
        if ($path === 'watch') {
            parse_str($parts['query'] ?? '', $query);
            $video_id = $query['v'] ?? '';
        } elseif (preg_match('#^(?:embed|shorts|live|v)/([^/]+)#', $path, $matches)) {
            $video_id = $matches[1];
        }
    }

    return preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id) ? $video_id : '';
}

function werkstek_get_youtube_embed_url($value) {
    $video_id = werkstek_get_youtube_video_id($value);

    return $video_id
        ? 'https://www.youtube-nocookie.com/embed/' . rawurlencode($video_id) . '?rel=0&playsinline=1'
        : '';
}

function werkstek_get_community_video_data($post_id) {
    $video = function_exists('get_field') ? get_field('video', $post_id) : get_post_meta($post_id, 'video', true);
    $placeholder = function_exists('get_field') ? get_field('placeholder', $post_id) : get_post_meta($post_id, 'placeholder', true);
    $default_image = get_template_directory_uri() . '/resources/images/large.webp';
    $placeholder_url = '';
    $video_url = '';
    $video_embed = '';
    $youtube_embed_url = '';
    $video_type = 'url';

    if (is_array($placeholder)) {
        $placeholder_url = $placeholder['sizes']['large'] ?? $placeholder['url'] ?? '';
    } elseif (is_numeric($placeholder)) {
        $placeholder_url = wp_get_attachment_image_url((int) $placeholder, 'large') ?: '';
    } elseif (is_string($placeholder)) {
        $placeholder_url = $placeholder;
    }

    if (is_array($video)) {
        $video_url = $video['url'] ?? '';
        $video_type = 'file';
    } elseif (is_string($video) && $video !== '') {
        $video = trim($video);

        $youtube_embed_url = werkstek_get_youtube_embed_url($video);

        if ($youtube_embed_url) {
            $video_url = $video;
            $video_type = 'youtube';
        } elseif (preg_match('#<iframe|<video#i', $video)) {
            $video_embed = wp_kses($video, [
                'iframe' => [
                    'src' => true,
                    'title' => true,
                    'width' => true,
                    'height' => true,
                    'allow' => true,
                    'allowfullscreen' => true,
                    'loading' => true,
                    'referrerpolicy' => true,
                    'frameborder' => true,
                ],
                'video' => [
                    'src' => true,
                    'controls' => true,
                    'poster' => true,
                    'preload' => true,
                ],
                'source' => [
                    'src' => true,
                    'type' => true,
                ],
            ]);
            $video_type = 'embed';
        } else {
            $video_url = esc_url_raw($video);
            $extension = strtolower(pathinfo(wp_parse_url($video, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
            $video_type = in_array($extension, ['mp4', 'webm', 'ogg', 'mov'], true) ? 'file' : 'url';
        }
    }

    if (! $video_url && ! $video_embed) {
        return null;
    }

    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'logo' => get_the_post_thumbnail_url($post_id, 'medium') ?: $default_image,
        'poster' => get_the_post_thumbnail_url($post_id, 'large') ?: $default_image,
        'placeholder' => $placeholder_url ?: get_the_post_thumbnail_url($post_id, 'large') ?: $default_image,
        'video_url' => $video_url,
        'video_embed' => $video_embed,
        'youtube_embed_url' => $youtube_embed_url,
        'video_type' => $video_type,
    ];
}

function werkstek_locatie_add_image_field() {
    ?>
    <div class="form-field term-image-wrap">
        <label for="locatie-afbeelding"><?php esc_html_e('Afbeelding', 'werkstek-thema'); ?></label>
        <input type="hidden" id="locatie-afbeelding" name="afbeelding" value="">
        <div id="locatie-afbeelding-preview" style="margin-bottom:12px;"></div>
        <button type="button" class="button" data-locatie-image-upload>
            <?php esc_html_e('Afbeelding kiezen', 'werkstek-thema'); ?>
        </button>
        <button type="button" class="button-link-delete" data-locatie-image-remove style="display:none; margin-left:8px;">
            <?php esc_html_e('Verwijderen', 'werkstek-thema'); ?>
        </button>
        <p class="description"><?php esc_html_e('Upload een afbeelding voor deze locatiekaart.', 'werkstek-thema'); ?></p>
    </div>
    <?php
}
add_action('locatie_add_form_fields', 'werkstek_locatie_add_image_field');

function werkstek_locatie_edit_image_field($term) {
    $image_id = get_term_meta($term->term_id, 'afbeelding', true);
    $image_url = $image_id ? wp_get_attachment_image_url((int) $image_id, 'thumbnail') : '';
    ?>
    <tr class="form-field term-image-wrap">
        <th scope="row">
            <label for="locatie-afbeelding"><?php esc_html_e('Afbeelding', 'werkstek-thema'); ?></label>
        </th>
        <td>
            <input type="hidden" id="locatie-afbeelding" name="afbeelding" value="<?php echo esc_attr($image_id); ?>">
            <div id="locatie-afbeelding-preview" style="margin-bottom:12px;">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="" style="width:96px; height:96px; object-fit:cover; border-radius:16px; box-shadow:0 8px 18px rgba(15,23,42,0.12);">
                <?php endif; ?>
            </div>
            <button type="button" class="button" data-locatie-image-upload>
                <?php esc_html_e('Afbeelding kiezen', 'werkstek-thema'); ?>
            </button>
            <button type="button" class="button-link-delete" data-locatie-image-remove style="<?php echo $image_url ? '' : 'display:none;'; ?> margin-left:8px;">
                <?php esc_html_e('Verwijderen', 'werkstek-thema'); ?>
            </button>
            <p class="description"><?php esc_html_e('Upload een afbeelding voor deze locatiekaart.', 'werkstek-thema'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('locatie_edit_form_fields', 'werkstek_locatie_edit_image_field');

function werkstek_save_locatie_image($term_id) {
    if (! current_user_can('edit_term', $term_id) || ! isset($_POST['afbeelding'])) {
        return;
    }

    $image_id = absint(wp_unslash($_POST['afbeelding']));

    if ($image_id && ! wp_attachment_is_image($image_id)) {
        return;
    }

    update_term_meta($term_id, 'afbeelding', $image_id);
}
add_action('created_locatie', 'werkstek_save_locatie_image');
add_action('edited_locatie', 'werkstek_save_locatie_image');

function werkstek_enqueue_locatie_admin_media($hook_suffix) {
    if ($hook_suffix !== 'edit-tags.php' && $hook_suffix !== 'term.php') {
        return;
    }

    $screen = get_current_screen();

    if (! $screen || $screen->taxonomy !== 'locatie') {
        return;
    }

    wp_enqueue_media();

    $remove_text = esc_js(__('Verwijderen', 'werkstek-thema'));

    wp_add_inline_script(
        'jquery',
        "
        document.addEventListener('DOMContentLoaded', function () {
            const imageField = document.querySelector('#locatie-afbeelding');
            const preview = document.querySelector('#locatie-afbeelding-preview');
            const uploadButton = document.querySelector('[data-locatie-image-upload]');
            const removeButton = document.querySelector('[data-locatie-image-remove]');

            if (!imageField || !preview || !uploadButton || !removeButton || typeof wp === 'undefined' || !wp.media) {
                return;
            }

            let mediaFrame;

            const renderPreview = (attachment) => {
                imageField.value = attachment.id;
                const image = document.createElement('img');
                image.src = attachment.url;
                image.alt = '';
                image.style.cssText = 'width:96px;height:96px;object-fit:cover;border-radius:16px;box-shadow:0 8px 18px rgba(15,23,42,0.12);';
                preview.replaceChildren(image);
                removeButton.style.display = 'inline-block';
            };

            uploadButton.addEventListener('click', function (event) {
                event.preventDefault();

                if (mediaFrame) {
                    mediaFrame.open();
                    return;
                }

                mediaFrame = wp.media({
                    title: 'Selecteer afbeelding',
                    button: {
                        text: 'Gebruik afbeelding'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    renderPreview(attachment);
                });

                mediaFrame.open();
            });

            removeButton.addEventListener('click', function (event) {
                event.preventDefault();
                imageField.value = '';
                preview.innerHTML = '';
                removeButton.style.display = 'none';
            });
        });
        "
    );
}
add_action('admin_enqueue_scripts', 'werkstek_enqueue_locatie_admin_media');

function werkstek_get_blog_term_icon_url($term) {
    if (! $term instanceof WP_Term) {
        return '';
    }

    $icon = function_exists('get_field') ? get_field('icoon', 'blog_categorie_' . $term->term_id) : null;

    if (empty($icon)) {
        $icon = get_term_meta($term->term_id, 'icoon', true);
    }

    if (is_numeric($icon)) {
        return wp_get_attachment_url((int) $icon) ?: '';
    }

    if (is_array($icon)) {
        $icon_id = isset($icon['ID']) ? (int) $icon['ID'] : (isset($icon['id']) ? (int) $icon['id'] : 0);

        return $icon['url'] ?? ($icon_id ? wp_get_attachment_url($icon_id) : '');
    }

    return is_string($icon) ? trim($icon) : '';
}

function werkstek_get_blog_tags($post_id) {
    $rows = function_exists('get_field') ? get_field('tags', $post_id) : get_post_meta($post_id, 'tags', true);

    if (empty($rows) && function_exists('get_field')) {
        $rows = get_field('Tags', $post_id);
    }

    if (empty($rows) || ! is_array($rows)) {
        return [];
    }

    $tags = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $tag = trim((string) ($row['tag'] ?? $row['Tag'] ?? ''));

        if ($tag === '') {
            continue;
        }

        $tags[] = strpos($tag, '#') === 0 ? $tag : '#' . $tag;
    }

    return $tags;
}

function werkstek_get_blog_card_data($post_id) {
    $default_image = get_template_directory_uri() . '/resources/images/large.webp';
    $terms = get_the_terms($post_id, 'blog_categorie');
    $primary_term = (! empty($terms) && ! is_wp_error($terms)) ? array_values($terms)[0] : null;

    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'url' => get_permalink($post_id),
        'image' => get_the_post_thumbnail_url($post_id, 'large') ?: $default_image,
        'image_alt' => get_the_title($post_id),
        'term' => $primary_term instanceof WP_Term ? [
            'name' => $primary_term->name,
            'url' => get_term_link($primary_term),
            'icon' => werkstek_get_blog_term_icon_url($primary_term),
        ] : null,
        'tags' => werkstek_get_blog_tags($post_id),
    ];
}

function werkstek_blog_categorie_add_icon_field() {
    ?>
    <div class="form-field term-icon-wrap">
        <label for="blog-categorie-icoon"><?php esc_html_e('Icoon', 'werkstek-thema'); ?></label>
        <input type="hidden" id="blog-categorie-icoon" name="icoon" value="">
        <div id="blog-categorie-icoon-preview" style="margin-bottom:12px;"></div>
        <button type="button" class="button" data-blog-category-icon-upload>
            <?php esc_html_e('Icoon kiezen', 'werkstek-thema'); ?>
        </button>
        <button type="button" class="button-link-delete" data-blog-category-icon-remove style="display:none; margin-left:8px;">
            <?php esc_html_e('Verwijderen', 'werkstek-thema'); ?>
        </button>
        <p class="description"><?php esc_html_e('Koppel een SVG icoon aan deze blog categorie.', 'werkstek-thema'); ?></p>
    </div>
    <?php
}
add_action('blog_categorie_add_form_fields', 'werkstek_blog_categorie_add_icon_field');

function werkstek_blog_categorie_edit_icon_field($term) {
    $icon_id = get_term_meta($term->term_id, 'icoon', true);
    $icon_url = $icon_id ? wp_get_attachment_url((int) $icon_id) : '';
    ?>
    <tr class="form-field term-icon-wrap">
        <th scope="row">
            <label for="blog-categorie-icoon"><?php esc_html_e('Icoon', 'werkstek-thema'); ?></label>
        </th>
        <td>
            <input type="hidden" id="blog-categorie-icoon" name="icoon" value="<?php echo esc_attr($icon_id); ?>">
            <div id="blog-categorie-icoon-preview" style="margin-bottom:12px;">
                <?php if ($icon_url): ?>
                    <img src="<?php echo esc_url($icon_url); ?>" alt="" style="width:40px;height:40px;object-fit:contain;border-radius:9999px;background: #FCF8F3;box-shadow:0 8px 18px rgba(15,23,42,0.12);padding:8px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button" data-blog-category-icon-upload>
                <?php esc_html_e('Icoon kiezen', 'werkstek-thema'); ?>
            </button>
            <button type="button" class="button-link-delete" data-blog-category-icon-remove style="<?php echo $icon_url ? '' : 'display:none;'; ?> margin-left:8px;">
                <?php esc_html_e('Verwijderen', 'werkstek-thema'); ?>
            </button>
            <p class="description"><?php esc_html_e('Koppel een SVG icoon aan deze blog categorie.', 'werkstek-thema'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('blog_categorie_edit_form_fields', 'werkstek_blog_categorie_edit_icon_field');

function werkstek_save_blog_categorie_icon($term_id) {
    if (! current_user_can('edit_term', $term_id) || ! isset($_POST['icoon'])) {
        return;
    }

    $icon_id = absint(wp_unslash($_POST['icoon']));
    $icon_mime = $icon_id ? (string) get_post_mime_type($icon_id) : '';

    if ($icon_id && strpos($icon_mime, 'image/') !== 0) {
        return;
    }

    update_term_meta($term_id, 'icoon', $icon_id);
}
add_action('created_blog_categorie', 'werkstek_save_blog_categorie_icon');
add_action('edited_blog_categorie', 'werkstek_save_blog_categorie_icon');

function werkstek_enqueue_blog_categorie_admin_media($hook_suffix) {
    if ($hook_suffix !== 'edit-tags.php' && $hook_suffix !== 'term.php') {
        return;
    }

    $screen = get_current_screen();

    if (! $screen || $screen->taxonomy !== 'blog_categorie') {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script(
        'jquery',
        "
        document.addEventListener('DOMContentLoaded', function () {
            const iconField = document.querySelector('#blog-categorie-icoon');
            const preview = document.querySelector('#blog-categorie-icoon-preview');
            const uploadButton = document.querySelector('[data-blog-category-icon-upload]');
            const removeButton = document.querySelector('[data-blog-category-icon-remove]');

            if (!iconField || !preview || !uploadButton || !removeButton || typeof wp === 'undefined' || !wp.media) {
                return;
            }

            let mediaFrame;

            const renderPreview = (attachment) => {
                iconField.value = attachment.id;
                const image = document.createElement('img');
                image.src = attachment.url;
                image.alt = '';
                image.style.cssText = 'width:40px;height:40px;object-fit:contain;border-radius:9999px;background:#FCF8F3;box-shadow:0 8px 18px rgba(15,23,42,0.12);padding:8px;';
                preview.replaceChildren(image);
                removeButton.style.display = 'inline-block';
            };

            uploadButton.addEventListener('click', function (event) {
                event.preventDefault();

                if (mediaFrame) {
                    mediaFrame.open();
                    return;
                }

                mediaFrame = wp.media({
                    title: 'Selecteer icoon',
                    button: {
                        text: 'Gebruik icoon'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    renderPreview(attachment);
                });

                mediaFrame.open();
            });

            removeButton.addEventListener('click', function (event) {
                event.preventDefault();
                iconField.value = '';
                preview.innerHTML = '';
                removeButton.style.display = 'none';
            });
        });
        "
    );
}
add_action('admin_enqueue_scripts', 'werkstek_enqueue_blog_categorie_admin_media');

function werkstek_kantoorruimte_permalink($post_link, $post) {
    if ($post->post_type !== 'kantoorruimte' || $post->post_status !== 'publish') {
        return $post_link;
    }

    $terms = get_the_terms($post->ID, 'locatie');

    if (empty($terms) || is_wp_error($terms)) {
        return str_replace('%locatie%/', 'algemeen/', $post_link);
    }

    $primary_term = array_shift($terms);

    return str_replace('%locatie%', $primary_term->slug, $post_link);
}
add_filter('post_type_link', 'werkstek_kantoorruimte_permalink', 10, 2);

function werkstek_kantoorruimte_rewrite_rules($rules) {
    $new_rules = [
        'kantoorruimte-huren/page/([0-9]+)/?$' => 'index.php?post_type=kantoorruimte&paged=$matches[1]',
        'kantoorruimte-huren/([^/]+)/page/([0-9]+)/?$' => 'index.php?locatie=$matches[1]&paged=$matches[2]',
        'kantoorruimte-huren/([^/]+)/([^/]+)/?$' => 'index.php?kantoorruimte=$matches[2]',
    ];

    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'werkstek_kantoorruimte_rewrite_rules');

function werkstek_verify_recaptcha_response($token) {
    if (! defined('WERKSTEK_RECAPTCHA_SECRET_KEY') || WERKSTEK_RECAPTCHA_SECRET_KEY === '' || $token === '') {
        return false;
    }

    $request_body = [
        'secret' => WERKSTEK_RECAPTCHA_SECRET_KEY,
        'response' => $token,
    ];

    if (! empty($_SERVER['REMOTE_ADDR'])) {
        $request_body['remoteip'] = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'timeout' => 10,
        'body' => $request_body,
    ]);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return false;
    }

    $result = json_decode(wp_remote_retrieve_body($response), true);

    if (! is_array($result) || empty($result['success']) || empty($result['hostname'])) {
        return false;
    }

    $expected_hostname = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
    $verified_hostname = strtolower((string) $result['hostname']);
    $allowed_hostnames = (array) apply_filters('werkstek_recaptcha_allowed_hostnames', [$expected_hostname]);
    $allowed_hostnames = array_filter(array_map('strtolower', $allowed_hostnames));

    return $expected_hostname !== '' && in_array($verified_hostname, $allowed_hostnames, true);
}

function werkstek_rondleiding_form_signature($timestamp, $post_id) {
    return hash_hmac('sha256', $timestamp . '|' . $post_id, wp_salt('nonce'));
}

function werkstek_rondleiding_is_rate_limited() {
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';

    if ($ip_address === '') {
        return false;
    }

    $key = 'werkstek_tour_rate_' . substr(hash_hmac('sha256', $ip_address, wp_salt('auth')), 0, 32);
    $attempts = (int) get_transient($key);

    if ($attempts >= 5) {
        return true;
    }

    set_transient($key, $attempts + 1, 15 * MINUTE_IN_SECONDS);

    return false;
}

function werkstek_handle_rondleiding_aanvraag() {
    $post_id = isset($_POST['kantoorruimte_id']) ? absint($_POST['kantoorruimte_id']) : 0;
    $return_url = $post_id ? get_permalink($post_id) : home_url('/');

    if (! $post_id || get_post_type($post_id) !== 'kantoorruimte') {
        wp_safe_redirect(add_query_arg('rondleiding', 'error', $return_url));
        exit;
    }

    if (
        ! isset($_POST['werkstek_rondleiding_nonce'])
        || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['werkstek_rondleiding_nonce'])), 'werkstek_rondleiding_aanvraag_' . $post_id)
    ) {
        wp_safe_redirect(add_query_arg('rondleiding', 'error', $return_url));
        exit;
    }

    $honeypot = isset($_POST['website']) ? trim((string) wp_unslash($_POST['website'])) : '';
    $form_timestamp = isset($_POST['werkstek_form_time']) ? absint($_POST['werkstek_form_time']) : 0;
    $form_signature = isset($_POST['werkstek_form_signature'])
        ? sanitize_text_field(wp_unslash($_POST['werkstek_form_signature']))
        : '';
    $form_age = time() - $form_timestamp;
    $expected_signature = werkstek_rondleiding_form_signature($form_timestamp, $post_id);

    if (
        $honeypot !== ''
        || $form_timestamp <= 0
        || $form_age < 3
        || $form_age > DAY_IN_SECONDS
        || ! hash_equals($expected_signature, $form_signature)
        || werkstek_rondleiding_is_rate_limited()
    ) {
        wp_safe_redirect(add_query_arg('rondleiding', 'spam-error', $return_url));
        exit;
    }

    $recaptcha_token = isset($_POST['g-recaptcha-response'])
        ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response']))
        : '';

    if (! werkstek_verify_recaptcha_response($recaptcha_token)) {
        wp_safe_redirect(add_query_arg('rondleiding', 'captcha-error', $return_url));
        exit;
    }

    $name = isset($_POST['naam']) ? sanitize_text_field(wp_unslash($_POST['naam'])) : '';
    $email = isset($_POST['emailadres']) ? sanitize_email(wp_unslash($_POST['emailadres'])) : '';
    $phone = isset($_POST['telefoonnummer']) ? sanitize_text_field(wp_unslash($_POST['telefoonnummer'])) : '';
    $privacy_accepted = isset($_POST['privacy_akkoord']) && wp_unslash($_POST['privacy_akkoord']) === '1';

    $phone_digits = preg_replace('/\D+/', '', $phone);
    $contains_link = preg_match('/(?:https?:\/\/|www\.|\[[^\]]*url|<a\b)/i', $name . ' ' . $phone);

    if (
        $name === ''
        || strlen($name) > 100
        || $email === ''
        || strlen($email) > 254
        || ! is_email($email)
        || $phone === ''
        || strlen($phone) > 40
        || strlen($phone_digits) < 7
        || strlen($phone_digits) > 15
        || $contains_link
        || ! $privacy_accepted
    ) {
        wp_safe_redirect(add_query_arg('rondleiding', 'error', $return_url));
        exit;
    }

    $submission_fingerprint = hash_hmac('sha256', strtolower($email) . '|' . $phone_digits . '|' . $post_id, wp_salt('auth'));
    $duplicate_key = 'werkstek_tour_duplicate_' . substr($submission_fingerprint, 0, 32);

    if (get_transient($duplicate_key)) {
        wp_safe_redirect(add_query_arg('rondleiding', 'success', $return_url));
        exit;
    }

    $kantoorruimte_title = get_the_title($post_id);
    $aanvraag_id = wp_insert_post([
        'post_type' => 'rondleiding_aanvraag',
        'post_status' => 'publish',
        'post_title' => sprintf('Rondleiding aanvraag - %s - %s', $name, $kantoorruimte_title),
        'meta_input' => [
            'naam' => $name,
            'emailadres' => $email,
            'telefoonnummer' => $phone,
            'kantoorruimte_id' => $post_id,
            'kantoorruimte_titel' => $kantoorruimte_title,
            'kantoorruimte_url' => get_permalink($post_id),
            'privacy_akkoord' => '1',
            'privacy_akkoord_op' => current_time('mysql'),
        ],
    ], true);

    $status = is_wp_error($aanvraag_id) ? 'error' : 'success';

    if (! is_wp_error($aanvraag_id)) {
        set_transient($duplicate_key, '1', HOUR_IN_SECONDS);

        $recipients = [
            'info@werkstek.nl',
            'info@thomaskat.nl',
        ];
        $subject = sprintf('Nieuwe rondleiding aanvraag: %s', $kantoorruimte_title);
        $message = implode("\n", [
            'Er is een nieuwe rondleiding aangevraagd.',
            '',
            'Naam: ' . $name,
            'E-mailadres: ' . $email,
            'Telefoonnummer: ' . $phone,
            'Kantoorruimte: ' . $kantoorruimte_title,
            'Pagina: ' . get_permalink($post_id),
        ]);
        $headers = [
            sprintf('Reply-To: %s <%s>', $name, $email),
        ];

        wp_mail($recipients, $subject, $message, $headers);
    }

    wp_safe_redirect(add_query_arg('rondleiding', $status, $return_url));
    exit;
}
add_action('admin_post_werkstek_rondleiding_aanvraag', 'werkstek_handle_rondleiding_aanvraag');
add_action('admin_post_nopriv_werkstek_rondleiding_aanvraag', 'werkstek_handle_rondleiding_aanvraag');

function werkstek_rondleiding_aanvraag_columns($columns) {
    return [
        'cb' => $columns['cb'] ?? '',
        'title' => 'Aanvraag',
        'kantoorruimte' => 'Kantoorruimte',
        'naam' => 'Naam',
        'emailadres' => 'E-mailadres',
        'telefoonnummer' => 'Telefoonnummer',
        'date' => $columns['date'] ?? 'Datum',
    ];
}
add_filter('manage_rondleiding_aanvraag_posts_columns', 'werkstek_rondleiding_aanvraag_columns');

function werkstek_rondleiding_aanvraag_column_content($column, $post_id) {
    if ($column === 'kantoorruimte') {
        $kantoorruimte_id = (int) get_post_meta($post_id, 'kantoorruimte_id', true);

        if ($kantoorruimte_id) {
            echo '<a href="' . esc_url(get_edit_post_link($kantoorruimte_id)) . '">' . esc_html(get_the_title($kantoorruimte_id)) . '</a>';
        }
    }

    if (in_array($column, ['naam', 'emailadres', 'telefoonnummer'], true)) {
        echo esc_html(get_post_meta($post_id, $column, true));
    }
}
add_action('manage_rondleiding_aanvraag_posts_custom_column', 'werkstek_rondleiding_aanvraag_column_content', 10, 2);
