<?php
/**
 * Asset Loading - Unified Dev and Production
 */

// Define shared constants.
define('VITE_THEME_ASSETS_DIR', get_template_directory_uri() . '/dist');
define('VITE_THEME_MANIFEST_PATH', get_template_directory() . '/dist/.vite/manifest.json');
define('VITE_THEME_DEV_SERVER', 'http://localhost:5173');
define('VITE_THEME_DEV_ASSETS_DIR', 'resources');
define('VITE_THEME_DEV_CLIENT_PATH', VITE_THEME_DEV_SERVER . '/@vite/client');
define('VITE_THEME_DEV_SCRIPTS_PATH', VITE_THEME_DEV_SERVER . '/resources/scripts/scripts.js');
define('VITE_THEME_DEV_STYLES_PATH', VITE_THEME_DEV_SERVER . '/resources/styles/styles.css');
define('WERKSTEK_GOOGLE_MAPS_API_KEY', 'AIzaSyB-alg9vyS89fUT-39wNwYmmm1I9DV0jrY');

function werkstek_allow_svg_uploads($mimes) {
    if (current_user_can('upload_files')) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
    }

    return $mimes;
}
add_filter('upload_mimes', 'werkstek_allow_svg_uploads');

function werkstek_fix_svg_filetype_check($data, $file, $filename, $mimes) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (in_array($extension, ['svg', 'svgz'], true)) {
        return [
            'ext' => $extension,
            'type' => 'image/svg+xml',
            'proper_filename' => $data['proper_filename'] ?? false,
        ];
    }

    return $data;
}
add_filter('wp_check_filetype_and_ext', 'werkstek_fix_svg_filetype_check', 10, 4);

/**
 * ACF 6.8.7+ validates image fields with wp_get_image_mime(), which only
 * recognises raster images. Remove that specific validation error for SVGs;
 * WordPress and SVG Support still perform their normal upload checks and
 * sanitisation afterwards.
 */
function werkstek_allow_svg_in_acf_image_fields($errors, $file, $attachment, $field, $context) {
    if (! current_user_can('upload_files') || ! is_array($errors)) {
        return $errors;
    }

    $filename = '';

    if (! empty($attachment['name'])) {
        $filename = (string) $attachment['name'];
    } elseif (! empty($file['name'])) {
        $filename = (string) $file['name'];
    } elseif (! empty($file['filename'])) {
        $filename = (string) $file['filename'];
    }

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mime = (string) ($attachment['mime'] ?? $attachment['type'] ?? $file['mime_type'] ?? $file['type'] ?? '');

    if ($extension === 'svg' && ($mime === '' || in_array($mime, ['image/svg+xml', 'image/svg'], true))) {
        unset($errors['invalid_image']);
    }

    return $errors;
}
add_filter('acf/validate_is_image_attachment', 'werkstek_allow_svg_in_acf_image_fields', 10, 5);

function vite_theme_has_manifest() {
    return file_exists(VITE_THEME_MANIFEST_PATH);
}

function vite_theme_is_dev_server_available() {
    static $available = null;

    if ($available !== null) {
        return $available;
    }

    $cache_key = 'vite_theme_dev_server_available';
    $cached = get_transient($cache_key);

    if ($cached !== false) {
        $available = $cached === 'yes';
        return $available;
    }

    $response = wp_remote_get(VITE_THEME_DEV_CLIENT_PATH, [
        'timeout' => 1,
    ]);

    $available = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;

    set_transient($cache_key, $available ? 'yes' : 'no', 5);

    return $available;
}

// Unified asset enqueuing
add_action('wp_enqueue_scripts', function() {
    $theme_version = wp_get_theme()->get('Version');
    $module_handles = ['vite-client', 'werkstek-thema-scripts'];
    $use_dev_server = vite_theme_is_dev_server_available();

    if (!$use_dev_server && vite_theme_has_manifest()) {
        // Production: Load from manifest
        $manifest = json_decode(file_get_contents(VITE_THEME_MANIFEST_PATH), true);
        if (is_array($manifest)) {
            foreach ($manifest as $key => $value) {
                $file   = $value['file'];
                $ext    = pathinfo($file, PATHINFO_EXTENSION);
                $handle = 'vite-' . sanitize_title($key);
                if ($ext === 'css') {
                    wp_enqueue_style($handle, VITE_THEME_ASSETS_DIR . '/' . $file, [], $theme_version);
                } elseif ($ext === 'js') {
                    wp_enqueue_script($handle, VITE_THEME_ASSETS_DIR . '/' . $file, [], $theme_version, true);
                    $module_handles[] = $handle;
                }
            }
        }
    } elseif ($use_dev_server) {
        // Development: Load from Vite dev server
        wp_enqueue_script('vite-client', VITE_THEME_DEV_CLIENT_PATH, [], null, true);
        wp_enqueue_script('werkstek-thema-scripts', VITE_THEME_DEV_SCRIPTS_PATH, [], null, true);
        wp_enqueue_style('werkstek-thema-styles', VITE_THEME_DEV_STYLES_PATH, [], null);
    }

    if ((is_post_type_archive('kantoorruimte') || is_tax('locatie')) && WERKSTEK_GOOGLE_MAPS_API_KEY) {
        wp_enqueue_script(
            'werkstek-google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode(WERKSTEK_GOOGLE_MAPS_API_KEY) . '&v=weekly',
            [],
            null,
            true
        );
    }

    add_filter('script_loader_tag', function ($tag, $handle) use ($module_handles) {
        if (in_array($handle, $module_handles, true)) {
            return str_replace('<script ', '<script type="module" ', $tag);
        }

        return $tag;
    }, 10, 2);
});
