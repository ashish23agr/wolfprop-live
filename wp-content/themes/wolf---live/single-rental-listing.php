<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); 
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); 
				$property_photos = get_field('property_photos');
			?>

			<div class="midd-content">
			  <div class="container">
			    <div class="page-title">
			      <h1>Properties / Listings</h1>
			    </div>
			    <div class="propert-detail-section">
			      <div class="row margin-top40">
			        <div class="col-sm-6 property-name">
			          <h2><?php the_title(); ?> <span><?php the_field( "city" ); ?>, <?php the_field( "zip_code" ); ?></span></h2>
			         <?php
							$status = get_post_meta(get_the_ID(), 'sold_under_contract', true);
							$property_type = get_post_type();
							   if ($property_type == 'sale-listing' and $status != 1) { ?>
								   <div class="for-sale">
									   <span>For Sale</span>
								   </div>
							   <?php }else if($status == 1){ ?>
									   <div class="for-sale">
									   <span><?php echo 'Sold'; ?></span>
								   </div>
						          <?php	}else{ ?>
								   <div class="for-sale">
									   <span>For Rent</span>
								   </div>
							   <?php } ?>
			        </div>
			        <div class="col-sm-6 property-price">
			        	<?php 
			        		$building_size=get_field( "building_size" );
                    		$sale_price = get_field( "listing_price" );
                    		$per_square = $sale_price/$building_size;
                    	?>
			          <h2>$<?php echo number_format($sale_price);?> <span>$<?php echo number_format($per_square);?> / sq. ft. </span></h2>
			        </div>
			      </div>
			      <div class="detail-banner"> 
			      <?php $thumbnail = get_the_post_thumbnail_url(get_the_ID(),'full');  
	                	if(!empty($thumbnail))
	                		echo '<img src="'.$thumbnail.'" alt="thumbnail">';
	                	else 
	                		echo '<img src="'.get_template_directory_uri().'/images/thumbnail-full.jpg" alt="thumbnail">';
	                            	
                    	$price = get_field( "listing_price" );                    	
                    	$per_square = $price/$building_size;
                    ?>
			        <div class="agent-form-section">
			          <div class="agent-detail">
			          <?php $agentID = get_post_meta( get_the_ID(), 'agent', true ); 
			          		$author_info = get_userdata($agentID);
			          ?>
			            <div class="agent-image"><?php echo get_avatar( $agentID, 65 ); ?></div>
			            <div class="agent-des">
			              <h2>Contact Agent</h2>
			              <p><i class="fa fa-user" aria-hidden="true"></i> <?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?></p>
			              <p><i class="fa fa-phone" aria-hidden="true"></i> <?php echo get_user_meta( $agentID, 'mobile_number',true);?></p>
			              <a href="<?php echo get_site_url();?>/agents?user=<?php echo $author_info->user_login; ?>">View my listing</a> </div>
			          </div>
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
			              <input name="agent" type="hidden" value="<?php echo $agentID;?>">
			              <input name=property type="hidden" value="<?php echo get_the_title();?>">
			              <button type="button" class="request_info btn btn-default">Request Info</button>
			              <div class="status"></div>
			            </form>
			          </div>
			        </div>
			        <div class="agent-detail-icon">
			        <?php 
			        	$unit = get_field('unit');
				        $address = get_field('address');
				        $city = get_field('city');
				        $zip_code = get_field('zip_code');
				        
				        $state_array = get_field('state');
				        $state = $state_array->name;
			        ?>
			          <ul>
			          	<?php if(!empty($property_photos)){?>
			            <li><a href="javascript:void(0)" onclick="lightbox(0)"><i class="fa fa-camera" aria-hidden="true"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($address) && !empty($zip_code))
			        	{?>
				            <li><a href="javascript:void(0)" id="google_map_view" data-toggle="modal" data-target="#myModal"><i class="fa fa-map" aria-hidden="true"></i></a></li>
				            <li><a href="javascript:void(0)" id="street_map_view" data-toggle="modal" data-target="#myModal"><i class="fa fa-street-view" aria-hidden="true"></i></a></li>
			            <?php } ?>
			          </ul>
			        </div>
			      </div>
			      <div class="clearfix"></div>
			      <div class="row">
			        <div class="col-sm-8 col-xs-12">
			          <div class="detail-block1">
			            <div class="block-title">
			              <h3>Description</h3>
			            </div>
			            <?php the_content();?>
			          </div>
                       
    
			          <div class="detail-block1 margin-top30">
			            <div class="block-title">
			              <h3>Detail</h3>
			              <span>Updated on <?php echo the_modified_date("F j,Y");?> at <?php echo the_modified_date("h:i a");?></span>
			            </div>
			            <?php 			            	
			            	$buildsize= get_field('building_size');
			            	$style = get_field('building_style');
			            	$heat = get_field('heat');
			            	$term = get_term( $style, $taxonomy );


			            ?>
			            <div class="blue-box">
			              <ul class="detail-style01">
			                <li>
			                  <label>Property ID :</label>
			                   <span><?php the_ID(); ?></span>
			                </li>
			                <li>
			                  <label>Style :</label>
			                  <span><?php echo $term->name;?></span>
			                </li>
			                <li>
			                  <label>Building Type :</label>
			                  <span><?php the_field('building_type');?></span>
			                </li>			                
			                <?php if(!empty($buildsize)){?>
			                <li>
			                  <label>Building Size :</label>
			                  <span><?php echo $buildsize;?> SqFt</span>
			                </li>
			                <?php }?>
			                <li>
			                  <label>Bedrooms :</label>
			                  <span><?php echo get_post_meta($post->ID, 'bedrooms', true); ?></span>
			                </li>			                
			                <li>
			                  <label>Bathrooms :</label>
			                  <span><?php echo get_post_meta($post->ID, 'bathrooms', true); ?></span>
			                </li>
			                <li>
			                  <label>Heat :</label>
			                  <span><?php echo $heat;?></span>
			                </li>
			                 <li>
			                  <label>Elevator :</label>
			                  <span><?php the_field('elevator');?></span>
			                </li>
			                <li>
			                  <label>Pets :</label>
			                  <span><?php the_field('pets');?></span>
			                </li>
			                <li>
			                  <label>Parking :</label>
			                  <span><?php the_field('parking');?></span>
			                </li>
			                <li>
			                  <label>Laundry :</label>
			                  <span><?php the_field('laundry');?></span>
			                </li>
			                <li>
			                  <label>Internet :</label>
			                  <span><?php the_field('internet');?></span>
			                </li>
			              </ul>
			            </div>
			            <div class="block-title2 margin-top30">
			              <h3>Listing Information</h3>
			            </div>
			            <ul class="detail-style01">			              
			              <li>
			                <label>Agent :</label>
			                <span><?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?></span>
			              </li>			              
			              <li>
			                <label>Listing Date :</label>
			                <span><?php echo date('m/d/Y',strtotime(get_field('listing_date')));?></span>
			              </li>
			              <li>
			                <label>Start Online :</label>
			                <span><?php echo date('m/d/Y',strtotime(get_field('start_online')));?></span>
			              </li>
			              <li>
			                <label>Listing Status :</label>
			                <span><?php the_field('listing_status');?></span>
			              </li>
			            </ul>
			          </div>
			          <div class="detail-block1 margin-top30">
			          
			          <?php
					  $ID = get_the_ID();
			          $neighbourhood = get_the_terms( $ID,'neighbourhood');	
						if(!empty($neighbourhood)){          
			          ?>
			            <div class="block-title">
			              <h3>Neighborhood</h3>
			            </div>
			            <ul class="detail-style02">
			              <?php 
			              	foreach ($neighbourhood as $value)
			              	{
								echo '<li>'.$value->name.'</li>';
			              	}	
			              ?>
			            </ul>			          
			          <?php } 
			          $features = get_the_terms( $ID,'property-feature');	
						if(!empty($features)){          
			          ?>
			            <div class="block-title margin-top30">
			              <h3>Property Features</h3>
			            </div>
			            <ul class="detail-style02">
			              <?php 
			              	foreach ($features as $feature)
			              	{
								echo '<li>'.$feature->name.'</li>';
			              	}	
			              ?>
			            </ul>
			            <?php } ?>	
			          </div>
			        </div>
			        <div class="col-sm-4 col-xs-12 listing-sidebar">
			          <?php get_sidebar('left-filter'); ?>
			        </div>
			      </div>
			    </div>
			  </div>
			</div>
			
			<!-- Google Map Modal -->
			<div class="modal fade normal_view" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			        
			      	<h4 class="modal-title" id="myModalLabel">Map View</h4>	
			      </div>
			      <div class="modal-body">
			        <div id="normal_view" style="width:100%;height:300px;"></div>
			      </div>			      
			    </div>
			  </div>
			</div>		
			<div class="modal fade street_view" id="myModal_street" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			        
			      	<h4 class="modal-title" id="myModalLabel">Street View</h4>
			      </div>
			      <div class="modal-body">
			        <div id="street_view" style="width:100%;height:300px;"></div>
			      </div>			      
			    </div>
			  </div>
			</div>			
			<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAtuDxHO18XkrgjV8LeaLk5EEm0fvRwU5s"></script>
			<script>
				function initialize(id,view=''){
				     var myLatlng = new google.maps.LatLng(<?php echo $latitude;?>,<?php echo $longitude; ?>);

				     if(view == "street")
				     {
				    	 var panorama = new google.maps.StreetViewPanorama(
				    	            document.getElementById(id), {
				    	              position: myLatlng,
				    	              pov: {
				    	                heading: 34,
				    	                pitch: 10
				    	              }
				    	            });
				    	        map.setStreetView(panorama);				    							
				     }
				     else
				     {            
					     var myOptions = {
					         zoom: 14,
					         center: myLatlng,
					         mapTypeId: google.maps.MapTypeId.ROADMAP
					         }
					      map = new google.maps.Map(document.getElementById(id), myOptions);
					      var marker = new google.maps.Marker({
					          position: myLatlng, 
					          map: map,
					      title:"Fast marker"
					     });
				     }	     
				} 
			
				//google.maps.event.addDomListener(window,'load', initialize);
			</script>	
			
			<!-- ninja gallery -->
			<div style="display:none;">
		        <div id="ninja-slider">
		            <div class="slider-inner">
		                <ul>
		                    <?php 
		                	foreach ($property_photos as $photo)
		                	{?>
			                	<li>
			                        <a class="ns-img" href="<?php echo $photo['photo']['url']; ?>"></a>                        
			                    </li>
		                	<?php }	
		                	?>		  
		                </ul>
		                <div id="fsBtn" class="fs-icon" title="Expand/Close"></div>
		            </div>
		        </div>
		    </div>		
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<script>
$(function() {
	$(".slide_googlemap").click(function(){
		$("#property_map").slideToggle(function(){
			var html = $("#property_map").html();
			if(html == ""){ 
				initialize("property_map");
			}	
		});		
	});	

	$('#myModal').on('shown.bs.modal', function(){	
		var html = $("#normal_view").html();
		if(html == ""){	
			initialize("normal_view");
		}	
	});			

	$('#myModal_street').on('shown.bs.modal', function(){	
		var html = $("#street_view").html();
		if(html == ""){	
			initialize("street_view","street");
		}	
	});			
	

	$(".request_info").click(function(){
		var name = $("#name").val();
		var phone = $("#phone").val();
		var email = $("#email").val();
		var message = $("#message").val();

		$(".status").html("<img alt='loading..' src='<?php echo get_template_directory_uri(); ?>/images/loader.gif'>");

		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if(name == "" && phone == "" && email == "" && message == "")
		{
			$(".status").html("<p>Please fill all required fields.</p>");
		}
		else if( regex.test(email) == false )
		{
			$(".status").html("<p>Please fill valid email address.</p>");
		}
		else
		{		
			var form = jQuery('#request_property_info').serialize();
			form += '&action=request_property_info';

			var ajaxurl='<?php echo admin_url('admin-ajax.php') ?>';
			jQuery.ajax({
				type:"post",
				url: ajaxurl,
				data: form,
				success:function(data){	
					var response = jQuery.parseJSON(data);		
					$(".status").html(response.message);	
					
					if(response.success == true)				
					$('#request_property_info').find("input, textarea").val("");					
				}
			});	
		}	
	});	

	$('.gravatar').each(function(){ 
		$(this).removeAttr('width')
		$(this).removeAttr('height');
	});
});	

//lightbox jquery
function lightbox(idx) {
            //show the slider's wrapper: this is required when the transitionType has been set to "slide" in the ninja-slider.js
            var ninjaSldr = document.getElementById("ninja-slider");
            ninjaSldr.parentNode.style.display = "block";

            nslider.init(idx);

            var fsBtn = document.getElementById("fsBtn");
            fsBtn.click();
        }

        function fsIconClick(isFullscreen) { //fsIconClick is the default event handler of the fullscreen button
            if (isFullscreen) {
                var ninjaSldr = document.getElementById("ninja-slider");
                ninjaSldr.parentNode.style.display = "none";
            }
        }
</script>
<link href="<?php echo get_template_directory_uri(); ?>/css/ninja-slider.css" rel="stylesheet" type="text/css" />
<script src="<?php echo get_template_directory_uri(); ?>/js/ninja-slider.js" type="text/javascript"></script>