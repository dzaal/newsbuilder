<?php
// Header
get_header();

// Main content
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <header class="page-header">
            <h1 class="page-title">Newsbuilder News Archive</h1>
        </header><!-- .page-header -->

        <?php if ( have_posts() ) : ?>
            <div class="post-list">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header><!-- .entry-header -->

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div><!-- .entry-summary -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                <?php endwhile; ?>
            </div><!-- .post-list -->
        <?php else : ?>
            <p>No newsletters found.</p>
        <?php endif; ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
// Footer
get_footer();
