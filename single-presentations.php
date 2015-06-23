<?php


get_header(); ?>

<?php if ( have_posts() ) : ?>
    <?php while( have_posts() ) : the_post(); ?>
        <div class="jumbotron jumbo-header">
            <div class="container">
                <h1>
                    <?php the_title(); ?>
                </h1>
                <?php the_field( 'pv_event_presentation_abstract' ); ?>
            </div>
        </div>
        <div class="video">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="thumbnail limelight-video-respond">
                            <?php echo preg_replace('/<param[^>]*name="wmode"[^>]*>/', '<param name="wmode" value="transparent"/>', get_field( 'pv_event_presentation_video' ) ); ?>
                        </div>
                        <?php if ( get_field( 'pve_113_show_limelight_failure_message' ) ) : ?>
                            <script>
                              jQuery(document).ready(function ($) {
                                $('.pre-limelight-load').show();
                              });

                              function limelightPlayerCallback(playerId, eventName, data) {
                                'use strict';

                                var $ = window.jQuery,
                                    id = $('.LimelightEmbeddedPlayerFlash').attr('id'),
                                    spoofMunchkin = function spoofer(queryString) {
                                      if ('undefined' !== typeof window.Munchkin) {
                                        window.Munchkin.munchkinFunction('visitWebPage', {
                                          url: global.location.href, params: queryString
                                        });
                                      } else {
                                        setTimeout(function () {
                                          spoofer(queryString);
                                        }, 1000);
                                      }
                                    };

                                if (eventName == 'onPlayerLoad' && (LimelightPlayer.getPlayers() == null || LimelightPlayer.getPlayers().length == 0)) {
                                  LimelightPlayer.registerPlayer(id);
                                }

                                if (eventName === 'onPlayerLoad') {
                                  $('.pre-limelight-load').remove();
                                  spoofMunchkin('playerload');
                                }
                              }
                            </script>
                            <p class="pre-limelight-load">This presentation uses live-streaming video over Limelight Networks. If the player does not load, please contact your company&rsquo;s IT department. You can also view a <a href="https://new-release.planview.com/presentations/planview-enterprise-11-3-2/">pre-recorded version of the presentation here</a>.</p>
                        <?php endif; ?>
                        <div class="qa-form">
                        <?php echo do_shortcode( get_field('pv_event_presentation_qa_form') ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="primary" class="content-area container">
            <div class="row">
                <div class="col-sm-9 comments">
                    <div class="presentation-comments">
                        <h3 class="section-title comments-title">Comments</h3>
                        <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                        ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <aside class="demo-callout">
                        <a href="/library/#tab-pve-11-4" class="btn btn-primary btn-block btn-lg" target="_blank">
                            Planview Enterprise Demo
                        </a>
                        <a href="/library/#tab-projectplace" class="btn btn-warning btn-block btn-lg" target="_blank">
                            Projectplace Demo
                        </a>
                    </aside>
                    <aside class="resource-list">
                        <h3 class="section-title">Resources</h3>
                        <?php pve_113_resource_list( get_field( 'pv_event_presentation_resources' ) ); ?>
                    </aside>
                    <aside class="survey-callout">
                        <h3 class="section-title">Survey</h3>
                        <a href="http://www.surveygizmo.com/s3/2192901/189d7eeb4531" class="btn btn-default btn-block btn-lg" target="_blank">
                            Take the Survey
                        </a>
                    </aside>
                </div>
            </div>
        </div><!-- #primary -->

    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer();
