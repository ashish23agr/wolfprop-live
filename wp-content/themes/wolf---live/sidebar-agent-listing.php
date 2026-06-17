<?php 
	$add_rental = get_site_url().'/add-rental-listing/';
	$add_sales = get_site_url().'/add-sales-listing/';
	$browser_url=  "http://".$_SERVER[HTTP_HOST].$_SERVER['REQUEST_URI'];
?>
<div class="listing-sidebar-2">
          <div class="sidebar-box">
            <h2>Listing Maintenance</h2>
              <p><a <?php echo ($add_rental == $browser_url)? 'class="red"':''; ?> href="<?php echo $add_rental;?>">Add Rental Listing</a><br>
              <span><a <?php echo ($add_sales == $browser_url)? 'class="red"':''; ?> href="<?php echo $add_sales;?>">Add Sales Listing</a></span><br>
              </p>
          </div>          
 </div>