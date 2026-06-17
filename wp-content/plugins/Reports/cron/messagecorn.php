<?php
include("../../../../wp-load.php");
global $wpdb;
 $from = get_field('clickatell_reply_phone_number', 'option');
 $from = str_replace('+', '', $from);

/* Get record from temp table that is select by client */
$result = $wpdb->prepare("SELECT * FROM  wp_posts WHERE  `post_type` =  'rental-listing' OR post_type =  'sale-listing'");
$phone_numbers = $wpdb->get_results($result);
  echo '<pre>';
 print_r($phone_numbers);
 exit; die('test');
?>
