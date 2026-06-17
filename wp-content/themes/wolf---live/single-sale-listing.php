<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>
<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
		<?php while ( have_posts() ) : the_post(); 
				$property_photos = get_field('property_photos');?>
				<div class="midd-content">
			  		<div class="container">
			    		<div class="page-title">
			      			<h1>Properties / Listings</h1>
		    			</div>
			    		<div class="propert-detail-section">
			      			<div class="row margin-top40">
			        			<div class="col-sm-6 property-name">
			          				<h2>
			          					<?php the_title(); ?>
			          					<span>
		          							<?php the_field( "city" ); ?>, 
		          							<?php the_field( "zip_code" ); ?>
	          							</span>
	          						</h2>
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
			        			<div class="col-sm-6 property-price property-custom">
						        	<?php 
						        		$building_size=get_field( "building_size" );
			                    		$sale_price = get_field( "listing_price" );
			                    		$per_square = $sale_price/$building_size;
			                    	?>
						          	<h2>
						          		$<?php 
						          			echo number_format($sale_price);?> 
						          			<span>$<?php echo number_format($per_square);?> / sq. ft. </span>
						          	</h2>
						          	<?php 
						          		$open_house_true 		= get_post_meta(get_the_ID(), 'open_house', true);
						          		$open_house_stare_date 	= get_post_meta(get_the_ID(), 'open_house_start_date', true);
						          		$open_house_end_date 	= get_post_meta(get_the_ID(), 'end_date', true);
						          		$open_house_start_time 	= get_post_meta(get_the_ID(), 'start_time', true);
						          		$open_house_end_time 	= get_post_meta(get_the_ID(), 'end_time', true);
						          		$day                	= get_field('day', get_the_ID());
						          		$todayDate 	= date('Y/m/d');
                    					$checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
						          		if($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate) {  
						          			$start_month= date('M', strtotime($open_house_stare_date)); 
						          			$start_date = date('dS', strtotime($open_house_stare_date));
						          			$end_month 	= date('M', strtotime($open_house_end_date)); 
						          			$end_date 	= date('dS', strtotime($open_house_end_date)); 
						          			$start_time = ltrim(date('h:ia', strtotime($open_house_start_time)),0);
						          			$end_time 	= ltrim(date('h:ia', strtotime($open_house_end_time)),0); ?>
					          				<div class="custom-open-house-outer">
								          		<div class="inner-open-house-outer">
								          			<h4>Open House: 
								          				<?php echo $day.', '.$start_month.' '.$start_date.', '; ?>
                                                    	<?php echo $start_time.' - '.$end_time; ?>
								          			</h4>
								          		</div>
						          			</div>
						          		<?php }  //else: ?>
						          			<!--<style>.custom-open-house-outer{display: none;}</style>-->
							        <?php //endif; ?>
						        </div>
					      	</div>
			      			<div class="detail-banner"> 
			      				<?php 
			      					$thumbnail = get_the_post_thumbnail_url(get_the_ID(),'full');  
	                					if(!empty($thumbnail)){
					                		echo '<img src="'.$thumbnail.'" alt="thumbnail">';
	                					}else {
					                		echo '<img src="'.get_template_directory_uri().'/images/thumbnail-full.jpg" alt="thumbnail">';
	                					}
	                            	
			                    	$price = get_field( "listing_price" );                    	
			                    	$per_square = $price/$building_size;
			                    ?>
			        			<div class="agent-form-section">
			          				<div class="agent-detail">
							          	<?php 
							          		$agentID = get_post_meta( get_the_ID(), 'agent', true ); 
							          		$author_info = get_userdata($agentID);
							          	?>
			            				<div class="agent-image">
			            					<?php echo get_avatar($agentID, 65 ); ?>
		            					</div>
			            				<div class="agent-des">
			              					<h2>Contact Agent</h2>
			              					<p>
			              						<i class="fa fa-user" aria-hidden="true"></i> 
			              						<?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?>
			              					</p>
			              					<p>
			              						<i class="fa fa-phone" aria-hidden="true"></i>
			              						<?php echo get_user_meta( $agentID, 'mobile_number',true);?>
			              					</p>
			              					<a href="<?php echo get_site_url();?>/agents?user=<?php echo $author_info->user_login; ?>">View my listing</a>
			              				</div>
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
								        $stateterm = get_term( $state_array, $taxonomy );
								        $state = $stateterm->name;
							        ?>
						          	<ul>
						            	<li>
						            		<a href="javascript:void(0)" onclick="lightbox(0)">
						            			<i class="fa fa-camera" aria-hidden="true"></i>
						            		</a>
						            	</li>
						            	<?php if(!empty($address) && !empty($zip_code)){ ?>
								            <li>
								            	<a href="javascript:void(0)" id="google_map_view" data-toggle="modal" data-target="#myModal">
								            		<i class="fa fa-map" aria-hidden="true"></i>
								            	</a>
								            </li>
								            <li>
								            	<a href="javascript:void(0)" id="street_map_view" data-toggle="modal" data-target="#myModal_street">
								            		<i class="fa fa-street-view" aria-hidden="true"></i>
								            	</a>
								            </li>
						            	<?php } ?>
						          	</ul>
						        </div>
                                <?php
                                	$project_status = get_field('sold_under_contract');
                                	if($project_status == 1 || $project_status == 2){ ?>
                                		<div class="transparent-strip">
	                                		<?php
	                                    		if($project_status == 1){
	                                        		echo "Sold";
	                                    		}elseif($project_status == 2){
	                                        		echo "In Contract";
	                                    		}
	                            			?>
                                		</div>
                            	<?php } ?>  
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
			              					<h3>Address</h3>
			              					<?php if(!empty($address) && !empty($zip_code)) {
		              							$address= $address." ".$city." ".$zip_code." ".$state;
			              						$formattedAddr = str_replace(' ','+',$address);
							              		//Send request and receive json data by address
			              						//$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false');
			              						$geocodeFromAddr = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false';
							              		$ch = curl_init();
												curl_setopt($ch, CURLOPT_URL, $geocodeFromAddr);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
												curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
												curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
												$response = curl_exec($ch);
												curl_close($ch);
												$output = json_decode($response);
												
							              		//Get latitude and longitute from json data
							              		$latitude  = $output->results[0]->geometry->location->lat;
							              		$longitude = $output->results[0]->geometry->location->lng; 
			              					?>
			              					<span>
			              						<a href="javascript:void(0)" class="slide_googlemap">Open on Google Maps 
			              							<i class="fa fa-map-marker" aria-hidden="true"></i>
			              						</a>
			              					</span>
						            		<?php }	?>			   
			            				</div>
			            				<div id="property_map" style="width:100%;height:300px;"></div>			            
			            					<ul class="detail-style01">
			            						<?php if(!empty($address)){?>
			              							<li>
			                							<label>Address :</label>
			                							<span><?php the_field('address'); ?></span>
			              							</li>
		            							<?php }?>  
			              						<li>
			                						<label>State/Country :</label>
			                						<span><?php echo $state; ?></span>
			              						</li>
			             						<?php if(!empty($unit)){?>
			              							<li>
			                							<label>Unit :</label>
			                							<span><?php echo $unit; ?></span>
			              							</li>
			              						<?php } if(!empty($city)){ ?>
			              							<li>
			                							<label>City :</label>
			                							<span><?php echo $city; ?></span>
			              							</li>
			              						<?php } if(!empty($zip_code)){ ?>
			              							<li>
			                							<label>Zip/Postal Code :</label>
			                							<span><?php echo $zip_code; ?></span>
			              							</li>
		              							<?php } ?>
			            					</ul>
			          					</div>
			          					<div class="detail-block1 margin-top30">
			            					<div class="block-title">
			              						<h3>Detail</h3>
			              						<span>Updated on <?php echo the_modified_date("F j,Y");?> at <?php echo the_modified_date("h:i a");?></span>
			            					</div>
								            <?php 
								            	$lotsize= get_field('lot_size');
								            	$buildsize= get_field('building_size');
								            	$style = get_field('building_style');	
								            	$heat = get_field('heat_source');	
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
			                						<?php if(!empty($lotsize)){?>
			                							<li>
			                  								<label>Lot Size :</label>
			                  								<span><?php echo $lotsize;?> SqFt</span>
			                							</li>
			                						<?php } if(!empty($buildsize)){ ?>
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
			                  							<label>Heat Source :</label>
			                  							<span><?php echo implode(", ",$heat);?></span>
			                						</li>
			                 						<li>
			                  							<label>Air Conditioning :</label>
			                  							<span><?php the_field('air_conditioning');?></span>
			                						</li>
			                						<li>
			                  							<label>Range :</label>
			                  							<span><?php the_field('range');?></span>
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
												if(!empty($neighbourhood)){ ?>
													<div class="block-title">
			              								<h3>Neighborhood</h3>
			            							</div>
										            <ul class="detail-style02">
									              		<?php foreach ($neighbourhood as $value) {
															echo '<li>'.$value->name.'</li>';
										              	} ?>
										            </ul>			          
									          	<?php }			          
									          	$features = get_the_terms( $ID,'property-feature');	
												if(!empty($features)){ ?>
													<div class="block-title margin-top30">
		              									<h3>Property Features</h3>
	            									</div>
	            									<ul class="detail-style02">
	              										<?php foreach ($features as $feature){
															echo '<li>'.$feature->name.'</li>';
	              										} ?>
	            									</ul>			          
		          								<?php } 
		          								$parking = get_the_terms( $ID,'parking');
		          								if(!empty($parking)){ ?>	
		          									<div class="block-title margin-top30">		          
		              									<h3>Parking</h3>
	            									</div>
		            								<ul class="detail-style02">
		              									<?php foreach ($parking as $value){
															echo '<li>'.$value->name.'</li>';
		              									} ?>
		            								</ul>			          
		          								<?php } 
		          								$mlsfeatures = get_the_terms( $ID,'mls-features');
		          								if(!empty($mlsfeatures)){ ?>
		          									<div class="block-title margin-top30">		          
		              									<h3>MLS Features</h3>
		            								</div>
		            								<ul class="detail-style02">
		              									<?php foreach ($mlsfeatures as $value){
															echo '<li>'.$value->name.'</li>';
														} ?>
		            								</ul>
		          								<?php }
		          								$basement = get_the_terms( $ID,'basement');
		          								if(!empty($basement)){?>
		          									<div class="block-title margin-top30">		          
		              									<h3>Basement</h3>
										            </div>
										            <ul class="detail-style02">
										              <?php foreach ($basement as $value){
															echo '<li>'.$value->name.'</li>';
										              	} ?>
										            </ul>			          
									          	<?php }
									          	$yard = get_the_terms( $ID,'yard');
		          								if(!empty($yard)){ ?>	
		          									<div class="block-title margin-top30">		          
		              									<h3>Yard</h3>
		            								</div>
		            								<ul class="detail-style02">
		              									<?php foreach ($yard as $value){
															echo '<li>'.$value->name.'</li>';
		              									} ?>
		            								</ul>			          
		          								<?php }
		          								$amenities = get_the_terms( $ID,'amenities');
		          								if(!empty($amenities)){ ?>	
		          									<div class="block-title margin-top30">		          
		              									<h3>Amenities</h3>
		            								</div>
		            								<ul class="detail-style02">
		              									<?php foreach ($amenities as $value){
															echo '<li>'.$value->name.'</li>';
		              									} ?>
		            								</ul>			          
	          								<?php }?>
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
			        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        					<span aria-hidden="true">&times;</span>
			        				</button>
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
			        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        					<span aria-hidden="true">&times;</span>
			        				</button>
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
					     	if(view == "street"){
					    	 	var panorama = new google.maps.StreetViewPanorama(
						    	    document.getElementById(id), {
						    	        position: myLatlng,
						    	        pov: {
						    	        	heading: 34,
						    	        	pitch: 10
						    	        }
				    	            }
			    	           	);
				    	        map.setStreetView(panorama);				    							
						     }else{            
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
		                			<?php foreach ($property_photos as $photo){ ?>
			                			<li>
			                        		<a class="ns-img" href="<?php echo $photo['photo']['url']; ?>"></a>
			                    		</li>
		                			<?php }	?>		                   
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