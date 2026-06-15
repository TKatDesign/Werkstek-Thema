<?php
/**
 * Theme Setup - Main entry point
 */

require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/cleanup.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/acf.php';

function werkstek_thema_setup() {
    add_theme_support('custom-logo', [
        'height'      => 96,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Hoofdmenu', 'werkstek-thema'),
    ]);
}
add_action('after_setup_theme', 'werkstek_thema_setup');

function vite($entry) {
    static $clientInjected = false;

    if (!vite_theme_has_manifest() && vite_theme_is_dev_server_available()) {
        if (!$clientInjected) {
            echo '<script type="module" src="' . esc_url(VITE_THEME_DEV_CLIENT_PATH) . '"></script>';
            $clientInjected = true;
        }

        echo '<script type="module" src="' . esc_url(trailingslashit(VITE_THEME_DEV_SERVER) . ltrim($entry, '/')) . '"></script>';
        return;
    }

    echo '<script type="module" src="' . esc_url(get_theme_file_uri('dist/' . $entry)) . '"></script>';
}

// Source - https://stackoverflow.com/a/14697220
// Posted by Evan
// Retrieved 2026-03-19, License - CC BY-SA 3.0

function remove_editor() {
  remove_post_type_support('page', 'editor');
}
add_action('admin_init', 'remove_editor');

function werkstek_duplicate_post_as_draft() {
    if (! is_admin()) {
        wp_die(esc_html__('Ongeldige aanvraag.', 'werkstek-thema'));
    }

    $post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;

    if (! $post_id || ! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'werkstek_duplicate_post_' . $post_id)) {
        wp_die(esc_html__('Deze duplicatie-aanvraag is ongeldig.', 'werkstek-thema'));
    }

    $post = get_post($post_id);

    if (! $post || ! current_user_can('edit_post', $post_id)) {
        wp_die(esc_html__('Je hebt geen rechten om dit item te dupliceren.', 'werkstek-thema'));
    }

    $new_post_id = wp_insert_post([
        'post_author' => get_current_user_id(),
        'post_content' => $post->post_content,
        'post_content_filtered' => $post->post_content_filtered,
        'post_excerpt' => $post->post_excerpt,
        'post_name' => '',
        'post_parent' => $post->post_parent,
        'post_password' => $post->post_password,
        'post_status' => 'draft',
        'post_title' => $post->post_title . ' (kopie)',
        'post_type' => $post->post_type,
        'to_ping' => $post->to_ping,
        'menu_order' => $post->menu_order,
    ], true);

    if (is_wp_error($new_post_id)) {
        wp_die(esc_html($new_post_id->get_error_message()));
    }

    $taxonomies = get_object_taxonomies($post->post_type);

    foreach ($taxonomies as $taxonomy) {
        $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);

        if (! is_wp_error($terms)) {
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }
    }

    $meta = get_post_meta($post_id);

    foreach ($meta as $meta_key => $values) {
        if (in_array($meta_key, ['_edit_lock', '_edit_last'], true)) {
            continue;
        }

        foreach ($values as $value) {
            add_post_meta($new_post_id, $meta_key, maybe_unserialize($value));
        }
    }

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
    exit;
}
add_action('admin_action_werkstek_duplicate_post', 'werkstek_duplicate_post_as_draft');

function werkstek_add_duplicate_post_row_action($actions, $post) {
    if (! current_user_can('edit_post', $post->ID)) {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin.php?action=werkstek_duplicate_post&post=' . $post->ID),
        'werkstek_duplicate_post_' . $post->ID
    );

    $actions['werkstek_duplicate'] = sprintf(
        '<a href="%s">%s</a>',
        esc_url($url),
        esc_html__('Dupliceren', 'werkstek-thema')
    );

    return $actions;
}
add_filter('post_row_actions', 'werkstek_add_duplicate_post_row_action', 10, 2);
add_filter('page_row_actions', 'werkstek_add_duplicate_post_row_action', 10, 2);
