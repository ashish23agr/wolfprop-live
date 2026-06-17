<?php

/**
Template Name: Home page template
*/
get_header(); 
get_sidebar('filter');
?>
  
<?php 

$args=array(
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'post_type' => array('sale-listing','rental-listing'),
		'meta_query' => array(
				'relation' => 'AND',
				array(
						'key' => 'featured',
						'value' => 1,
						'compare' => '='
				),
				array(
						'key' => 'listing_end',
						'value' => date('Y-m-d'),
						'compare' => '>=', 
                                                  'type'    => 'DATE' 
				)
		)
);
$my_query = new WP_Query($args);

//echo '<pre>'; print_r($my_query);
?>
<div class="banner-section">
	<?php if ( $my_query->have_posts() ) {  ?>
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<?php $count = 0; ?>
		<ol class="carousel-indicators">
			<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
		  <li data-target="#myCarousel" data-slide-to="<?php echo $count ?>" class="<?php echo ($count==0)?'active':'' ?>"></li>
		  <?php $count++; endwhile; ?>
		</ol>

		<!-- Wrapper for slides -->
		<?php 
		$count = 0;
		
		echo '<div class="carousel-inner" role="listbox">';
		while ( $my_query->have_posts() ) : $my_query->the_post();
          	
			$thumbnail = get_the_post_thumbnail_url(get_the_ID(),'home-thumb' );	          	
			
			?>
				
				<div class="item feature_slide <?php echo ($count==0)?'active':'' ?>" >
					<a href="<?php echo get_the_permalink() ?>">
						<?php 
						if(!empty($thumbnail))
							echo '<img src="'.$thumbnail.'" alt="thumbnail">';
						else
							echo '<img src="'.get_template_directory_uri().'/images/thumbnail-385-260.jpg" alt="thumbnail">';
						?>
					</a>
				</div>	
			
		<?php
		$count++;
        endwhile;
		?>
		</div>
		<?php if($count > 1){ ?>
		<!-- Left and right controls -->
		<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
		  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		  <span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
		  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		  <span class="sr-only">Next</span>
		</a>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<?php 
get_sidebar('openhouse');


get_footer(); ?>