<?php
/**
  Template Name: Client Forms
 */
get_header();
get_sidebar('filter');
global $post;
while (have_posts()) : the_post();
    ?>
    <div class="testimonials-outer client-forms">
        <div class="container">
            <div class="testimonials-sec client-sec">
                <div class="page-title">        	
                    <?php
                    the_content();
                    ?>
                    <div class="row">
                        <?php
                        if (get_query_var('paged')) {
                            $page = get_query_var('paged');
                        } else {
                            $page = 1;
                        }
                        // Variables
                        $row = 0;
                        $pdf_per_page = 12; // How many images to display on each page
                        $client_formsData = get_field('client_forms', $post->ID);
                        $total = count($client_formsData);
                        $pages = ceil($total / $pdf_per_page);
                        $min = ( ( $page * $pdf_per_page ) - $pdf_per_page ) + 1;
                        $max = ( $min + $pdf_per_page ) - 1;


                        //$client_formsData = get_field('client_forms', $post->ID);
                        foreach ($client_formsData as $client_formsDataEach) {
                            $row++;
                            // Ignore this image if $row is lower than $min
                            if ($row < $min) {
                                continue;
                            }
                            // Stop loop completely if $row is higher than $max
                            if ($row > $max) {
                                break;
                            }
                            ?>                            
                            <div class="col-sm-4 col-xs-6">
                                <div class="client-form-box">
                                    <span class="pdf-client">
                                        <a href="<?php echo $client_formsDataEach['pdf_file']; ?>" target="_blank">
                                            <img src="<?php echo get_bloginfo('template_url'); ?>/images/pdf-icon.png" alt="" /><br /><?php echo $client_formsDataEach['pdf_title']; ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>
                    <div class="pagination pagination-custom clearfix">
                            <?php
                            $big = 999999999; // need an unlikely integer
                            // Pagination
                            echo paginate_links(array(
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format' => '?page=%#%',
                                'current' => $page,
                                'total' => $pages,
                                'prev_text' => __('<<<'),
                                'next_text' => __('>>>'),
                            ));
                            ?>
                        </div>
                    <?php
                    wp_reset_postdata();
                    ?>       
                </div>
            </div>            
        </div>
    </div>   
<?php endwhile; ?>

<?php
get_sidebar('openhouse');
get_footer();
?>