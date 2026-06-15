<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <?php if (have_rows('componenten')): ?>
            <?php get_template_part('template-parts/flexibele-componenten'); ?>
        <?php else: ?>
            <section class="py-14 lg:py-24">
                <div class="mx-auto max-w-5xl px-5 sm:px-6">
                    <h1 class="text-4xl font-bold text-slate-900 sm:text-5xl">
                        <?php the_title(); ?>
                    </h1>

                    <div class="prose prose-slate mt-8 max-w-none">
                        <?php the_content(); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
