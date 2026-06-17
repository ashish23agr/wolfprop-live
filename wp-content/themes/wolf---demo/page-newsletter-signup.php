<?php
if(!empty($_GET['subscribe']) && $_GET['subscribe'] == 'd')
{
     $_SESSION['subscribe'] = true;
     wp_redirect(get_site_url()."#newsletter_subscribe");
}   
elseif(!empty($_GET['confirm']) && $_GET['confirm'] == 'd')
{
     $_SESSION['confirm'] = true;
     wp_redirect(get_site_url()."#newsletter_subscribe");
}   
else
{
    wp_redirect(get_site_url()."#newsletter_subscribe");
}    
?>