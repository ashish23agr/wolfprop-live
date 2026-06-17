<?php
/**
 * The Header template for our theme
 *

 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wolf Properties</title>
<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/jasny-bootstrap.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/jquery-ui.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/style.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/dev-style.css" rel="stylesheet">

<link href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/selectize.css" rel="stylesheet">
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-2.2.3.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/owl.carousel.min.js"></script>

<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.selectbox.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/custom-design.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/selectize.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jasny-bootstrap.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jQuery.equalHeights.js"></script>
<?php wp_head(); ?>
</head>
<body>
<header>
  <div class="head-top">
    <div class="container">
      <ul>
        <li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:<?php the_field('header_mail', 'option'); ?>"><?php the_field('header_mail', 'option'); ?></a></li>
        <li><a href="https://www.google.com/maps/place/Wolf+Properties/@40.5946755,-73.9715661,17z/data=!3m1!4b1!4m5!3m4!1s0x89c244587695281b:0xc2ce82b17c3c5345!8m2!3d40.5946714!4d-73.9693774" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/map_icon.png" alt="map"> <?php the_field('header_address', 'option'); ?></a></li>
      </ul>
    </div>
  </div>
  <div class="container head-midd">
    <div class="row">
      <div class="col-sm-6 col-xs-12 logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php the_field('header_logo', 'option'); ?>" alt=""></a></div>
      <div class="col-sm-6 col-xs-12 social-section">
        <ul>
          <li class="call"><span>Call us:</span> <?php the_field('header_contect', 'option'); ?> <i class="fa fa-phone" aria-hidden="true"></i></li>
          <li class="social"> <a target="_blank" href="<?php the_field('facebook_link', 'option'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a> <a target="_blank" href="<?php the_field('twitter_link', 'option'); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a> <a target="_blank" href="<?php the_field('instagram_link', 'option'); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a> </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="navigation-outer">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9 col-xs-4">
        <a class="toggleMenu" href="#"></a>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'main-nav menu' ,'container'=> false) ); ?>
          <!--ul class="main-nav">
            <li class="current"><a href="#">Home</a></li>
            <li><a href="#">PROPERTIES / LISTINGS</a></li>
            <li><a href="#">AGENTS</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
          </ul-->
        </div>
        <?php if(!is_user_logged_in()){?>
        <div class="col-sm-4 col-md-3 col-xs-8 register-button"> <a href="<?php echo get_site_url(); ?>/login/">SIGN IN</a> </div>
        <?php } else {?>
         <div class="dropdown col-sm-4 col-md-3 col-xs-8">
          <?php global $current_user; get_currentuserinfo(); ?>
		  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">WELCOME! <?php echo strtoupper($current_user->user_firstname); ?>
		  <span class="caret"></span></button>
		  <ul class="dropdown-menu">
		  <?php 
		  		$current_user = wp_get_current_user();
		  ?>
		  	<li><a href="<?php echo get_site_url(); ?>/add-rental-listing/" class="real_url">Maintenance</a></li>
		    <!-- <li><a href="<?php //echo get_site_url(); ?>/user/<?php //echo $current_user->user_login;?>/?profiletab=main&um_action=edit" class="real_url">Edit Profile</a></li> -->
		    <li>
		    	<a href="<?php echo get_site_url().'/user/?profiletab=main&um_action=edit'; ?>" class="real_url">Edit Profile</a>
		    </li>
		    <li><a href="<?php echo get_site_url(); ?>/account/" class="real_url">My Account</a></li>
		    <li><a href="<?php echo wp_logout_url( home_url() ); ?>" class="real_url">Logout</a></li>
		  </ul>
		</div>
        <?php } ?>
      </div>
    </div>
  </div>
</header>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/navigation.js"></script> 
<?php //wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>