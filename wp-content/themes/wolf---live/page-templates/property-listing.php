<?php
/**
Template Name: Property listing template
*/
get_header(); 
get_sidebar('filter');
?>
<div class="midd-content">
  <div class="container">
    <div class="page-title">
      <h1>Properties / Listings</h1>
    </div>
    <div class="row">
    <div class="col-sm-4 col-xs-12 listing-sidebar margin-top30">
      	<?php get_sidebar('left-filter'); ?>
      </div>
      <div class="col-sm-8 col-xs-12 listing-section margin-top30">
	      	<div class="view-mode">
	          <ul>
	            <li class="active"><a href="javascript:void(0);" class="list"><i class="fa fa-list-ul" aria-hidden="true"></i></a></li>
	            <li><a href="javascript:void(0);" class="grid"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
	          </ul>
	        </div>
	        <div class="sort-outer">
	            <label>Sort by :</label>
	            <select class="sortby_selectbox" id="sortby">
	              <option value="recent">Most Recent</option>
	              <option value="price_asc"><strong>Price </strong>- Low to High</option>
	              <option value="price_desc"><strong>Price </strong>- High to Low</option>
	            </select>
	        </div>
	      <div class="property_listing">        
	        	<img style="display: block; margin: 0 auto;" alt="loading" src="<?php echo get_template_directory_uri(); ?>/images/ring.gif">
	      </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
<div class="loading">Loading&#8230;</div>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.selectbox.js"></script>


<script>
$(function() {
	ajaxSubmit();
	function ajaxSubmit(extra='',callback= false){
		var sortby = jQuery("#sortby").val();
		<?php if(!empty($_GET['action'])){?>		
		var filterform = jQuery('#property_filter').serialize();
		<?php } else {?>						
			var filterform = 'action=filter_properties';
		<?php } ?>

		filterform += '&sortby='+sortby+extra;
		var ajaxurl='<?php echo admin_url('admin-ajax.php') ?>';	
		jQuery.ajax({
			type:"get",
			url: ajaxurl,
			data: filterform,
			success:function(data){			
				jQuery('.property_listing').html(data);	
				if(callback == true){
					$('html, body').animate({
				        scrollTop: $(".property_listing").offset().top
				    }, 1000);
                    jQuery(".loading").css("display","none");
				}						
			}
		});		
		
	}

	jQuery("#sortby").change(function(){
		var text = '<img style="display: block; margin: 0 auto;" alt="loading" src="<?php echo get_template_directory_uri(); ?>/images/ring.gif">';
		$(".property_listing").html(text);
		var sortby = jQuery().val();
		ajaxSubmit();
	});	

	jQuery('body').on('click', 'a.inactive', function(event) {	
		event.preventDefault();
		var paged = jQuery(this).attr("data-paged"); 
		var tab = jQuery(this).attr("data-tab"); 
                jQuery(".loading").css("display","block");
	    $extra = "&paged="+paged+"&tab="+tab;
		ajaxSubmit($extra,true);		
	});
});
</script>