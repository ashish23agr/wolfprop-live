<?php
/** 
Template Name: Add rental listing template

template to add rental listing for agent
*/

if(is_user_logged_in()){
get_header(); 
get_sidebar('filter');
$currentUserId = get_current_user_id();
?>
<?php 
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
$currentUserId = get_current_user_id();
	//store our post vars into variables for later use
	//now would be a good time to run some basic error checking/validation
	//to ensure that data for these values have been set
	$title     = $_POST['title'];
	$content   = $_POST['content'];
	$excerpt   = $_POST['excerpt'];
	$post_type = 'rental-listing';
	$neighbourhood = $_POST['neighbourhood'];
	$property_features = $_POST['property-feature'];	
	$fields = $_POST['fields'];	
	$property_features = explode(",",$property_features);	
	
	$fields['date_available'] = date("Y-m-d", strtotime($fields['date_available']));
	$fields['listing_date'] = date("Y-m-d", strtotime($fields['listing_date']));
	$fields['start_online'] = date("Y-m-d", strtotime($fields['start_online']));
	$fields['agent'] = $currentUserId;
	$feature_image_id = $_POST['feature_image'];	
	
	if(empty($fields['state']))
	{
		$fields['state'] = 51;
	}else{
    $state_id =  $fields['state'];
    $fields['state'] = $state_id;
  }
	
	//the array of arguements to be inserted with wp_insert_post
	$new_post = array(
	'post_title'    => $title,
	'post_content'  => $content,
	'post_excerpt'	=> $excerpt,
	'post_status'   => 'publish',
	'post_author'   => $currentUserId,
	'post_type'     => $post_type,
	'tax_input' 	=> array(
			'neighbourhood' => 	$neighbourhood,
			'building-style' => array($fields['building_style']),
			'property-feature' => $property_features
		),
	'meta_input' => $fields
	);	
	//insert the the post into database by passing $new_post to wp_insert_post
	//store our post ID in a variable $pid
	$pid = wp_insert_post($new_post);
	if($pid)
	{	
		set_post_thumbnail( $pid , $feature_image_id);
		$_SESSION['status'] = '<div class="alert alert-success">Listing has been published successfully.</div>';
	}
	else 
	{
		$_SESSION['status'] = '<div class="alert alert-danger">Listing could not save, please try again</div>';
	}	
}
?>  
<form action="" method="post">
<div class="midd-content">
  <div class="container">
    <div class="row margin-top30">
      <div class="col-md-3 col-sm-4 col-xs-12">
        <?php get_sidebar('agent-listing');?>
      </div>
      <div class="col-md-9 col-sm-8 col-xs-12 listing-content">
      <?php 
      	if(isset($_SESSION['status']))
      	{
      		echo '<div class="col-sm-12">'.$_SESSION['status'].'</div>';	
      		unset($_SESSION['status']);
      	}	
      ?>
        <div class="page-heading2">
          <h1 class="pull-left">ADD A RENTAL LISTING</h1>
          <img class="pull-right margin-top-30" src="<?php echo get_template_directory_uri(); ?>/images/for-rent.png" alt=""> </div>
        <div class="property-location">
          <div class="sub-heading">
            <h2>Property Location</h2>
          </div>          
          <div class="row">
            <div class="col-sm-6 col-xs-12">
              <label>Listing Type:</label>
              <span>Rental</span> </div>
            <div class="col-sm-6 col-xs-12">
              <label>Featured: </label>
              <div class="custom-selectbox width70">
                <select class="selectbox" name ="fields[featured]">
                  <option value="0">No</option>
                  <option value="1">Yes</option>                  
                </select>
              </div>
            </div>
            
            <div class="col-md-2 col-sm-3 col-xs-12 margin-top20">
              <label>Title:</label>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12 margin-top20">
              <div class="custom-input">
                <input name="title" type="text">
              </div>
            </div>
          	
            <div class="col-sm-6 col-xs-12 margin-top10">
              <label>Neighborhood:*</label>
            </div>
            <div class="col-sm-6 col-xs-12 margin-top10 text-right">
            	<a href="javascript:void(0)"  class="btn-default btn-default-blue fileinput-exists selectall">Select All</a>
            	<a href="javascript:void(0)"  class="btn-default btn-default-blue fileinput-exists deselectall">Deselect All</a>
            </div>
            <div class="col-sm-12 col-xs-12 margin-top10">
              <ul>
              <?php     
			      $neighborhood = get_terms( array(
			      		'taxonomy' => 'neighbourhood',
			      		'hide_empty' => false				
			      ) );
				 foreach ($neighborhood as $index=>$value)
				 {								
		        ?>
                <li>
                  <div class="checkbox-button">
                    <input type="checkbox" name="neighbourhood[]" value="<?php echo $value->term_id; ?>" id="check<?php echo $index;?>"/>
                    <label for="check<?php echo $index;?>"> <span class="fa-stack"> <i class="circle-o fa-stack-1x"></i> <i class="circle fa-stack-1x"></i> </span> <?php echo $value->name;?> </label>
                  </div>
                </li> 
                <?php } ?>               
              </ul>
            </div>
          </div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-3 col-xs-12">
              <label>Property Features</span></label>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
              <div class="custom-input">
                <input name="property-feature" id="property-feature" type="text">
              </div>
            </div>
          </div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-3 col-xs-12">
              <label>Featured Description: <span>(150 characters)</span></label>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
              <div class="custom-input">
                <textarea name="excerpt" cols="" rows=""></textarea>
              </div>
            </div>
          </div>
          <div class="row margin-top50">
            <div class="col-md-2 col-sm-3 col-xs-12">
              <label>Description:<span>(500 characters)</span></label>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
              <div class="custom-input height130">
                <textarea name="content" cols="" rows=""></textarea>
              </div>
            </div>
          </div>
          <div class="dot-border">&nbsp;</div>
          <div class="row">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Address</label>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[address]" type="text">
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Unit/Apt/Suite:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[unit]" type="text">
              </div>
            </div>
          </div>          
          <div class="clearfix"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>City<span>(optional)</span></label>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[city]" type="text">
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Zip Code:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[zip_code]" type="text">
              </div>
            </div>
          </div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>State:<span>(optional)</span></label>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[state]">
                	<option value="">Select State</option>
                <?php     
			      $states = get_terms( array(
			      		'taxonomy' => 'state',
			      		'hide_empty' => false				
			      ) );
				  foreach ($states as $index=>$value)
				  {								
		        ?>
                  <option value="<?php echo $value->term_id; ?>"><?php echo $value->name; ?></option>
                <?php } ?>                  
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="property-detail margin-top50">
          <div class="sub-heading">
            <h2>Property Detail</h2>
          </div>
          <div class="clearfix"></div>
          <div class="row margin-top10">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Style</label>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[building_style]">
                	
                  <?php     
			      $states = get_terms( array(
			      		'taxonomy' => 'building-style',
			      		'hide_empty' => false				
			      ) );
				  foreach ($states as $index=>$value)
				  {								
		         ?>
                  <option value="<?php echo $value->term_id; ?>"><?php echo $value->name; ?></option>
                <?php } ?>    
                </select>
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Building Size (Sq Ft.)</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[building_size]" type="text">
              </div>
            </div>
          </div>
          <div class="dot-border"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Bedrooms:</label>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[bedrooms]">
                  <option value="1">1</option>
		          <option value="2">2</option>
		          <option value="3">3</option>
		          <option value="4">4</option>
		          <option value="5">5</option>
		          <option value="6">6</option>
		          <option value="7">7</option>
		          <option value="8">8</option>
		          <option value="9">9</option>
		          <option value="10">10</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Bathrooms:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[bathrooms]">
                  <option value="1">1</option>
		          <option value="1.5">1.5</option>
		          <option value="2">2</option>
		          <option value="2.5">2.5</option>
		          <option value="3">3</option>
		          <option value="3.5">3.5</option>
		          <option value="4">4</option>
		          <option value="4.5">4.5</option>
		          <option value="5">5</option>
		          <option value="5.5">5.5</option>
		          <option value="6">6</option>
                </select>
              </div>
            </div>
          </div>
          <div class="dot-border"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Heat:</label>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[heat]">
                  <option value="N/A">N/A</option>
                  <option value="Included">Included</option>
                  <option value="Not Included">Not Included</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Elevator:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[elevator]">
                  <option value="No">No</option>
                  <option value="Yes">Yes</option>                  
                </select>
              </div>
            </div>
          </div>
          <div class="dot-border"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Pets:</label>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[pets]"> 
                  <option value="None" >None</option>
                  <option value="Any">Any</option>
                  <option value="Cats Only">Cats Only</option>
                  <option value="Dogs Only">Dogs Only</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Parking:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[parking]">
                  <option value="No">No</option>
                  <option value="Yes">Yes</option>                  
                </select>
              </div>
            </div>
          </div>
          <div class="dot-border"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Laundry:</label>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[laundry]"> 
                  <option value="Near by" >Near by</option>
                  <option value="Near by">In Unit</option>
                  <option value="In Building">In Building</option>                  
                </select>
              </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12 text-right">
              <label>Internet:</label>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[internet]">
                  <option value="None">None</option>
                  <option value="Cable">Cable</option>  
                  <option value="DSL">DSL</option>                 
                </select>
              </div>
            </div>
          </div>
          <div class="dot-border"></div>
          <div class="row margin-top20">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Showing Notes:</label>
            </div>
            <div class="col-md-10 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[showing_notes]" type="text">
              </div>
            </div>
          </div>
        </div>
        <div class="rent-information margin-top50">
          <div class="sub-heading">
            <h2>Rent Information:</h2>
          </div>
          <div class="clearfix"></div>
          <div class="row margin-top10">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Listing Rent:</label>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[listing_price]" type="text">
              </div>
            </div>
          </div>
          <div class="row margin-top10">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Date Available:</label>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[date_available]" type="text" class="datepicker">
                <span class="date-icon"></span> </div>
            </div>
          </div>
        </div>
        <div class="listing-information width100 pull-left margin-top50">
          <div class="sub-heading">
            <h2>Listing Information:</h2>
          </div>
          <div class="clearfix"></div>
          <div class="row margin-top10">
            <div class="col-md-2 col-sm-12 col-xs-12">
              <label>Landlord:</label>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[landlord]" type="text">
              </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 text-right">
              <label>Landlord Contact #:</label>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[landlord_contact_#]" type="text">
              </div>
            </div>            
          </div>          
        <div class="row margin-top20">
            <div class="col-md-3 col-sm-12 col-xs-12">
              <label>Listing Date:</label>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[listing_date]" type="text" class="datepicker">
                <span class="date-icon"></span> </div>
            </div>
          <div class="col-md-3 col-sm-12 col-xs-12 text-right">
              <label>Start Online:</label>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
              <div class="custom-input">
                <input name="fields[start_online]" type="text" class="datepicker">
                <span class="date-icon"></span> </div>
            </div>
          </div>
          
          <div class="row margin-top20">
            <div class="col-md-3 col-sm-12 col-xs-12">
              <label>Listing Status: </label>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
              <div class="custom-selectbox">
                <select class="selectbox" name="fields[listing_status]">
                  <option value="Any">Any</option>
                  <option value="Available">Available</option>
                  <option value="Rented / Sold">Rented / Sold</option>
                  <option value="Cancelled">Cancelled</option>
                  <option value="Deposit">Deposit</option>
                </select>
              </div>
          </div>
        </div>
        
        <div class="dot-border"></div>
        
        <div class="sub-heading">
            <h2>Add Photos : <span>Accpeted file types are .jpg, and .png.</span></h2>
          </div>
          <ul id="images_wrap">
			<li class="images_head"><div class="attachment">Photo</div><div class="attachment">Feature</div><div class="attachment"></div></li>
		</ul> 
		<div class="row margin-top20">
         <div class="col-md-12 col-xs-12 upload_form">
        <div class="fileinput fileinput-new margin-top20" data-provides="fileinput">
        <span class="btn btn-default btn-default-blue btn-file"><span>Choose Photo</span><input type="hidden"><input type="file" name="images[]" id="images" accept="image/*" multiple></span>
        <span class="fileinput-filename"></span><span class="fileinput-new">No file selected</span>
        <span id="status"></span>
        <?php wp_nonce_field('image_upload', 'image_upload_nonce');?>
        </div> 
        </div>
        <div class="col-md-12 col-xs-12 margin-top20">
        	<input type="button" value="Upload Photo" class="btn-default btn-default-blue" id="upload_form">
            <a href="javascript:void(0)" class="btn-default btn-default-blue fileinput-exists" data-dismiss="fileinput">Cancel</a>
        </div>
        <div class="col-md-12 col-xs-12 margin-top50 text-center">
        	<input type="submit" value="Submit Listing" class="btn-default btn-default-blue">            
        </div> 
        </div>        
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</form>
<?php get_footer(); ?>
      <script>
      var count = 0;
         $(function() {
            $( ".datepicker" ).datepicker();           
         });
         $('#property-feature').selectize({
				persist: false,
				createOnBlur: true,
				create: true
			});

         $( function() {
     	    $('#upload_form').on('click', function(e){
     	    	e.preventDefault();
     				var $this = $('.upload_form'),
     					nonce = $this.find('#image_upload_nonce').val(),
     					images_wrap = $('#images_wrap'),
     					status = $('#status'),
     					formdata = false;
						
     				if ( $this.find('#images').val() == '' ) {
     					alert('Please select an image to upload');
     					return;
     				}

     				status.fadeIn().html('<img alt="loading.." src="<?php echo get_template_directory_uri(); ?>/images/loading.gif">')

     				if (window.FormData) {
     					formdata = new FormData();
     				}
     				var files_data = $('#images');

     				$.each($(files_data), function(i, obj) {
     					$.each(obj.files, function(j, file) {
     						formdata.append('files[' + j + ']', file);
     					})
     				});
     				// our AJAX identifier
     				formdata.append('action', 'upload_images');

     				formdata.append('nonce', nonce);

     				formdata.append('count', count);

     				$.ajax({
     					url: '<?php echo admin_url('admin-ajax.php') ?>',
     					type: 'POST',
     					data: formdata,
     					dataType: 'json',
     					processData: false,
     					contentType: false,
     					success: function(data) {

     						if (data.status) {
         						$(".images_head").css("display","block");
     							images_wrap.append(data.message);
     							count++;
     							status.fadeIn().text('Image uploaded').fadeOut(2000);
     						} else {
     							status.fadeIn().text(data.message);
     						}
     					}
     				});

     			});

     	    $(document).on("click", ".remove_attach", function(){
     	        var container = $(this).attr("data-attachment");        	        
     	        $("#attachment-"+container).remove();
     		});

     	    $(document).on("click",".fileinput-exists",function(){
     	    	$("#images").val("");	
     	    	$(".fileinput-filename").empty();     	    	
         	});

     	   $(document).on("click",".selectall",function(){
				$(this).parent().next().find('input[type=checkbox]').each(function(){
			           $(this).prop( "checked",true);  
			                 
			   });
			});

			$(document).on("click",".deselectall",function(){
				$(this).parent().next().find('input[type=checkbox]').each(function(){						
					$(this).prop( "checked",false);  
			          
			   });
			});
     		
     	} );
      </script>    
<?php } 
else 
{
	wp_redirect(get_site_url()."/login/");
}	
?>      