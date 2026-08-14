<?php
$theme_uri = get_template_directory_uri();
$brand_icon = $theme_uri . '/resources/images/branding/werkstek-icon-01.png';
$phone_icon = $theme_uri . '/resources/images/solar_phone-outline.svg';
$mail_icon = $theme_uri . '/resources/images/solar_letter-outline.svg';
?>

<section class="py-16 sm:py-20 lg:py-28">
    <div class="mx-auto max-w-4xl px-5 text-center sm:px-6">
        <img
            src="<?php echo esc_url($brand_icon); ?>"
            alt=""
            class="mx-auto h-20 w-20 object-contain opacity-100"
            loading="lazy"
            aria-hidden="true"
        >

        <h2 class="mx-auto mt-6 max-w-2xl text-4xl font-bold leading-tight text-slate-900 sm:text-5xl lg:text-[3.25rem]">
            Weten wat de mogelijkheden zijn?
        </h2>

        <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a
                href="tel:0850290598"
                class="inline-flex min-h-14 items-center gap-4 rounded-full bg-slate-900 py-2 pl-2 pr-7 text-base font-medium text-white transition hover:bg-slate-800"
            >
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-orange-accent">
                    <img src="<?php echo esc_url($phone_icon); ?>" alt="" class="h-6 w-6 brightness-0 invert" aria-hidden="true">
                </span>
                <span>085 0290 598</span>
            </a>

            <a
                href="mailto:info@werkstek.nl"
                class="inline-flex min-h-14 items-center gap-4 rounded-full bg-surface-200 py-2 pl-2 pr-7 text-base font-medium text-slate-900 transition hover:bg-white"
            >
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface">
                    <img src="<?php echo esc_url($mail_icon); ?>" alt="" class="h-6 w-6" aria-hidden="true">
                </span>
                <span>info@werkstek.nl</span>
            </a>
        </div>
    </div>
</section>
