<?php
$sectie_titel = get_sub_field('titel') ?: 'Ontdek onze bruisende community';
$button_tekst = get_sub_field('button_tekst') ?: 'Over onze community';
$button_link = get_sub_field('button_link');
$toon_button_onderin = get_sub_field('toon_button_onderin');
$show_bottom_button = ! in_array($toon_button_onderin, [false, 0, '0'], true);
$button_url = '#';
$button_target = '_self';

if (is_array($button_link)) {
    $button_url = $button_link['url'] ?? '#';
    $button_target = $button_link['target'] ?? '_self';
} elseif (is_string($button_link) && $button_link !== '') {
    $button_url = $button_link;
}

$community_videos_query = new WP_Query([
    'post_type' => 'community_video',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
]);

$community_videos = [];

if ($community_videos_query->have_posts()) {
    while ($community_videos_query->have_posts()) {
        $community_videos_query->the_post();
        $video = werkstek_get_community_video_data(get_the_ID());

        if ($video) {
            $community_videos[] = $video;
        }
    }

    wp_reset_postdata();
}

if (empty($community_videos)) {
    return;
}

$component_id = 'community-videos-' . wp_unique_id();
$default_active_index = 0;
?>

<section class="py-14 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 sm:px-6">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-[2rem] font-bold leading-tight text-slate-900 sm:text-5xl">
                <?php echo nl2br(esc_html($sectie_titel)); ?>
            </h2>
        </div>

        <div class="no-scrollbar -mx-5 mt-10 overflow-x-auto px-5 pb-2 sm:-mx-6 sm:px-6 lg:mx-0 lg:mt-12 lg:px-0" data-community-video-tabs="<?php echo esc_attr($component_id); ?>" role="tablist" aria-label="Community videos">
            <div class="flex w-max min-w-full snap-x snap-mandatory flex-nowrap justify-center gap-4">
                <?php foreach ($community_videos as $index => $video): ?>
                <?php $is_active = $index === $default_active_index; ?>
                <button
                    type="button"
                    id="<?php echo esc_attr($component_id . '-tab-' . $video['id']); ?>"
                    data-community-video-tab
                    data-panel-id="<?php echo esc_attr($component_id . '-panel-' . $video['id']); ?>"
                    class="<?php echo $is_active ? 'border-orange-accent bg-[#FCF8F3]' : 'border-surface bg-[#FCF8F3]/80'; ?> inline-flex h-20 min-w-[110px] shrink-0 snap-start items-center justify-center rounded-[1.5rem] border px-6 transition hover:border-orange-accent hover:bg-[#FCF8F3]"
                    role="tab"
                    aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                    aria-controls="<?php echo esc_attr($component_id . '-panel-' . $video['id']); ?>"
                >
                    <?php if ($video['logo']): ?>
                        <img src="<?php echo esc_url($video['logo']); ?>" alt="<?php echo esc_attr($video['title']); ?>" class="<?php echo $is_active ? 'max-h-14 w-25 object-contain opacity-100 grayscale-0 transition duration-200' : 'max-h-14 w-25 object-contain opacity-25 grayscale transition duration-200'; ?>">
                    <?php else: ?>
                        <span class="text-sm font-semibold text-slate-700"><?php echo esc_html($video['title']); ?></span>
                    <?php endif; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-8 lg:mt-10" data-community-video-panels="<?php echo esc_attr($component_id); ?>">
            <?php foreach ($community_videos as $index => $video): ?>
                <?php $is_active = $index === $default_active_index; ?>
                <div
                    id="<?php echo esc_attr($component_id . '-panel-' . $video['id']); ?>"
                    data-community-video-panel
                    class="<?php echo $is_active ? '' : 'hidden'; ?>"
                    role="tabpanel"
                    aria-labelledby="<?php echo esc_attr($component_id . '-tab-' . $video['id']); ?>"
                >
                    <div class="community-video-player mx-auto max-w-4xl overflow-hidden rounded-[2.25rem] bg-slate-900 shadow-[0_28px_70px_rgba(15,23,42,0.16)]" data-community-video-player>
                        <?php if ($video['video_type'] === 'youtube' && $video['youtube_embed_url']): ?>
                            <div class="lg:aspect-video aspect-[16/12]">
                                <iframe
                                    src="<?php echo esc_url($video['youtube_embed_url']); ?>"
                                    title="<?php echo esc_attr($video['title']); ?>"
                                    class="h-full w-full"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                    loading="lazy"
                                ></iframe>
                            </div>
                        <?php elseif ($video['video_type'] === 'embed' && $video['video_embed']): ?>
                            <div class="aspect-[16/10] [&_iframe]:h-full [&_iframe]:w-full [&_video]:h-full [&_video]:w-full [&_video]:object-cover">
                                <?php echo $video['video_embed']; ?>
                            </div>
                        <?php elseif ($video['video_type'] === 'file' && $video['video_url']): ?>
                            <video controls preload="metadata" poster="<?php echo esc_url($video['poster']); ?>" class="aspect-[16/10] h-full w-full object-cover">
                                <source src="<?php echo esc_url($video['video_url']); ?>">
                            </video>
                        <?php elseif ($video['video_url']): ?>
                            <div class="aspect-[16/10]">
                                <?php echo wp_oembed_get($video['video_url'], ['width' => 1200]) ?: '<iframe src="' . esc_url($video['video_url']) . '" class="h-full w-full" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>'; ?>
                            </div>
                        <?php endif; ?>
                        <button type="button" class="community-video-player__overlay" data-community-video-play aria-label="<?php echo esc_attr(sprintf(__('Speel video af: %s', 'werkstek-thema'), $video['title'])); ?>">
                            <img src="<?php echo esc_url($video['placeholder']); ?>" alt="" class="community-video-player__placeholder">
                            <span class="community-video-player__gradient" aria-hidden="true"></span>
                            <span class="community-video-player__button">
                                <span class="community-video-player__icon" aria-hidden="true">
                                    <svg class="scale-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M9 7.5v9l7-4.5-7-4.5Z"></path>
                                    </svg>
                                </span>
                                <span><?php esc_html_e('Video afspelen', 'werkstek-thema'); ?></span>
                            </span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($show_bottom_button): ?>
            <div class="mt-10 flex justify-center">
                <a
                    href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"
                    rel="<?php echo $button_target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                    class="inline-flex items-center gap-3 rounded-full bg-dark-main px-6 py-2.5 pr-3 text-base font-medium text-white transition hover:bg-orange-accent"
                >
                    <span><?php echo esc_html($button_tekst); ?></span>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#FCF8F3] text-dark-main shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2">
                            <path d="M7 17 17 7"></path>
                            <path d="M9 7h8v8"></path>
                        </svg>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
