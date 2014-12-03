<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Planview Enterprise 11.3
 */

get_header(); ?>

    <div class="jumbotron jumbo-header">
        <div class="container">
            <h1>
                <?php echo get_field( 'pv_event_resources_archive_title', 'option' ) ?: __( 'Resource Library', 'pve_113' ); ?>
            </h1>
            <?php echo get_field( 'pv_event_resources_archive_intro', 'option' ) ?: ''; ?>
        </div>
    </div>
    <?php if ( have_posts() ) : $sorted_posts = pve_113_library_sort(); $activeSet = false; ?>
        <nav class="resources-tabs">
            <div class="container">
                <ul class="nav nav-pills nav-justified">
                    <?php foreach ($sorted_posts as $release => $release_posts) : ?>
                        <li<?php echo $activeSet ? '' : ' class="active"'; $activeSet = true; ?>><a href="#<?php echo $release_posts['__object']->slug; ?>" role="tab" data-toggle="tab"><?php echo $release ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
    <?php endif; ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if ( have_posts() ) : $sorted_posts = pve_113_library_sort(); $activeSet = false; ?>
                <div class="tab-content">
                <?php foreach ($sorted_posts as $release => $release_posts) : ?>
                    <div class="release tab-pane<?php echo $activeSet ? '' : ' active'; $activeSet = true; ?>" id="<?php echo $release_posts['__object']->slug; ?>">
                        <?php if ( isset( $release_posts['__featured'] ) ) :
                            $post = $release_posts['__featured'];
                            setup_postdata( $post ); ?>
                            <div class="featured-resource">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-8"><img src="http://placehold.it/800x450" alt="" class="img-responsive"></div>
                                        <div class="col-sm-4">
                                            <h2><span class="small">Featured:</span><br /> <?php the_title(); ?></h2>
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="resource-listing">
                            <div class="container">
                                <div class="row">
                                    <?php foreach ($release_posts as $type => $type_posts) :
                                        if ( in_array( $type, array( '__object', '__featured' ) ) ) continue; ?>
                                        <!-- <div class="type"> -->
                                            <?php foreach ($type_posts as $index => $post) :
                                                if ('__object' === $index) continue;
                                                setup_postdata( $post ); ?>
                                                <div class="col-sm-4 resource pane">
                                                    <div class="panel">
                                                        <div class="panel-body">
                                                        <img src="http://placehold.it/800x450" alt="" class="img-responsive center-block">
                                                        <h4><?php the_title(); ?></h4>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque optio cumque omnis perferendis ut possimus vero, sapiente explicabo nesciunt dolorum reiciendis at, quibusdam delectus, ea, quo dolorem aut ipsum ex.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <!-- </div> -->
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
