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

<footer>
    <div class="footer-newsletter" id="newsletter_subscribe">
  
    <div class="container text-center">	
      
        <?php 
       
        if(!empty($_SESSION['textingconfirm']) && $_SESSION['textingconfirm'] == true){
            echo '<img src="'.get_template_directory_uri().'/images/newsletter-confirm.png" alt="texting_confirmation">'; 
            echo '<p style="margin-top:10px;">You have successfully subscribed to our newsletter or requested text message notifications for new sales listings. For email subscribers, you will receive a confirmation email shortly. Please follow the link contained within the email to confirm your subscription. We recommend checking your spam folder if the email takes more than 15 minutes to appear in your mailbox.</p>';
            unset($_SESSION['textingconfirm']);
        }
        elseif(!empty ($_SESSION['subscribe']) && $_SESSION['subscribe'] == true)
        {
           echo '<div class="text-center midd-content newsletter-signup">
                  <img src="'.get_template_directory_uri().'/images/newsletter-confirm.png" alt="texting_confirmation">
                <p style="margin-top:10px;">You have successfully subscribed to our newsletter or requested text message notifications for new sales listings. For email subscribers, you will receive a confirmation email shortly. Please follow the link contained within the email to confirm your subscription. We recommend checking your spam folder if the email takes more than 15 minutes to appear in your mailbox.</p>
            </div>';
           unset($_SESSION['subscribe']);
        }  
        elseif(!empty ($_SESSION['confirm']) && $_SESSION['confirm'] == true)
        {
            echo '<img src="'.get_template_directory_uri().'/images/newsletter-confirm.png" alt="newsletter_confirmation">';            
            unset($_SESSION['confirm']);            
        }
        else
        {
        ?>
         <div class="row">
          <label class="col-md-3 col-sm-4 col-xs-12" for="">SIGN UP FOR UPDATES</label>
          <div class="col-md-9 col-sm-8 col-xs-12">
            <?php               
		dynamic_sidebar( 'signup-widget' );                
                if(!empty($_SESSION['newslettererr'])){
                    echo $_SESSION['newslettererr'];
                    unset($_SESSION['newslettererr']);
                }
            ?>
            <script type="text/javascript">
                $("#submitverification").prop('disabled', false);
                $("#newsletter :input[type=submit]").prop('disabled', false);
                //<![CDATA[
                if (typeof newsletter_check !== "function") {
                    window.newsletter_check = function (f) {                        
                        for (var i=1; i<20; i++) {
                        if (f.elements["np" + i] && f.elements["np" + i].required && f.elements["np" + i].value == "") {
                            alert("");
                            return false;
                        }
                        }
                        if (f.elements["ny"] && !f.elements["ny"].checked) {
                            alert("You must accept the privacy statement");
                            return false;
                        }
                        
                        var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
                        var intRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;  
                        if (re.test(f.elements["ne"].value)) {
                             $( "#newsletter" ).submit();
                        }
                        else if(intRegex.test(f.elements["ne"].value))
                        {
                            $(f.elements["ne"]).addClass('loading_click');
                            $("#newsletter :input[type=submit]").prop('disabled', true);
                            var data = {
                                action : 'texting-signup',
                                text : f.elements["ne"].value
                            }; 
                            
                            $.ajax({
                                type: 'POST',
                                url:  <?php echo json_encode( admin_url('admin-ajax.php') ) ?>,
                                data: data,               
                                success: function(response) {                                     
                                    var result = jQuery.parseJSON(response);
                                    if(result.status == true)
                                    {                                        
                                        $("#newsletter").remove();
                                        $(".response").html(result.verify);
                                    }
                                    else
                                    {                                        
                                        $(".response").html(result.message);
                                    } 
                                    $(f.elements["ne"]).removeClass('loading_click');
                                    $("#newsletter :input[type=submit]").prop('disabled', false);
                                }
                            });
                            return false;
                        }    
                        else
                        {
                            alert("Please enter correct email address");
                            return false;
                        }    
                        return true;
                    }
                }
                
                $('body').on('click', '#submitverification', function() {                
                    $("#verfication_code").addClass('loading_click');
                    $(this).prop('disabled', true);
                    $.ajax({
                        type: 'POST',
                        url:  <?php echo json_encode( admin_url('admin-ajax.php') ) ?>,
                        data: $("#verifyphone").serialize(),               
                        success: function(response) {                            
                            var result = jQuery.parseJSON(response);
                            if(result.status == true)
                            {                                
                                $("#newsletter_subscribe .container").html(result.message);
                            }
                            else
                            {
                                $(".response1").html(result.message);
                            }    
                            $("#verfication_code").removeClass('loading_click');
                            $(this).prop('disabled', false);
                        }
                    });
                    return false;
                });
                //]]>
                
                $('body').on('click','#resendotp',function(event ){
                    event.preventDefault();
                    $("#verfication_code").addClass('loading_click');
                    $("#submitverification").prop('disabled', true);
                    var data = {
                                action : 'texting-signup',
                                text : $("#verification_number").val()
                            }; 
                            
                        $.ajax({
                            type: 'POST',
                            url:  <?php echo json_encode( admin_url('admin-ajax.php') ) ?>,
                            data: data,               
                            success: function(response) {                                    
                                var result = jQuery.parseJSON(response);
                                if(result.status == true)
                                {                                    
                                    
                                }
                                else
                                {
                                    $(".response").html(result.message);
                                }
                                $("#verfication_code").removeClass('loading_click');
                                $("#submitverification").prop('disabled', false);
                            }
                        });
                });
            </script>
          </div>
        </div>
        <?php } ?>
      
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

</script>


<style>
	a.active 
	{
		opacity: 0.6;
	}			
</style>
</body>
</html>