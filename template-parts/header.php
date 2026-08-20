<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.classList.add('title-reveal-ready');</script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$site_name = get_bloginfo('name') ?: 'Werkstek';
$mail_icon = get_template_directory_uri() . '/resources/images/solar_mailbox-outline.svg';
$nav_mail_icon = get_template_directory_uri() . '/resources/images/solar_letter-outline.svg';
$phone_icon = get_template_directory_uri() . '/resources/images/solar_phone-outline.svg';
$language_icon = get_template_directory_uri() . '/resources/images/streamline-freehand-color_worldwide-web-location-pin.svg';
$menu_icon = get_template_directory_uri() . '/resources/images/streamline-freehand-color_menu-navigation-2.svg';
$custom_logo_id = get_theme_mod('custom_logo');
$custom_logo = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : null;
$custom_logo_url = $custom_logo[0] ?? '';
$kantoorruimte_archive_url = get_post_type_archive_link('kantoorruimte') ?: home_url('/kantoorruimte-huren/');
$locatie_search_items = function_exists('werkstek_get_locatie_search_items') ? werkstek_get_locatie_search_items() : [];
$primary_menu_html = wp_nav_menu([
    'theme_location' => 'primary',
    'menu' => 'Hoofdmenu',
    'container' => false,
    'menu_class' => 'flex flex-col gap-5',
    'fallback_cb' => false,
    'echo' => false,
    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
]);
?>
<header class="border-b border-[#E3D5C8]">
    <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-6 px-5 py-6 lg:px-8 xl:px-10">
        <div class="flex min-w-0 items-center gap-6 xl:gap-8">
            <?php if ($custom_logo_url) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex min-w-0 max-w-[220px] shrink items-center text-slate-900 xl:max-w-[260px]" aria-label="<?php echo esc_attr($site_name); ?>">
                    <img src="<?php echo esc_url($custom_logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" class="h-auto max-h-13 w-full object-contain object-left xl:max-h-12">
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex shrink-0 items-center gap-3 text-slate-900">
                    <span class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#FCF8F3] text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-11 w-11 fill-current">
                            <path d="M21 4h6v8h-6zM11 10h8v8h-8zM29 10h8v8h-8zM9 20h8v8H9zM20 20h8v8h-8zM31 20h8v8h-8zM9 31h8v8H9zM20 31h8v13h-8zM31 31h8v8h-8z"></path>
                            <path d="M15.5 3.5c4.3 0 8.1 2.2 10.2 5.5-4.9.2-9.1 2.7-11.9 6.4-2.3-1.4-3.8-4-3.8-6.9 0-2.8 2.4-5 5.5-5z" class="text-lime-500"></path>
                        </svg>
                    </span>
                    <span class="text-3xl font-bold xl:text-[3rem]"><?php echo esc_html($site_name); ?></span>
                </a>
            <?php endif; ?>

            <div class="hidden items-center gap-3 text-base font-semibold text-slate-700 lg:flex">
                <span>5/5</span>
                <span class="text-orange-500">&#9733;</span>
                <span class="font-medium text-slate-600">85 reviews</span>
            </div>
        </div>

        <form class="relative z-40 hidden max-w-sm flex-1 lg:flex xl:max-w-sm" action="<?php echo esc_url($kantoorruimte_archive_url); ?>" method="get" data-location-search>
            <label class="relative block w-full">
                <span class="sr-only">Zoek plaats of adres</span>
                <input
                    type="text"
                    name="locatie"
                    placeholder="Zoek plaats of adres..."
                    autocomplete="off"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-expanded="false"
                    aria-controls="header-kantoorruimte-locaties"
                    class="h-12 w-full rounded-full border border-gray-200 bg-[#FCF8F3] pl-5 pr-16 text-base text-slate-700 shadow-[0_10px_30px_rgba(15,23,42,0.08)] outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                >
                <div id="header-kantoorruimte-locaties" class="absolute left-0 right-0 top-[calc(100%+0.75rem)] hidden max-h-80 overflow-y-auto rounded-[1.25rem] bg-surface-200 px-2 py-3 shadow-[0_20px_50px_rgba(15,41,58,0.16)]" data-location-results role="listbox">
                    <?php foreach ($locatie_search_items as $locatie): ?>
                        <a href="<?php echo esc_url($locatie['url']); ?>" class="block rounded-xl px-4 py-2.5 text-base font-medium text-dark-main transition hover:text-orange-accent focus:bg-white/60 focus:text-orange-accent focus:outline-none" data-location-option data-name="<?php echo esc_attr($locatie['name']); ?>" data-slug="<?php echo esc_attr($locatie['slug']); ?>" role="option">
                            <?php echo esc_html($locatie['name']); ?>
                        </a>
                    <?php endforeach; ?>
                    <p class="hidden px-4 py-3 text-sm text-dark-main/60" data-location-empty>Geen locaties gevonden.</p>
                </div>
                <button
                    type="submit"
                    aria-label="Zoeken"
                    class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-orange-500 text-white transition hover:bg-orange-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                </button>
            </label>
        </form>

        <div class="flex shrink-0 items-center gap-3 xl:gap-6">
            <div class="hidden items-center gap-3 lg:flex">
                <div class="flex -space-x-3">
                    <div class="h-8 w-8 bg-[url(/resources/images/avatar1.png)] rounded-full bg-cover bg-center border-2 border-[#F7E8D9]"></div>
                    <div class="h-8 w-8 bg-[url(/resources/images/avatar2.png)] rounded-full bg-cover bg-center border-2 border-[#F7E8D9]"></div>
                    <div class="h-8 w-8 bg-[url(/resources/images/avatar3.png)] rounded-full bg-cover bg-center border-2 border-[#F7E8D9]"></div>
                </div>
                <a href="#" class="text-base font-medium text-slate-800 transition hover:text-orange-500">Community</a>
            </div>
                <div class="werkstek-header-actions flex shrink-0 items-center gap-1.5 sm:gap-2 xl:gap-2">
                    <a
                        href="tel:0850290598"
                        aria-label="Bel Werkstek"
                        class="werkstek-header-action inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-200 transition hover:bg-white sm:h-12 sm:w-12"
                    >
                        <img src="<?php echo esc_url($phone_icon); ?>" alt="" class="h-5 w-5 object-contain" aria-hidden="true">
                    </a>

                    <a
                        href="mailto:info@werkstek.nl"
                        aria-label="Mail Werkstek"
                        class="werkstek-header-action inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-200 transition hover:bg-white sm:h-12 sm:w-12"
                    >
                        <img src="<?php echo esc_url($nav_mail_icon); ?>" alt="" class="h-5 w-5 object-contain" aria-hidden="true">
                    </a>

                    <button
                        type="button"
                        aria-label="Taal of locatie"
                        class="werkstek-header-action inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-200 transition hover:bg-white sm:h-12 sm:w-12"
                    >
                        <img src="<?php echo esc_url($language_icon); ?>" alt="" class="werkstek-header-language-icon h-[26px] w-[26px] object-contain" aria-hidden="true">
                    </button>

                    <button
                        type="button"
                        aria-label="Open menu"
                        aria-controls="mobile-menu-panel"
                        aria-expanded="false"
                        data-menu-open
                        class="werkstek-header-action inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-200 transition hover:bg-white sm:h-12 sm:w-12"
                    >
                        <img src="<?php echo esc_url($menu_icon); ?>" alt="" class="werkstek-header-menu-icon h-[22px] w-[22px] object-contain" aria-hidden="true">
                    </button>
                 </div>
        </div>
    </div>
</header>
<div
    class="mobile-menu pointer-events-none fixed inset-0 z-50"
    data-menu-root
    aria-hidden="true"
>
    <button
        type="button"
        aria-label="Sluit menu"
        class="mobile-menu__overlay"
        data-menu-close
    ></button>

    <aside
        id="mobile-menu-panel"
        class="mobile-menu__panel flex h-full w-full max-w-[360px] flex-col bg-[#FCF8F3] shadow-[-20px_0_60px_rgba(15,23,42,0.18)] sm:max-w-[380px]"
        aria-modal="true"
        aria-label="Hoofdmenu"
        role="dialog"
        tabindex="-1"
    >
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-6">
            <span class="text-lg font-normal text-slate-900">Menu</span>
            <button
                type="button"
                aria-label="Sluit menu"
                class="mobile-menu__close inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-900 transition hover:bg-slate-100"
                data-menu-close
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current stroke-2">
                    <path d="M6 6l12 12"></path>
                    <path d="M18 6 6 18"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-10">
            <nav aria-label="Mobiel hoofdmenu" class="mobile-menu__nav">
                <?php if ($primary_menu_html) : ?>
                    <?php echo $primary_menu_html; ?>
                <?php else : ?>
                    <ul class="flex flex-col gap-5">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="font-normal text-orange-500">Home</a></li>
                    </ul>
                <?php endif; ?>
            </nav>
        </div>

        <div class="mobile-menu__contact border-t border-slate-200 px-6 py-2">
            <div class="mobile-menu__contact-row flex items-center gap-3 border-slate-200/80 py-4 text-lg font-medium text-slate-700">
                <img src="<?php echo esc_url($mail_icon); ?>" alt="" class="h-6 w-6 shrink-0" aria-hidden="true">
                <a href="mailto:info@werkstek.nl" class="transition hover:text-orange-500">info@werkstek.nl</a>
            </div>
        </div>
        <div class="mobile-menu__contact border-t border-slate-200 px-6 py-2">
            <div class="mobile-menu__contact-row flex items-center gap-3 py-4 text-lg font-medium text-slate-700">
                <img src="<?php echo esc_url($phone_icon); ?>" alt="" class="h-6 w-6 shrink-0" aria-hidden="true">
                <a href="tel:0850290598" class="transition hover:text-orange-500">085 - 0290598</a>
            </div>
        </div>
    </aside>
</div>
