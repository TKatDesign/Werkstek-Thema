<?php
$site_name = get_bloginfo('name') ?: 'Werkstek';
$skyline_banner = get_template_directory_uri() . '/resources/images/landscape.svg';
$footer_logo = get_template_directory_uri() . '/resources/images/branding/werkstek-logo.png';
$steden = ['Amsterdam', 'Den haag', 'Rotterdam', 'Utrecht', 'Haarlem'];
$stekjes = ['Computerweg 1', 'Het Ravelijn 50', 'Papiermolen 26', 'Databankweg 20', 'Simon Stevinweg 27'];
$werkstek_links = [
    'Kantoorruimte huren' => get_post_type_archive_link('kantoorruimte') ?: '#',
    'Over ons' => '#',
    'Community' => '#',
    'Voor verhuurders' => '#',
    'Contact' => '#',
];
$socials = [
    'Instagram' => '#',
    'Facebook' => '#',
    'LinkedIn' => '#',
];
?>

<footer class="relative mt-20 bg-[#EDD1B5] text-slate-900">
    <div class="pointer-events-none relative z-10 h-32 overflow-hidden bg-surface">
        <img src="<?php echo esc_url($skyline_banner); ?>" alt="" class="block h-full w-full translate-y-[2px] object-cover object-bottom" aria-hidden="true">
    </div>

    <div class="relative mx-auto max-w-7xl px-5 pb-10 pt-16 sm:px-6 lg:pt-20">
        <div class="flex justify-center">
            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($site_name); ?> - home">
                <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr($site_name); ?>" class="h-auto w-[220px] object-contain sm:w-[250px]">
            </a>
        </div>

        <div class="mt-16 grid gap-12 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Populairste steden</h2>
                <ul class="mt-6 space-y-4 text-lg text-slate-700">
                    <?php foreach ($steden as $stad): ?>
                        <li><?php echo esc_html($stad); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">Populairste stekjes</h2>
                <ul class="mt-6 space-y-4 text-lg text-slate-700">
                    <?php foreach ($stekjes as $stekje): ?>
                        <li><?php echo esc_html($stekje); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">Werkstek</h2>
                <ul class="mt-6 space-y-4 text-lg text-slate-700">
                    <?php foreach ($werkstek_links as $label => $url): ?>
                        <li>
                            <a href="<?php echo esc_url($url); ?>" class="transition hover:text-orange-500">
                                <?php echo esc_html($label); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">Contact</h2>
                <div class="mt-6 space-y-4 text-lg text-slate-700">
                    <p><a href="mailto:info@werkstek.nl" class="transition hover:text-orange-500">info@werkstek.nl</a></p>
                    <p><a href="tel:0850290598" class="transition hover:text-orange-500">085 - 0290598</a></p>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <?php foreach ($socials as $platform => $url): ?>
                        <a
                            href="<?php echo esc_url($url); ?>"
                            aria-label="<?php echo esc_attr($platform); ?>"
                            class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-surface text-slate-900 transition hover:-translate-y-0.5 hover:text-orange-500"
                        >
                            <?php if ($platform === 'Instagram'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="5"></rect>
                                    <circle cx="12" cy="12" r="3.5"></circle>
                                    <circle cx="17.5" cy="6.5" r="1"></circle>
                                </svg>
                            <?php elseif ($platform === 'Facebook'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                                    <path d="M13.5 21v-7h2.6l.4-3h-3V9.1c0-.9.3-1.6 1.7-1.6h1.5V4.8c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.3H8v3h2.5v7z"></path>
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                                    <path d="M6.94 8.5H4V20h2.94zm.2-3a1.72 1.72 0 1 0-3.44 0 1.72 1.72 0 0 0 3.43 0ZM20 20h-2.93v-5.6c0-1.34-.03-3.05-1.86-3.05-1.86 0-2.14 1.45-2.14 2.95V20H10.1V8.5h2.82v1.57h.04c.39-.74 1.35-1.52 2.78-1.52 2.98 0 3.53 1.96 3.53 4.51z"></path>
                                </svg>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-16 flex flex-col gap-4 border-t border-slate-300/60 pt-8 text-base text-slate-600 md:flex-row md:items-center md:justify-between">
            <p>Copyright Werkstek &copy; <?php echo esc_html(wp_date('Y')); ?></p>
            <div class="flex flex-wrap items-center gap-6">
                <a href="#" class="transition hover:text-orange-500">Algemene voorwaarden</a>
                <a href="#" class="transition hover:text-orange-500">Privacy verklaring</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
