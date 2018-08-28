<?php
 /**
  * The template for acorntv landing page.
  */

get_header(); ?>

<style>
/* BASE CSS */
h1{font-size: 2em; color: rgb(85, 85, 85); font-weight: 600;}
h2{font-size: 1.5em; color: rgb(85, 85, 85); font-weight: 600;}
h3{font-size: 1.17em; color: rgb(85, 85, 85); font-weight: 600;}
h4{font-size: 1.12em; color: rgb(85, 85, 85); font-weight: 600;}
h5{font-size: .83em; color: rgb(85, 85, 85); font-weight: 600;}
h6{font-size: .75em; color: rgb(85, 85, 85); font-weight: 600;}

.page-main{position: relative; /*top: 45px;*/ background-color: #fff; color: rgb(85, 85, 85);}
article{padding: 70px 0;}
header{color: rgb(85, 85, 85);}
</style>

<?php
    while ( have_posts() ) :
        the_post();
?>

<section class="page-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="col-sm-12">
                <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->

                <?php
                if ( (!post_password_required() || !is_attachment() || has_post_thumbnail()) ):
                    if ( is_singular() ) :
                ?>

                <div class="post-thumbnail">
                        <?php the_post_thumbnail(); ?>
                </div><!-- .post-thumbnail -->

                <?php else : ?>

                <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                        <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
                </a>

                <?php endif; // End is_singular()
                endif;
                ?>

                <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages( array(
                                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                                'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
                                'separator'   => '<span class="screen-reader-text">, </span>',
                        ) );
                        ?>
                </div><!-- .entry-content -->

        </article><!-- #post-page## -->
    </div>
</section>
<?php endwhile; ?>

<?php get_footer(); ?>
