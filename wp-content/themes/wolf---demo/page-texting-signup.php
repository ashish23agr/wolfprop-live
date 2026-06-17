<?php
if( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
     global $wpdb;
     $phone_number = preg_replace('/\D+/', '', $_POST['ne']);
     $phone_number = "+1".$phone_number;
     $ip = $_SERVER['REMOTE_ADDR'];
     
     $table_name = $wpdb->prefix . "phone_texting";
     $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE phone_number = $phone_number");
     if($rowcount == 0)
     {    
        $retun = $wpdb->insert($table_name, array('phone_number' => $phone_number, 'ip' => $ip,'created'=>date("Y-m-d H:i:s"),'modified'=>date("Y-m-d H:i:s")) );

        if($retun){
            $_SESSION['textingconfirm'] = true;
            wp_redirect(get_site_url()."#newsletter_subscribe");
        }    
        else
        {
           $_SESSION['newslettererr']="Something went wrong, please try again later.";           
           wp_redirect(get_site_url()."#newsletter_subscribe");
           
        }   
     }
     else {
         $_SESSION['newslettererr'] = "Phone number is already subscribed.";       
         wp_redirect(get_site_url()."#newsletter_subscribe");
     }
}
else
{
    wp_redirect(get_site_url());
}    
?>
