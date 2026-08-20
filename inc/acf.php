<?php
/**
 * ACF options and field helpers.
 */

function werkstek_register_acf_options_pages() {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => 'Overige instellingen',
        'menu_title' => 'Overige instellingen',
        'menu_slug' => 'overige-instellingen',
        'capability' => 'edit_posts',
        'redirect' => false,
        'post_id' => 'option',
    ]);
}
add_action('acf/init', 'werkstek_register_acf_options_pages');

function werkstek_register_footer_social_fields() {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_werkstek_footer_socials',
        'title' => 'Footer social media',
        'fields' => [
            [
                'key' => 'field_werkstek_footer_instagram_url',
                'label' => 'Instagram URL',
                'name' => 'footer_instagram_url',
                'type' => 'url',
                'placeholder' => 'https://www.instagram.com/...',
            ],
            [
                'key' => 'field_werkstek_footer_facebook_url',
                'label' => 'Facebook URL',
                'name' => 'footer_facebook_url',
                'type' => 'url',
                'placeholder' => 'https://www.facebook.com/...',
            ],
            [
                'key' => 'field_werkstek_footer_linkedin_url',
                'label' => 'LinkedIn URL',
                'name' => 'footer_linkedin_url',
                'type' => 'url',
                'placeholder' => 'https://www.linkedin.com/company/...',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'overige-instellingen',
                ],
            ],
        ],
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ]);
}
add_action('acf/init', 'werkstek_register_footer_social_fields', 20);

function werkstek_get_vastgoedconsultants() {
    if (! function_exists('get_field')) {
        return [];
    }

    $rows = get_field('vastgoedconsultant', 'option');

    if (empty($rows) || ! is_array($rows)) {
        return [];
    }

    $consultants = [];

    foreach ($rows as $index => $row) {
        if (! is_array($row)) {
            continue;
        }

        $name = trim((string) ($row['naam'] ?? ''));
        $phone = trim((string) ($row['telefoonnummer'] ?? ''));
        $image = werkstek_normalize_acf_image($row['afbeelding'] ?? null, 'medium');

        if ($name === '' && $phone === '' && $image['url'] === '') {
            continue;
        }

        $consultants[(string) $index] = [
            'id' => (string) $index,
            'name' => $name,
            'phone' => $phone,
            'image' => $image['url'],
            'image_alt' => $image['alt'] ?: $name,
        ];
    }

    return $consultants;
}

function werkstek_normalize_acf_image($image, $size = 'medium') {
    if (is_numeric($image)) {
        $image_id = (int) $image;

        return [
            'url' => wp_get_attachment_image_url($image_id, $size) ?: '',
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: '',
        ];
    }

    if (is_array($image)) {
        $image_id = isset($image['ID']) ? (int) $image['ID'] : (isset($image['id']) ? (int) $image['id'] : 0);

        return [
            'url' => $image['sizes'][$size] ?? $image['url'] ?? ($image_id ? wp_get_attachment_image_url($image_id, $size) : ''),
            'alt' => $image['alt'] ?? ($image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : ''),
        ];
    }

    return [
        'url' => is_string($image) ? trim($image) : '',
        'alt' => '',
    ];
}

function werkstek_load_vastgoedconsultant_choices($field) {
    if (($field['type'] ?? '') !== 'select') {
        return $field;
    }

    $field['choices'] = [];
    $consultants = werkstek_get_vastgoedconsultants();

    foreach ($consultants as $id => $consultant) {
        $label = $consultant['name'] ?: $consultant['phone'];

        if ($consultant['name'] && $consultant['phone']) {
            $label .= ' - ' . $consultant['phone'];
        }

        $field['choices'][$id] = $label;
    }

    return $field;
}
add_filter('acf/load_field/name=vastgoedconsultant', 'werkstek_load_vastgoedconsultant_choices');

function werkstek_add_community_videos_button_toggle($field) {
    if (($field['type'] ?? '') !== 'flexible_content' || empty($field['layouts']) || ! is_array($field['layouts'])) {
        return $field;
    }

    foreach ($field['layouts'] as &$layout) {
        if (($layout['name'] ?? '') !== 'community_videos') {
            continue;
        }

        $layout['sub_fields'] = $layout['sub_fields'] ?? [];

        foreach ($layout['sub_fields'] as $sub_field) {
            if (($sub_field['name'] ?? '') === 'toon_button_onderin') {
                return $field;
            }
        }

        $layout['sub_fields'][] = [
            'key' => 'field_werkstek_community_videos_toon_button_onderin',
            'label' => 'Toon button onderin',
            'name' => 'toon_button_onderin',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'message' => 'Toon de button "Over onze community"',
            'default_value' => 1,
            'ui' => 1,
            'ui_on_text' => 'Ja',
            'ui_off_text' => 'Nee',
        ];

        return $field;
    }
    unset($layout);

    return $field;
}
add_filter('acf/load_field/name=componenten', 'werkstek_add_community_videos_button_toggle');

function werkstek_add_contact_hero_layout($field) {
    if (($field['type'] ?? '') !== 'flexible_content' || empty($field['layouts']) || ! is_array($field['layouts'])) {
        return $field;
    }

    foreach ($field['layouts'] as $layout) {
        if (($layout['name'] ?? '') === 'contact_hero') {
            return $field;
        }
    }

    $field['layouts']['layout_werkstek_contact_hero'] = [
        'key' => 'layout_werkstek_contact_hero',
        'name' => 'contact_hero',
        'label' => 'Contact hero',
        'display' => 'block',
        'sub_fields' => [
            [
                'key' => 'field_werkstek_contact_hero_afbeelding',
                'label' => 'Afbeelding',
                'name' => 'afbeelding',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_werkstek_contact_hero_subtitel',
                'label' => 'Subtitel',
                'name' => 'subtitel',
                'type' => 'text',
                'default_value' => 'Wij helpen je verder',
            ],
            [
                'key' => 'field_werkstek_contact_hero_titel',
                'label' => 'Titel',
                'name' => 'titel',
                'type' => 'text',
                'default_value' => 'Neem direct contact op!',
            ],
            [
                'key' => 'field_werkstek_contact_hero_tekst',
                'label' => 'Tekst',
                'name' => 'tekst',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => 'wpautop',
            ],
            [
                'key' => 'field_werkstek_contact_hero_telefoonnummer',
                'label' => 'Telefoonnummer',
                'name' => 'telefoonnummer',
                'type' => 'text',
            ],
            [
                'key' => 'field_werkstek_contact_hero_e_mailadres',
                'label' => 'E-mailadres',
                'name' => 'e-mailadres',
                'type' => 'email',
            ],
        ],
        'min' => '',
        'max' => '',
    ];

    return $field;
}
add_filter('acf/load_field/name=componenten', 'werkstek_add_contact_hero_layout');

function werkstek_get_selected_vastgoedconsultant($post_id) {
    if (! function_exists('get_field')) {
        return null;
    }

    $selected = get_field('vastgoedconsultant', $post_id);

    if (is_array($selected)) {
        $selected = $selected['value'] ?? reset($selected);
    }

    $selected = is_scalar($selected) ? (string) $selected : '';

    if ($selected === '') {
        return null;
    }

    $consultants = werkstek_get_vastgoedconsultants();

    return $consultants[$selected] ?? null;
}
