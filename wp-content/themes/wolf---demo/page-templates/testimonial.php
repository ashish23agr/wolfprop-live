<?php
/**
  Template Name: Testimonials template
 */
get_header();
get_sidebar('filter');
while (have_posts()) : the_post();
    ?>

    <div class="testimonials-outer">
        <div class="container">
            <div class="testimonials-sec">
                <div class="page-title">        	
                    <?php
                    the_content();
                    $args = array('post_type' => 'testimonial', 'posts_per_page' => -1, 'post_status' => 'publish');
                    $testimonials = get_posts($args);
                    $i = 0;
                    foreach ($testimonials as $post) :
                        setup_postdata($post);
                        ?>
                        <div class="testimonials-content-bx <?php echo ($i % 2 == 0) ? "testimonials-bg" : ""; ?>">
                        <?php the_content(); ?>
                            <span><?php the_title(); ?></span>
                        </div>
                        <?php
                        $i++;
                    endforeach;
                    wp_reset_postdata();
                    ?>       
                </div>
            </div>            
        </div>
    </div>   
<?php endwhile; ?>
<?php 
get_sidebar('openhouse');
get_footer(); ?>