<?php
/**
Template Name: Agent Listing template
*/
get_header(); 
get_sidebar('filter');
while ( have_posts() ) : the_post(); ?>
<div class="midd-content">
  <div class="container">
    <div class="page-title">
      <h1><?php the_title();?></h1>
    </div>
    
    <?php 
    	$user = isset($_GET['user']) ? $_GET['user'] : "";
    	if(empty($user)){
    ?>   
    <div class="agent-listing-section">
    	<div class="agent-listing-top">
        	<div class="listing-image" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/agent-listing-image.jpg);">&nbsp;</div>
            <div class="listing-left" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/agent-listing-bg.jpg);">
           	  <?php the_content(); ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row agent-listing">        
        <?php 
	        $count_args  = array(
	        		'role'      => 'agent',
	        		'fields'    => 'all_with_meta',
	        		'number'    => 999999
	        );
	        $user_count_query = new WP_User_Query($count_args);
	        $user_count = $user_count_query->get_results();
	        
	        // count the number of users found in the query
	        $total_users = $user_count ? count($user_count) : 1;
	        
	        // grab the current page number and set to 1 if no page number is set
	        $page = isset($_GET['p']) ? $_GET['p'] : 1;
	        
	        // how many users to show per page
	        $users_per_page = 10;
	        
	        // calculate the total number of pages.
	        $total_pages = 1;
	        $offset = $users_per_page * ($page - 1);
	        $total_pages = ceil($total_users / $users_per_page);
	        
	        $args  = array(
	        		// search only for Authors role
	        		'role'      => 'agent',
                                'meta_key' => 'order',
	        		// order results by display_name
	        		'orderby'   => 'meta_value',
	        		// return all fields
	        		'fields'    => 'all_with_meta',
	        		//'number'    => $users_per_page,
	        		//'offset'    => $offset // skip the number of users that we have per page
	        );
	        
	        // Create the WP_User_Query object
	        $wp_user_query = new WP_User_Query($args);
	        
	        // Get the results
	        $authors = $wp_user_query->get_results();
	        
	        // check to see if we have users
	        if (!empty($authors))
	        {
	        	$i = 0;
	        	foreach ($authors as $author)
	        	{
	        		$author_info = get_userdata($author->ID);	        		
        	?>
    		<div class="col-sm-4 col-xs-6 margin-top30">
       	    <a href="?user=<?php echo $author_info->user_login; ?>"><?php echo get_avatar( $author->ID, 374 ); /* http://codex.wordpress.org/Function_Reference/get_avatar */ ?></a>
            <div class="agent-description">
            	<h2><a href="?user=<?php echo $author_info->user_login; ?>"><?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?></a></h2>
                <div class="agent-property"><?php echo get_user_meta( $author->ID, 'company_name',true);?></div>
                <ul>
                	<?php $phone = get_user_meta( $author->ID, 'mobile_number',true);
                	if(!empty($phone)){
                	?>
                	<li>
                    <label>Call</label>
                    <?php echo $phone;?>
                    </li>
                    <?php } ?>
                	<li>
                    <label>Email</label>
                     <a href="mailto:<?php echo $author_info->user_email; ?>"><?php echo $author_info->user_email; ?></a>
                    </li>
                </ul>
            </div>
            </div>  		
           <?php	$i++;}	        	
	        } ?>	       	
      </div>
    	</div>
    </div>
    <?php }
    		else 
    	  {	
    	  	$author = get_user_by( 'login', $user );    
    	  	$author_info = get_userdata($author->ID);
    ?>
    
    <div class="agent-detail-section">
    	<div class="agent-detail-image"><?php echo get_avatar( $author->ID, 190 );?></div>
        <div class="agent-detail">
        	<div class="agent-description">
            	<h2><?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?></h2>
                <div class="agent-property"><?php echo get_user_meta( $author->ID, 'company_name',true);?></div>
                <p><?php echo get_user_meta( $author->ID, 'about',true);?></p>
                <ul>
                	<?php $phone = get_user_meta( $author->ID, 'mobile_number',true);
                	if(!empty($phone)){
                	?>
                	<li>
                    <label>Call</label>
                    <?php echo $phone;?>
                    </li>
                    <?php } ?>
                	<li>
                    <label>Email</label>
                     <a href="mailto:<?php echo $author_info->user_email; ?>"><?php echo $author_info->user_email; ?></a>
                    </li>
                	<li>
                    <label>Social</label>
                     <div class="agent-social-link">
                     <?php 
                     	$facebook = get_user_meta( $author->ID, 'facebook',true);
                     	$twitter = get_user_meta( $author->ID, 'twitter',true);
                     	if(!empty($facebook))
                     	{
                     		echo '<a href="'.$facebook.'" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>';	
                     	}

                     	if(!empty($twitter))
                     	{
                     		echo '<a href="'.$twitter.'" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>';
                     	}	
                     ?>                     	
                     	<a href="mailto:<?php echo $author_info->user_email; ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a>                     
                     </div>
                    </li>
                </ul>
            </div>
            <a href="javascript:void(0)" class="btn-default send-button" data-toggle="modal" data-target="#myModal" ><i class="fa fa-envelope" aria-hidden="true"></i> Send a message</a>       
        	</div>		
    	</div>
    </div>
    
    <!-- Modal -->
	<div class="modal fade send_message" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content request_info_wrap">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>	   
	        <h4 class="modal-title" id="myModalLabel">Send a message</h4>     
	      </div>
	      <div class="modal-body">
	        <div class="agent-form">
	            <form action="request_info" id="request_property_info" method="post">
	              <div class="input-field">
	                <input name="name" id="name" type="text"  placeholder="Your Name *">
	              </div>
	              <div class="input-field">
	                <input name="phone" id="phone" type="text"  placeholder="Phone *">
	              </div>
	              <div class="input-field">
	                <input name="email" id="email" type="text"  placeholder="Email *">
	              </div>
	              <div class="input-field">
	                <textarea name="message" id="message" cols="" rows="" placeholder="Message *"></textarea>
	              </div>
	              <input name="agent" type="hidden" value="<?php echo $author->ID;?>">	              
	              <button type="submit" class="request_info btn btn-default">Request Info</button>
	              <div class="status"></div>
	            </form>
	          </div>
	      </div>	      
	    </div>
	  </div>
	</div>
    	
    <?php }?>    
  </div>
</div>
<?php endwhile; ?>
<?php get_footer(); ?>
<script>
$(document).ready(function() {
	jQuery.validator.addMethod("noSpace", function(value, element) {
		return value.trim() !== ""; // Disallow spaces
	}, "Space is not allowed"); // Custom error message
	jQuery.validator.addMethod("validate_email",function(value,element){
		
		if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
			return true;
		} else {
			return false;
		}
	  
		
	},"Please ensure email is valid.");
	jQuery.validator.addMethod("phoneGlobal", function(phone_number, element) {
		phone_number = phone_number.replace(/\s+/g, ""); 
		return this.optional(element) || phone_number.match(/^(\+?\d{1,3}[-\s]?)?\(?\d{3}\)?[-\s]?\d{3}[-\s]?\d{4}$/);
	}, "Please specify a valid phone number");
	jQuery("#request_property_info").validate({
		ignore: [],
		rules: {				
			name: {
				required: true,
				minlength: 1,
				maxlength: 20,
				noSpace: true,
				

			},
			
			email: {
				required: true,
				email: true,
				validate_email: true,
				noSpace: true,
				maxlength: 50,


			},
			
			phone: {
				required: true,	
				noSpace: true,
				phoneGlobal: true


			},
			message: {
				required: true,		
				noSpace: true,
				maxlength: 50,


			},
			

			
		},
		messages: {
			name: {
				required: "This is a required field",
				minlength: "Minimum 1 character required",
				
			},
			
			email: {
				required: "This is a required field",
				email: "Please ensure email is valid.",					
				validate_email: "Please ensure email is valid.",					
			},
			
			phone: {
				required: "This is a required field",					
			},
			message: {
				required: "This is a required field",					

			},
			
			
		},
		submitHandler: function(form) {
			$(".status").html("<img alt='loading..' src='<?php echo get_template_directory_uri(); ?>/images/loader.gif'>");

				
			var form = jQuery('#request_property_info').serialize();
			form += '&action=request_property_info';

			var ajaxurl='<?php echo admin_url('admin-ajax.php') ?>';
			jQuery.ajax({
				type:"post",
				url: ajaxurl,
				data: form,
				success:function(data){		
					var jsonResponse = JSON.parse(data);
					// Access the message from the JSON response
					var message = jsonResponse.message;
					var status = jsonResponse.success;
	
					$(".status").html(message);
					if(status){
						$('#request_property_info').find("input, textarea").val("");					

					}
				}
			});	
				
		}
				
	});

$("#send-button").click(function(){
	console.log("hello");
	$('#request_property_info').find("input, textarea").val("");
	$(".status").val("");
});
$('.gravatar').each(function(){ 
	$(this).removeAttr('width')
	$(this).removeAttr('height');
});
});
$(function(){ $('.agent-listing').equalHeights(); });
</script>