<?php
get_header(); 
get_sidebar('filter');
while ( have_posts() ) : the_post(); ?>
<div class="midd-content">
  <div class="container">
  	 <?php the_content(); ?>
   </div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>