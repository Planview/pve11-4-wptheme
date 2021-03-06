<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Planview Enterprise 11.3
 */
get_header('head'); ?>

    <a class="skip-link sr-only" href="#content"><?php _e( 'Skip to content', 'pve_113' ); ?></a>

    <nav id="header" class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <header class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand bg-size" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
            </header>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <?php wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'nav navbar-nav navbar-right',
                        'fallback_cb' => false,
                        'depth' => 2,
                        'walker' => new The_Bootstrap_Nav_Walker()
                    ) ); ?>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>

    <a href="http://www.surveygizmo.com/s3/1955258/2015-Planview-Enterprise-11-3-Product-Launch-Event" id="survey" target="_blank">
        <span class="survey-text"><?php _e( 'Take the Survey', 'pve_113' ); ?></span>
        <span class="fa fa-bullhorn"></span>
    </a>

    <!-- <div id="content" class="site-content"> -->
