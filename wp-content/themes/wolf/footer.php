<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>
<style>
        
        .section-wrapper {
            display: flex;
            align-items: stretch;
            justify-content: center;
            margin: 20px auto;
            width: 100%;
            background-color: #f0f8ff;            
            background-image: url('https://wolfprop.24livehost.com/wp-content/uploads/2024/12/dotted-world-map.webp');
            background-size: contain;
            background-position: left;
            background-repeat: no-repeat;
        }
        .text-section {
            flex: 1;            
            color: #000;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .text-section p {
            margin: 0;
            font-size: 20px;
            line-height: 1.6;            
        }
        .form-section {
            flex: 0.7;
            display: flex;
            flex-direction: column;
            align-items: left;
            background-color: #fff;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-section h3 {
            margin: 0 0 15px;
            font-size: 24px;
            color: #333;
        }
        .form-section .icon {            
            font-size: 40px;
            color: #b71c1c;
            margin-bottom: 15px;
        }
        .form-section form {
            width: 100%;
        }
        .form-section input {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-weight: 400;
            margin-top: 20px;
        }
        .form-section input[type=submit] {
            margin-top: 0;
            background-color: #b71c1c;
            width: 116px;
            height:40px;
            border-radius:5px;
            font-weight: 500;
            font-size: 17px;
            text-transform: uppercase;
        }
        .form-section input[type=submit]:hover{
            background-color: #000;
        }
        .form-section button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #b71c1c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-section button:hover {
            background-color: #a31616;
        }
        .wpformcode p {
        color: black;
        font-size: 18px;
        margin-bottom: 5px;
        font-weight: 600;
        }
        .wpformcode p br{display:none}

.map-form-section{background-color:#f5f5f5;  padding:15px 0; position: relative;     background-position: center; z-index: 1;}
.map-form-section:after{position: absolute; left:0; top:0; width: 100%; height:100%; background-color: rgba(245, 245, 245, 0.95); content:''; z-index: -1;}
.map-form-section .text-section{ padding: 0;}
.map-form-section .text-section p{ font-size: 24px; line-height: 1.3; margin: 0; font-weight: 600;}
.map-form-section .row{display: flex; align-items: center; flex-wrap:wrap;}
.map-form-section span.wpcf7-not-valid-tip{font-size:12px;     margin-top: -10px; margin-bottom: 10px;}
.map-form-section .wpcf7-response-output.wpcf7-display-none.wpcf7-validation-errors {color: red; padding:0; margin-top: -18px;}
.map-form-section div.wpcf7-validation-errors, div.wpcf7-acceptance-missing {    border: 0;}
.map-form-section div.wpcf7-response-output {margin: 1px;  border: 0;}
.map-form-section .wpcf7-response-output.wpcf7-display-none.wpcf7-mail-sent-ok {color: green; padding:0;     margin-top: -18px;}

@media screen and (max-width:1199px) {

.map-form-section .text-section p{font-size:21px;}

}

@media screen and (max-width:991px) {

.form-section{padding:25px}
.map-form-section .text-section p{font-size:19px;}

}

@media screen and (max-width:767px) {

.map-form-section {padding:40px 0}
.map-form-section .text-section{margin-bottom:30px;}

}
@media (min-width: 768px) and (max-width: 965px) {
  .form-section input {
    width: 100%;
  }
}

    </style>
<footer>
    <div class="section-wrapper map-form-section">

        <div class="container">
            <div class="row">
               <div class="col-sm-6">
                    <div class="text-section">
                        <p>Be the first to know about our new listings.<br>Add your cell number to receive instant updates straight to your phone.</p>
                    </div>
                </div>   
                <div class="col-sm-6">
                    <div class="form-section">
                        <div class="icon"><img src="https://wolfprop.24livehost.com/wp-content/uploads/2024/12/mobile-hand-icon.png" alt="img"></div>
                            <div class="wpformcode">                   
                            <?php echo do_shortcode('[contact-form-7 id="108477" title="Home Page1"]'); ?>              
                        </div>
                    </div>
                </div> 
            </div>

            

        </div>

    </div>


  <div class="footer-section1">
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-xs-12">
          <h4>CONTACT</h4>
          <p><?php the_field('footer_contact','option');?>
          </p>
          <p class="address"><?php the_field('header_address','option');?></p>
          <p class="phone"><?php the_field('contact_phone','option');?></p>
          <p class="email"><a href="mailto:<?php the_field('header_mail','option');?>"><?php the_field('header_mail','option');?></a></p>
        </div>
        <div class="col-sm-4 col-xs-12 quick_links">
          <h4>QUICK LINKS</h4>
          <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'main-nav' ) ); ?>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12 pull-right">
		<?php dynamic_sidebar( 'sidebar-twitter' ); ?>          
        </div>
      </div>
    </div>
  </div>
  
  <div class="footer-section2">
  		<div class="container">
        	<div class="row">
            	<div class="col-sm-4 col-xs-12">
           	    <img src="<?php the_field('footer_logo', 'option'); ?>" alt=""> 
                </div>
                <div class="col-sm-6 col-md-5 col-xs-12">
           	    <h4>ABOUT US</h4>
                <p><?php the_field('footer_about_us','option');?></p>
                <div class="mls-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/image1.png" alt=""> </div>
                </div>
                
                <div class="footer-social-section pull-right">
                	<h4>Follow US</h4>
                    <div class="footer-social">
                    <a target="_blank" href="<?php the_field('facebook_link', 'option'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a> 
                    <a target="_blank" href="<?php the_field('twitter_link', 'option'); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a> 
                    <a target="_blank" href="<?php the_field('instagram_link', 'option'); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
  </div>
  
  <div class="copyright-section">
  	<div class="container">
    	<div class="row">
        	 <div class="col-md-4 col-sm-12 col-xs-12">
             	<p><?php the_field('copyright', 'option'); ?></p>
             </div>
             <div class="col-md-5 col-sm-12 col-xs-12">
             	<p>Site design and development <a target="_blank" href="<?php the_field('site_design_and_development', 'option'); ?>">Creative Solutions</a></p>
             </div>
              <div class="col-md-3 col-sm-12 col-xs-12">
              	<?php wp_nav_menu( array( 'theme_location' => 'footer') ); ?>
              </div>
        </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
<script type="text/javascript">
jQuery(function($) {
	$(".head-top ul li a").click(function(){
		$(this).addClass("active");
	});

	$(".footer-social a").click(function(){
		$(this).addClass("active");
	});

	$(".copyright-section a").click(function(){
		$(this).addClass("active");
	});

	//open house
	$(".open_house").click(function(){
	    if($("#open_house_listing").length > 0)
	    {
	        $('html, body').animate({
	            scrollTop: $("#open_house_listing").offset().top
	        }, 1000);
	    } 
	    else
	    {
	        window.location.replace("<?php echo get_site_url()."#open_house_listing"; ?>");
	    }    
	});
});
</script>


<script>
// jQuery(document).ready(function($) {
//     $('input[name="your-cell-number"]').on('keypress', function(e) {
//         var phoneNumber = $(this).val();
//         var key = e.key;

//         // Allow only numbers and the plus sign
//         if (!/[\d+]/.test(key) && key !== "Backspace") {
//             e.preventDefault(); // Prevent non-numeric keys or invalid characters
//         }

//         // Ensure max length of 15 characters
//         if (phoneNumber.length >= 15 && key !== "Backspace") {
//             e.preventDefault(); // Prevent entering more than 15 characters
//         }
//     });
// });

    jQuery(document).ready(function($) {
    // Add an empty error message container to the DOM
    var errorMessage = $('<div class="error-message" style="color: red; font-size: 12px; display: none;">Only numbers and the "+" sign are allowed, with a maximum of 15 characters.</div>');
    $('input[name="your-cell-number"]').after(errorMessage); // Position the error message after the input field

    $('input[name="your-cell-number"]').on('keypress', function(e) {
        var phoneNumber = $(this).val();
        var key = e.key;

        // Hide error message initially on each key press
        errorMessage.hide();

        // Allow only numbers and the plus sign
        if (!/[\d+]/.test(key) && key !== "Backspace") {
            e.preventDefault(); // Prevent non-numeric keys or invalid characters
            errorMessage.text("Only numbers and the '+' sign are allowed.").show(); // Show error
        }

        // Ensure max length of 15 characters
        if (phoneNumber.length >= 15 && key !== "Backspace") {
            e.preventDefault(); // Prevent entering more than 15 characters
            errorMessage.text("Maximum 15 characters are allowed.").show(); // Show error
        }
    });

    // Optional: clear error message when the user starts correcting
    $('input[name="your-cell-number"]').on('input', function() {
        if ($(this).val().length <= 15) {
            errorMessage.hide();
        }
    });
});


</script>




<style>
	a.active 
	{
		opacity: 0.6;
	}			
</style>
</body>
</html>