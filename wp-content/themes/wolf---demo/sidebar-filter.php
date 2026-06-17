<!--header start end-->
<?php 
$filter_property_type = isset($_GET['filter_property_type'])?trim($_GET['filter_property_type']):'';
$filter_address = isset($_GET['filter_address'])?$_GET['filter_address']:'';
$filter_building_type = isset($_GET['filter_building_type'])?trim($_GET['filter_building_type']):'';
$area = isset($_GET['area'])?trim($_GET['area']):'';
$filter_beds = isset($_GET['filter_beds'])?trim($_GET['filter_beds']):'';
$filter_baths = isset($_GET['filter_baths'])?trim($_GET['filter_baths']):'';
$building_min_area = isset($_GET['building_min_area'])?trim($_GET['building_min_area']):'';
$building_max_area = isset($_GET['building_max_area'])?trim($_GET['building_max_area']):'';
$filter_price = isset($_GET['filter_price'])?$_GET['filter_price']:'';
$property_type = isset($_GET['property_type'])?trim($_GET['property_type']):'';
$listing_status = isset($_GET['listing_status'])?trim($_GET['listing_status']):'';
$query = "SELECT min(cast(meta_value as unsigned)) FROM wp_postmeta WHERE meta_key='listing_price'";
$min = $wpdb->get_var($query);
$query = "SELECT max(cast(meta_value as unsigned)) FROM wp_postmeta WHERE meta_key='listing_price'";
$max= $wpdb->get_var($query);
$slidemin = $min;
$slidemax = $max;
if($filter_price != '')
{	
	$filter_price = str_replace('$','',$filter_price);
	$filter_price = explode('-',$filter_price);
	$slidemin = trim($filter_price[0]);
	$slidemax = trim($filter_price[1]);
}
?>
<div class="serch-filter">
<form type="get" action="properties" name ="property_filter" id="property_filter">
  <div class="container">    
      <select class="selectbox" id="filter_property_type" name="filter_property_type">        
        <option value="buy" <?php echo ($filter_property_type == "buy") ? "selected" : ""; ?>>Buy</option>
        <option value="rent" <?php echo ($filter_property_type == "rent") ? "selected" : ""; ?>>Rent</option>
		<option value="sold" <?php echo ($filter_property_type == "sold") ? "selected" : ""; ?>>Sold</option>
      </select>
      <div class="input-field">
        <input type="text" id="filter_address" name="filter_address" value="<?php echo $filter_address; ?>"  placeholder="Enter an address, town, street, zip or property ID">
      </div>
      <?php     
	      $neighborhood = get_terms( array(
	      		'taxonomy' => 'neighbourhood',
	      		'hide_empty' => false				
	      ) );		
      ?>
      <select class="selectbox" name="area">
        <option value=" ">Areas</option>  
         <?php 
          	$selected ="";
          	foreach ($neighborhood as $value)
          	{
          		$selected = ($area == $value->term_id)?"selected":"";
          		echo '<option value="'.$value->term_id.'"'.$selected.'>'.$value->name.'</option>';
          			
          	}	
          ?>      
      </select>
      <label><i class="fa fa-cog" aria-hidden="true"></i> Advanced</label>
      <button type="submit" class="btn btn-default">Search</button>  
      <input type="hidden" id="filter_address" name="property_type" value="<?php echo $property_type; ?>" >  
    <div class="clearfix"></div>
    <?php 
    	$style ='';
    	if($filter_building_type != '' || $filter_beds != '' || $filter_baths != '' || $building_min_area != '' || $building_max_area != '' || $slidemin != $min || $slidemax != $max || $listing_status != '')
    	{
    		$style='style="display:block;"';
    	}	
    ?>
    <div class="advance-search row" <?php echo $style;?>>
      <div class="col-sm-3 col-xs-12">
        <select class="selectbox" name="listing_status">
          <option value=" ">All Status</option>
          <option value="Any" <?php echo ($listing_status == "Any")?"selected":""; ?>>Any</option>
          <option value="Available" <?php echo ($listing_status == "Available")?"selected":""; ?>>Available</option>
          <option value="Rented / Sold" <?php echo ($listing_status == "Rented / Sold")?"selected":""; ?>>Rented / Sold</option>
          <option value="Cancelled" <?php echo ($listing_status == "Cancelled")?"selected":""; ?>>Cancelled</option>
          <option value="Deposit" <?php echo ($listing_status == "Deposit")?"selected":""; ?>>Deposit</option>
        </select>
      </div>
      <div class="col-sm-3 col-xs-12">
      <?php     
	      $building_type = get_terms( array(
	      		'taxonomy' => 'building-style',
	      		'hide_empty' => false				
	      ) );		
      ?>
        <select class="selectbox" name="filter_building_type">
          <option value=" ">All Types</option>
          <?php 
          	$selected ="";
          	foreach ($building_type as $type)
          	{          		
          		$selected = ($filter_building_type == $type->term_id)?"selected":"";
          		echo '<option value="'.$type->term_id.'"'.$selected.'>'.$type->name.'</option>';
          			
          	}	
          ?>
        </select>
      </div>
      <div class="col-sm-3 col-xs-12">
        <select class="selectbox" name="filter_beds">
          <option value=" ">Beds</option>
          <option value="1" <?php echo ($filter_beds == 1)?"selected":""; ?>>1</option>
          <option value="2" <?php echo ($filter_beds == 2)?"selected":""; ?>>2</option>
          <option value="3" <?php echo ($filter_beds == 3)?"selected":""; ?>>3</option>
          <option value="4" <?php echo ($filter_beds == 4)?"selected":""; ?>>4</option>
          <option value="5" <?php echo ($filter_beds == 5)?"selected":""; ?>>5</option>
          <option value="6" <?php echo ($filter_beds == 6)?"selected":""; ?>>6</option>
          <option value="7" <?php echo ($filter_beds == 7)?"selected":""; ?>>7</option>
          <option value="8" <?php echo ($filter_beds == 8)?"selected":""; ?>>8</option>
          <option value="9" <?php echo ($filter_beds == 9)?"selected":""; ?>>9</option>
          <option value="10" <?php echo ($filter_beds == 10)?"selected":""; ?>>10</option>
        </select>
      </div>
      <div class="col-sm-3 col-xs-12">
        <select class="selectbox" name="filter_baths">
          <option value=" ">Baths</option>
          <option value="1" <?php echo ($filter_baths == 1)?"selected":""; ?>>1</option>
          <option value="1.5" <?php echo ($filter_baths == 1.5)?"selected":""; ?>>1.5</option>
          <option value="2" <?php echo ($filter_baths == 2)?"selected":""; ?>>2</option>
          <option value="2.5" <?php echo ($filter_baths == 2.5)?"selected":""; ?>>2.5</option>
          <option value="3" <?php echo ($filter_baths == 3)?"selected":""; ?>>3</option>
          <option value="3.5" <?php echo ($filter_baths == 3.5)?"selected":""; ?>>3.5</option>
          <option value="4" <?php echo ($filter_baths == 4)?"selected":""; ?>>4</option>
          <option value="4.5" <?php echo ($filter_baths == 4.5)?"selected":""; ?>>4.5</option>
          <option value="5" <?php echo ($filter_baths == 5)?"selected":""; ?>>5</option>
          <option value="5.5" <?php echo ($filter_baths == 5.5)?"selected":""; ?>>5.5</option>
          <option value="6" <?php echo ($filter_baths == 6)?"selected":""; ?>>6</option>
          
        </select>
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="input-field">
          <input type="text"  placeholder="Min area (sqft)" name="building_min_area" value="<?php echo $building_min_area; ?>">
        </div>
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="input-field">
          <input type="text"  placeholder="Max area (sqft)" name="building_max_area" value="<?php echo $building_max_area; ?>">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12 price-filter">
        <label>Price Range : <span>From</span></label>
        <div id="slider-range"></div>
        <input type="text" id="amount" readonly name="filter_price" >
        <input type="hidden" name="action" value="filter_properties">
      </div>
    </div>
  </div>
  </form>
</div>
<script>
//price range js start here
$(function() {
	$( "#slider-range" ).slider({
	  range: true,
	  min: <?php echo $min; ?>,
	  max: <?php echo $max; ?>,
	  values: [ <?php echo $slidemin; ?>, <?php echo $slidemax; ?> ],
	  slide: function( event, ui ) {
		$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
	  }
	});
	$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
	  " - $" + $( "#slider-range" ).slider( "values", 1 ) );
});
// price range js end here
</script>