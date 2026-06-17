<?php
/**
*Template Name: Contact us template
*/
get_header(); 
get_sidebar('filter');
$apiKey= get_field('google_map_api_key','options');

while ( have_posts() ) : the_post(); ?>
<?php 

$location = get_field('address');

if( !empty($location) ):
?>
<div class="contact-map">
	<div id="map" style="width:100%;height:400px;"></div>
</div>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo $apiKey; ?>"></script>

<script>
	function initialize(){
	     var myLatlng = new google.maps.LatLng(<?php echo $location['lat'];?>,<?php echo $location['lng']; ?>);
	     var myOptions = {
	         zoom: 14,
	         center: myLatlng,
	         mapTypeId: google.maps.MapTypeId.ROADMAP,
	         scrollwheel: false,
	         navigationControl: false,
	         mapTypeControl: false,
	         scaleControl: false,
	         draggable: false,
	         }
	      map = new google.maps.Map(document.getElementById("map"), myOptions);
	      var marker = new google.maps.Marker({
	          position: myLatlng, 
	          map: map,
	      title:"Fast marker"
	     });
	} 

	google.maps.event.addDomListener(window,'load', initialize);
</script>
<?php endif; ?>
<div class="midd-content">
  <div class="container">
  <div class="page-title">
          <h1>Contact Us</h1>
        </div>
    <div class="row about-section">
      <div class="col-sm-6 col-xs-12 pull-right"> 
      <img src="<?php the_post_thumbnail_url( 'full' );  ?>" alt="Wolf property Contact" /> 
      	<div class="contact-address">
        	<address>
            	<strong><?php echo get_bloginfo();?></strong><br>
                <?php the_field('contact_address', 'option');?>
            </address>
            <p><label>phone:</label> <?php the_field('contact_phone', 'option');?></p>
            <p><label>fax:</label> <?php the_field('contact_fax', 'option');?></p>
            <p><label>email:</label> <a href="mailto:<?php the_field('header_mail', 'option');?>"><?php the_field('header_mail', 'option');?></a></p>
            <div class="contact-social">
            <label>Follow Us:</label>
            <a target="_blank" href="<?php the_field('facebook_link', 'option');?>"><i class="fa fa-facebook" aria-hidden="true"></i></a> 
            <a target="_blank" href="<?php the_field('twitter_link', 'option');?>"><i class="fa fa-twitter" aria-hidden="true"></i></a> 
            <a target="_blank" href="<?php the_field('instagram_link', 'option');?>"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        
        <div class="contact-detail margin-top30">
          <h2>Have a Question? We Can Help You</h2>
          <p>We're always glad to hear from you! <br>
            Simply fill out the form below, and we will contact you as soon as possible.</p>
        </div>
        <div class="contct-form">          
            <?php echo do_shortcode('[contact-form-7 id="108" title="Contact"]');?>          
        </div>
      </div>
    </div>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>