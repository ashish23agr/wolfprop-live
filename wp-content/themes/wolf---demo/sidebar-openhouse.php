<div class="testimonials-outer">
    <div class="container">
        <?php
        $args = array(
            'post_type' => array('sale-listing'),
            'post_status' => 'publish',
            'posts_per_page' => -1,                    
            'meta_query' => array(
                'relation' => 'AND', // Optional, defaults to "AND"
                array(
                    'key' => 'open_house',
                    'value' => true,
                    'compare' => '=',
                ),
                array(
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => 'end_date',
                    'value' => date('Y-m-d'),
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                )
            )
        );
        $open_houeses = new WP_Query($args);
        
      
           
           ?>
            <div class="house-listing-sec1" id="open_house_listing">
               <?php 
                 if ( $open_houeses->have_posts() ) { 
				 $i= 1;
                    while ( $open_houeses->have_posts() ) : $open_houeses->the_post(); 
                      //     echo 'sdkjsdfhsdkfhdskfj';
                 //echo '<pre>'; print_r($open_houeses); echo '</pre>';     
                        $open_house_true    = get_post_meta(get_the_ID(), 'open_house', true); 
                        $thumbnail          = get_the_post_thumbnail_url(get_the_ID(),'grid-thumb' );
                        $sale_price         = get_field( "listing_price" );
                        $state              = get_field( "state" );    
                        $start_time         = get_field( "start_time" );  
                        $end_time           = get_field("end_time");
                        $day                = get_field("day");
                        //$open_house_start_date  = get_post_meta(get_the_ID(),'open_house_start_date');
                         $open_house_end_date    = get_post_meta(get_the_ID(), 'end_date', true);
    
        $todayDate = date('Y/m/d');
        $checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
         $open_house_stare_date  = get_post_meta(get_the_ID(), 'open_house_start_date', true);
        $open_house_end_date    = get_post_meta(get_the_ID(), 'end_date', true);
        $open_house_start_time  = get_post_meta(get_the_ID(), 'start_time', true);
        $open_house_end_time    = get_post_meta(get_the_ID(), 'end_time', true);
        $todayDate = date('Y/m/d');
        $checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
           if($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate){
            if($i==1){
               ?>
                <style>.testimonials-outer {padding: 30px 0 40px; display: inline-block; width: 100%;}</style>
               <div class="house-tittle ">
                    <span>Check out our latest</span>
                    <h1>OPEN HOUSE LISTINGS</h1>
                </div>
            <?php  } }  $i++; endwhile;   } ?>
                <div class="owl-carousel owl-theme">
                <?php  
                   if ( $open_houeses->have_posts() ) { 
                    while ( $open_houeses->have_posts() ) : $open_houeses->the_post(); 
                   //     echo 'sdkjsdfhsdkfhdskfj';
                 //echo '<pre>'; print_r($open_houeses); echo '</pre>';     
                        $open_house_true    = get_post_meta(get_the_ID(), 'open_house', true); 
                        $thumbnail          = get_the_post_thumbnail_url(get_the_ID(),'grid-thumb' );
                        $sale_price         = get_field( "listing_price" );
                        $state              = get_field( "state" );    
                        $start_time         = get_field( "start_time" );  
                        $end_time           = get_field("end_time");
                        $day                = get_field("day");
                        //$open_house_start_date  = get_post_meta(get_the_ID(),'open_house_start_date');
                         $open_house_end_date    = get_post_meta(get_the_ID(), 'end_date', true);
    
        $todayDate = date('Y/m/d');
        $checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
         $open_house_stare_date  = get_post_meta(get_the_ID(), 'open_house_start_date', true);
        $open_house_end_date    = get_post_meta(get_the_ID(), 'end_date', true);
        $open_house_start_time  = get_post_meta(get_the_ID(), 'start_time', true);
        $open_house_end_time    = get_post_meta(get_the_ID(), 'end_time', true);
        $todayDate = date('Y/m/d');
        $checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
                        // echo 'Today date: '.$todayDate = date('Y/m/d').'<>';
                        // echo 'Check Last Date: '.$checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
                        // die;
      
                        if($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate){
                            $hide_show_sec = 'show';
                            $start_month= date('M', strtotime($open_house_stare_date)); 
                            $start_date = date('dS', strtotime($open_house_stare_date));
                            $end_month  = date('M', strtotime($open_house_end_date)); 
                            $end_date   = date('dS', strtotime($open_house_end_date)); 
                            $start_time = ltrim(date('h:ia', strtotime($open_house_start_time)), 0);
                            $end_time   = ltrim(date('h:ia', strtotime($open_house_end_time)), 0); ?>
                            <div class="item">
                                <div class="house-list-bx">
                                    <div class="house-content-list">
                                        <div class="list-img">
                                            <?php
                                                if(!empty($thumbnail)){
                                                    echo '<img src="'.$thumbnail.'" alt="thumbnail">';
                                                }else{
                                                    echo '<img src="'.get_template_directory_uri().'/images/thumbnail-385-260.jpg" alt="thumbnail">';
                                                }
                                            ?>
                                            
                                        </div>
                                        <div class="price-sec">
                                            <span>$<?php echo number_format($sale_price); ?></span>
                                        </div>
                                        <div class="list-content-bx">
                                            <div class="custom-home-open-house-outer">
                                                <div class="home-inner-open-house-outer">
                                                    <span class="open">Open House:
                                                        <?php echo $day.', '.$start_month.' '.$start_date.', '; ?>
                                                        <?php echo $start_time.' - '.$end_time; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <h2><?php the_title(); ?></h2>
                                            <div class="property_type_open">
                                                <?php 
                                                $property_type = get_post_type();                                    
                                                if($property_type == 'sale-listing'){ ?>   
                                                    <span class="type">FOR SALE</span>
                                                <?php }else{ ?>    
                                                    <span class="type">FOR RENT</span>
                                                <?php } ?>
                                                
                                            </div>
                                            <p>
                                                <?php echo the_excerpt_max_charlength(100); ?>
                                                <a href="<?php the_permalink(); ?>">[more]</a>
                                            </p>
                                            <div class="list-loction pull-left">
                                                <span><?php the_field( "city" ); ?>,  <?php echo $state->name; ?></span>
                                            </div>
                                            <div class="sharing-icon pull-right">
                                                <a href="<?php the_permalink(); ?>"><i class="fa fa-share-alt">&nbsp;</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                <?php   }   else {  ?> <!--<style>.house-listing-sec1{display:none;}</style>--><?php }  endwhile; ?>
            </div><?php } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            loop: true,
            items: 3,
            navigation : false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }

            }
        })
        $('.owl-item').css("width","375px");
    })
</script> 