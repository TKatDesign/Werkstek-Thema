<?php
$afbeelding = get_sub_field('afbeelding');
$subtitel = trim((string) get_sub_field('subtitel'));
$titel = trim((string) get_sub_field('titel')) ?: 'Neem direct contact op!';
$tekst = trim((string) get_sub_field('tekst'));
$telefoonnummer = trim((string) get_sub_field('telefoonnummer'));
$emailadres = trim((string) get_sub_field('e-mailadres'));
$afbeelding_data = function_exists('werkstek_normalize_acf_image')
    ? werkstek_normalize_acf_image($afbeelding, 'large')
    : ['url' => is_array($afbeelding) ? ($afbeelding['url'] ?? '') : '', 'alt' => ''];
$afbeelding_url = $afbeelding_data['url'] ?? '';
$afbeelding_alt = $afbeelding_data['alt'] ?: $titel;
$phone_icon_url = get_template_directory_uri() . '/resources/images/solar_phone-outline.svg';
$mail_icon_url = get_template_directory_uri() . '/resources/images/solar_letter-outline.svg';
$phone_href = $telefoonnummer ? 'tel:' . preg_replace('/[^0-9+]/', '', $telefoonnummer) : '';
$email_href = $emailadres ? 'mailto:' . sanitize_email($emailadres) : '';
?>

<section class="bg-white py-8 lg:py-14">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-6 lg:px-12">
        <div class="relative lg:pb-16">
            <div class="relative overflow-hidden rounded-[1.25rem] bg-slate-900">
                <?php if ($afbeelding_url): ?>
                    <img
                        src="<?php echo esc_url($afbeelding_url); ?>"
                        alt="<?php echo esc_attr($afbeelding_alt); ?>"
                        class="absolute inset-0 h-full w-full object-cover"
                        loading="eager"
                    >
                <?php else: ?>
                    <div class="absolute inset-0 flex items-center justify-center bg-slate-700 px-6 text-center text-sm font-medium text-white/70">
                        Voeg een contact hero-afbeelding toe
                    </div>
                <?php endif; ?>

                <div
                    class="absolute inset-0"
                    style="background: linear-gradient(to top, rgba(15, 41, 58, 1) 0%, rgba(15, 41, 58, 0.4) 100%);"
                    aria-hidden="true"
                ></div>

                <div class="relative z-10 mx-auto flex min-h-[520px] max-w-3xl flex-col items-center justify-center px-5 pb-32 pt-20 text-center text-white sm:px-8 lg:min-h-[520px] lg:pb-32">
                    <?php if ($subtitel): ?>
                        <span class="inline-flex rounded-full bg-green-accent px-4 py-2 text-sm font-regular leading-none text-white">
                            <?php echo esc_html($subtitel); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="<?php echo $subtitel ? 'mt-6' : ''; ?> text-3xl font-medium leading-tight text-white sm:text-5xl lg:text-[3.5rem]">
                        <?php echo esc_html($titel); ?>
                    </h1>

                    <?php if ($tekst): ?>
                        <div class="mt-5 max-w-2xl text-lg font-regular leading-8 text-white sm:text-2xl sm:leading-9">
                            <?php echo wp_kses_post(wpautop($tekst)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($telefoonnummer || $emailadres): ?>
                <div class="relative z-20 mx-auto -mt-16 grid max-w-4xl gap-5 px-5 sm:px-8 lg:absolute lg:inset-x-0 lg:bottom-0 lg:mt-0 lg:grid-cols-2">
                    <?php if ($telefoonnummer): ?>
                        <a href="<?php echo esc_url($phone_href); ?>" class="group flex min-h-[102px] lg:min-h-[130px] items-center gap-5 rounded-[1.25rem] border border-slate-200 bg-white p-5 lg:p-8 text-slate-900 shadow-[0_18px_40px_rgba(15,23,42,0.12)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.16)]">
                            <span class="inline-flex h-12 w-12 lg:h-16 lg:w-16 shrink-0 items-center justify-center rounded-full bg-[#f3f2f1]">
                                <img src="<?php echo esc_url($phone_icon_url); ?>" alt="" class="h-5 w-5 lg:h-7 lg:w-7 object-contain" aria-hidden="true">
                            </span>
                            <span>
                                <span class="block text-lg lg:text-[20px] font-medium leading-none text-slate-400">Bellen</span>
                                <span class="mt-2 block text-lg lg:text-2xl font-bold leading-tight text-slate-900"><?php echo esc_html($telefoonnummer); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if ($emailadres): ?>
                        <a href="<?php echo esc_url($email_href); ?>" class="group flex min-h-[102px] lg:min-h-[130px] items-center gap-5 rounded-[1.25rem] border border-slate-200 bg-white p-5 lg:p-8 text-slate-900 shadow-[0_18px_40px_rgba(15,23,42,0.12)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.16)]">
                            <span class="inline-flex h-12 w-12 lg:h-16 lg:w-16 shrink-0 items-center justify-center rounded-full bg-[#f3f2f1]">
                                <img src="<?php echo esc_url($mail_icon_url); ?>" alt="" class="h-5 w-5 lg:h-7 lg:w-7 object-contain" aria-hidden="true">
                            </span>
                            <span>
                                <span class="block text-lg lg:text-[20px] font-medium leading-none text-slate-400">Mailen</span>
                                <span class="mt-2 block break-all text-lg lg:text-2xl font-bold leading-tight text-slate-900"><?php echo esc_html($emailadres); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
