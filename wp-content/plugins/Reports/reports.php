<?php

/*

  Plugin Name: Reports Manager

  Plugin URI:

  Description: Reports Manager.

  Author: WP Developer

  Version: 1.1

 */



function my_admin_theme_style() {

    wp_enqueue_style('admin_css', plugins_url('/Reports/style.css', false, '1.0.0', 'all'));

    wp_enqueue_script('custom_js', plugin_dir_url(__FILE__) . 'custom.js');

    wp_enqueue_style('select2_css', plugins_url('/Reports/select2/select2.css', false, '1.0.0', 'all'));

    wp_enqueue_script('select2_js', plugin_dir_url(__FILE__) . 'select2/select2.min.js');

}



add_action('admin_enqueue_scripts', 'my_admin_theme_style');

add_action('login_enqueue_scripts', 'my_admin_theme_style');



add_action('admin_menu', 'my_plugin_menu');



/** Step 1. */

function my_plugin_menu() {

    add_menu_page('Reports', 'Reports', 'manage_options', 'reportlisting', 'myplguin_admin_page', 'dashicons-welcome-write-blog', 28);

    add_submenu_page('reportlisting', __('30 Day+ Listing'), __('30 Day+ Listing'), 'manage_options', '30_day_listing', 'myplguin_admin_page');

    add_submenu_page('reportlisting', __('Daily Activity'), __('Daily Activity'), 'manage_options', 'daily_activity', 'daily_activity_admin_page');

    remove_submenu_page('reportlisting', 'reportlisting');



    add_menu_page('Queries', 'Queries', 'manage_options', 'lease', 'lease_expiring_page', 'dashicons-tickets', 29);

    add_submenu_page('lease', __('Lease Expiring'), __('Lease Expiring'), 'manage_options', 'lease_expiring', 'lease_expiring_page');

    remove_submenu_page('lease', 'lease');



    add_menu_page('Clickatell', 'Clickatell', 'manage_options', 'clickatell', 'clickatell_newsletter', 'dashicons-tickets', 30);

    add_submenu_page('clickatell', __('Groups'), __('Groups'), 'manage_options', 'clickatell_groups', 'clickatell_group_page');

    add_submenu_page('clickatell', __('Add Group'), __('Add Group'), 'manage_options', 'clickatell_add_group', 'clickatell_add_group_page');

}



function myplguin_admin_page() {

    $reset_id = $_GET['reset'];

    if (!empty($reset_id)) {

        $listing_end = date('Y-m-d', strtotime("+30 days"));

        update_post_meta($reset_id, 'listing_end', $listing_end);

    }



    $args = array(

        'post_status' => 'publish',

        'posts_per_page' => -1,

        'post_type' => array('sale-listing', 'rental-listing'),

        'meta_query' => array(

            array(

                'key' => 'listing_end',

                'value' => date('Y-m-d'),

                'compare' => '<'

            )

        )

    );

    $my_query = new WP_Query($args);

    ?>



    <div class="wrap report">

        <h1>REPORTS: 30 DAY + LISTING</h1>

        <h3>Listing Available For More Than 30 Days.</h3>

        <table class="wp-list-table widefat fixed striped posts">

            <tr>

                <th>Listing Agent</th>

                <th>Property # </br>Status</th>

                <th>Address</th>

                <th>Neighborhood</th>

                <th>Landlord</br>Contact Info</th>

                <th>View Profile Listing</th>               

            </tr>

            <?php

            if ($my_query->have_posts()) {

                while ($my_query->have_posts()) : $my_query->the_post();

                    $agent = get_field("agent");

                    $author_info = is_array($agent) ? get_userdata($agent['ID']) : get_userdata($agent);



                    $addr = '';



                    $address = get_field('address');

                    if (!empty($address))

                        $addr .= $address;



                    $city = get_field('city');

                    if (!empty($city))

                        $addr .= ' ' . $city;



                    $zip_code = get_field('zip_code');

                    if (!empty($zip_code))

                        $addr .= ', ' . $zip_code;



                    $neigharr = '';

                    $ID = get_the_ID();

                    $neighbourhood = get_the_terms($ID, 'neighbourhood');

                    $count = is_array($neighbourhood) ? count($neighbourhood) : 0;

                    if (!empty($neighbourhood)) {

                        foreach ($neighbourhood as $index => $value) {

                            $neigharr .= $value->name;

                            if ($index != $count - 1)

                                $neigharr .= ', ';

                        }

                    }

                    ?>

                    <tr>

                        <th><?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?></th>

                        <th><a href="<?php the_permalink(); ?>"><?php the_ID(); ?></a> </br><?php the_field('listing_status'); ?></th>

                        <th><?php echo $addr; ?></th>

                        <th><?php echo $neigharr; ?></th>

                        <th><?php the_field('landlord'); ?></br><?php echo the_field('landlord_contact_#'); ?></th>

                        <th style="text-align:center"><a class="button button-primary button-large" target="_blank" href="<?php echo get_edit_post_link(); ?>">View</a></br></br>

                            <a href="?page=30_day_listing&reset=<?php echo $ID; ?>">Reset 30+ Day</a>

                        </th>

                    </tr>

                    <?php

                endwhile;

                ?>

                <tr>

                    <td colspan="6">

                        <?php

                        echo paginate_links(array(

                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),

                            'format' => '?paged=%#%',

                            'current' => max(1, get_query_var('paged')),

                            'total' => $my_query->max_num_pages

                        ));

                        ?>

                    </td>

                </tr>

                <?php

            }else {

                ?>

                <tr><td colspan="6">No record found</td></tr>

                <?php

            }

            ?>

        </table>

    </div> 

    <?php

}



function daily_activity_admin_page() {



    $content = "";

    $count_args = array(

        'role' => 'agent',

        'fields' => 'all_with_meta',

        'number' => 999999

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



    $args = array(

        // search only for Authors role

        'role' => 'agent',

        // order results by display_name

        'orderby' => 'display_name',

        // return all fields

        'fields' => 'all_with_meta',

        'number' => $users_per_page,

        'offset' => $offset // skip the number of users that we have per page

    );



    // Create the WP_User_Query object

    $wp_user_query = new WP_User_Query($args);



    // Get the results

    $authors = $wp_user_query->get_results();





    $content = '<div class="wrap report">

            <h1>REPORTS: DAILY ACTIVITY</h1>

            <h3>As of ' . date('m/d/Y') . '</h3>

            <table class="wp-list-table widefat fixed striped posts">

                <tr>

                    <th>Agent</th>

                    <th>ID # </th>

                    <th>Buyer Prospects</th>

                    <th>Rental Prospects</th>

                    <th>Active Rental Listings</th>

                    <th>Active Sales Listings</th>   

                    <th>Rented YTD</th>

                    <th>Sold YTD</th>           

                </tr>';

    if (!empty($authors)) {

        $i = 0;

        foreach ($authors as $author) {

            $author_info = get_userdata($author->ID);



            //count buyer prospects

            $today = getdate();

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'prospect',

                'date_query' => array(

                    array(

                        'year' => $today['year'],

                        'month' => $today['mon'],

                        'day' => $today['mday']

                    )

                ),

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'prospect_type',

                        'value' => 'Buyer',

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'prospect_source',

                        'value' => $author->ID,

                        'compare' => '=',

                    )

                )

            );

            $buyer_prospects = new WP_Query($args);



            //rental prospects

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'prospect',

                'date_query' => array(

                    array(

                        'year' => $today['year'],

                        'month' => $today['mon'],

                        'day' => $today['mday']

                    )

                ),

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'prospect_type',

                        'value' => 'Renter',

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'prospect_source',

                        'value' => $author->ID,

                        'compare' => '=',

                    )

                )

            );

            $renter_prospects = new WP_Query($args);



            //active rental listing

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'rental-listing',

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'agent',

                        'value' => $author->ID,

                        'compare' => '=',

                    )

                )

            );

            $rental_listing = new WP_Query($args);



            //active sales listing

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'sale-listing',

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'agent',

                        'value' => $author->ID,

                        'compare' => '=',

                    )

                )

            );

            $sale_listing = new WP_Query($args);



            //Rented YTD

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'rental-listing',

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'agent',

                        'value' => $author->ID,

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'listing_status',

                        'value' => 'Rented',

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'listing_rentedsold_date',

                        'value' => date('Y-m-d'),

                        'compare' => '>='

                    )

                )

            );

            $rented_ytd = new WP_Query($args);



            //Sold YTD

            $args = array(

                'post_status' => 'publish',

                'posts_per_page' => -1,

                'post_type' => 'sale-listing',

                'meta_query' => array(

                    'relation' => 'AND',

                    array(

                        'key' => 'agent',

                        'value' => $author->ID,

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'listing_status',

                        'value' => 'Sold',

                        'compare' => '=',

                    ),

                    array(

                        'key' => 'listing_rentedsold_date',

                        'value' => date('Y-m-d'),

                        'compare' => '>='

                    )

                )

            );

            $sold_ytd = new WP_Query($args);

            $content .= '<tr>

                         <td>' . $author_info->first_name . ' ' . $author_info->last_name . '</td>

                         <td>' . $author->ID . '</td>

                         <td>' . $buyer_prospects->found_posts . '</td>

                         <td>' . $renter_prospects->found_posts . '</td>

                         <td>' . $rental_listing->found_posts . '</td>

                         <td>' . $sale_listing->found_posts . '</td>

                         <td>' . $rented_ytd->found_posts . '</td>

                         <td>' . $sold_ytd->found_posts . '</td>

                     </tr>';



            $i++;

        }

    } else {



        $content .= '<tr><td colspan="8">No record found</td></tr>';

    }



    $content .= '</table>           

        </div>';

    $style .= '<style>

                            .widefat td, .widefat th {

                                color: #555;

                                text-align: center;

                                padding:15px 1px;

                            }

                            .widefat td, .widefat th {

                                width:85px;

                            }   

                            .wrap h1,.wrap h3 {

                                text-align:center;                              

                            }   

                            .wrap h1 

                            {

                                font-size:20px;

                                color:#971425;

                                margin:0 !important;

                            }   

                            .wrap h3

                            {

                               font-size:15px;

                            }

                            .widefat th

                            {

                               background-color:#971425;

                               color:white;             

                            }

                            table {

                                border-collapse: collapse;

                            }

                            

                            table, td, th {

                                border: 1px solid black;

                            }

                                                

                            .alternate, .striped > tbody > *:nth-child(2n+1), ul.striped > *:nth-child(2n+1) {

                                background-color: #f9f9f9;

                            }

                                                

                            .wrap_logo {

                                text-align:center;                              

                                background-color:#971425;

                                padding:15px;

                                margin:15px 0;                                                                                  

                            }                           

                         </style>';



    $logo = '<div class="wrap_logo"><img src="' . get_field('header_logo', 'option') . '" alt=""></div>';



    if ($_GET['print'] == true) {

        require_once(dirname(__FILE__) . '/html2pdf/html2pdf.class.php');

        $html2pdf = new HTML2PDF('P', 'A4', 'fr');

        $html2pdf->WriteHTML($logo . $content . $style);

        ob_end_clean();

        $html2pdf->Output('report.pdf');

    } else {

        $content .= '<style>.widefat td, .widefat th {

                                color: #555;

                                text-align: center;

                                padding:15px 1px;

                            }

                            .wrap_print {text-align:right;padding:20px;}

                            </style>';

        $content .= '<div class="wrap_print"><a class="button button-primary button-large" target="_blank" href="?page=daily_activity&print=true">Print Report</a></div>';

        echo $content;

    }

}



function lease_expiring_page() {

    if (isset($_GET['reminder'])) {

        $id = $_GET['reminder'];

        $post = get_post($id);

        $agent = get_field("prospect_source", $id);



        $neighbourhood = get_the_terms($id, 'neighbourhood');

        $count = is_array($neighbourhood) ? count($neighbourhood) : 0;

        if (!empty($neighbourhood)) {

            foreach ($neighbourhood as $index => $value) {

                $neigharr .= $value->name;

                if ($index != $count - 1)

                    $neigharr .= ', ';

            }

        }



        $tenantcontact = get_field("cell_phone", $id);

        if (empty($tenantcontact))

            $tenantcontact = get_field("home_phone", $id);

        if (empty($tenantcontact))

            $tenantcontact = get_field("business_phone", $id);



        $lease_expiry = get_field("lease_expiration", $id);



        $body = '<table border="0" cellpadding="0" cellspacing="0" style="width:650px">

                <tbody>

                    <tr>

                        <td scope="col">

                        <table border="0" cellpadding="10" cellspacing="0" style="border-bottom:1px solid #cccccc; height:80px; width:650px">

                            <tbody>

                                <tr>

                                    <td scope="col"><a href="' . get_site_url() . '" target="_blank"><img alt="" src="' . get_field('email_logo', 'option') . '" style="height:200px; width:200px" /></a></td>

                                </tr>

                            </tbody>

                        </table>

        

                        <table border="0" cellpadding="10" cellspacing="0" style="width:650px">

                            <tbody>

                                <tr>

                                    <td scope="col">

                                    <table border="0" cellpadding="10" cellspacing="0" style="width:610px">

                                        <tbody>

                                            <tr>

                                                <td scope="col"><strong>Hi ' . $agent[user_firstname] . ' ' . $agent[user_lastname] . ',</strong></td>

                                            </tr>

                                            <tr>

                                                <td scope="col"><span style="font-size:14px">This is reminder for ' . $post->title . ' property, details given below:</span></td>

                                            </tr>

                                            <tr>

                                                <table style="width:400px;border-collapse: collapse;" cellspacing="0" cellpadding="10" border="1">

                                                <tbody>

                                                    <tr>

                                                        <td>Exp. Date </td>

                                                        <td>' . $lease_expiry . '</td>

                                                    </tr>

                                                    <tr>

                                                        <td>Property Address </td>

                                                        <td>' . get_field("address", $id) . ' ' . get_field("city", $id) . ', ' . get_field("zip_code", $id) . '</td>

                                                    </tr>

                                                    <tr>

                                                        <td>Neighborhood</td>

                                                        <td>' . $neighbourhood . '</td>

                                                    </tr>

                                                    <tr>

                                                        <td>Tenant Contact Info</td>

                                                        <td>' . get_field("first_name", $id) . ' ' . get_field("last_name", $id) . '<br>(C) ' . $tenantcontact . '</td>

                                                    </tr>

                                                    <tr>

                                                        <td>Landlord Contact Info</td>

                                                        <td>' . get_field("landlord", $id) . '<br>(C) ' . get_field("landlord_contact_#", $id) . '</td>

                                                    </tr>                                                                                                   

                                                </tbody>

                                                </table>

                                            </tr>

                                            <tr>

                                                <td scope="col">Thanks</td>

                                            </tr>

                                            <tr>

                                                <td scope="col"><strong>' . get_bloginfo() . '<br />

                                                <a href="' . get_site_url() . '">' . get_site_url() . '</a></strong></td>

                                            </tr>

                                        </tbody>

                                    </table>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

        

                        <table align="left" border="0" cellpadding="10" cellspacing="0" style="width:650px">

                            <tbody>

                                <tr>

                                    <td scope="col"><em>Please do not reply to this email as this is an automated response from ' . get_bloginfo() . '.</em></td>

                                </tr>

                            </tbody>

                        </table>

                        </td>

                    </tr>

                </tbody>

            </table>';





        $to = $agent[user_email];

        //$to = "dstesting2016@gmail.com";

        $subject = 'LEASE EXPIRING';

        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: WOLF PROPERTIES <admin@wolfprop.com>');

        $logfile = dirname(__FILE__) . "/prospectslog.txt";

        if (wp_mail($to, $subject, $body, $headers)) {

            $_SESSION['reminder'] = '<div class="updated notice"><p>Reminder sent successfully.</p></div>';

            wp_redirect('?page=lease_expiring');

            exit;

        } else {

            $_SESSION['reminder'] = '<div class="error notice"><p>Reminder could not send, please try again.</p></div>';

            wp_redirect('?page=lease_expiring');

            exit;

        }

    }



    if (isset($_SESSION['reminder'])) {

        echo $_SESSION['reminder'];

        unset($_SESSION['reminder']);

    }



    $plusdate = date('Y-m-d', strtotime("+30 days"));

    $args = array(

        'post_status' => 'publish',

        'posts_per_page' => -1,

        'post_type' => array('rental-listing'),

        'meta_query' => array(

            'relation' => 'AND',

            array(

                'key' => 'listing_status',

                'value' => 'Rented',

                'compare' => '=',

            ),

            array(

                'key' => 'lease_expiration',

                'value' => date('Y-m-d'),

                'compare' => '>=',

            ),

            array(

                'key' => 'lease_expiration',

                'value' => $plusdate,

                'compare' => '<=',

            )

        )

    );

    $my_query = new WP_Query($args);

    $content = '<div class="wrap">

            <h1>REPORTS: LEASE EXPIRING</h1>

            <h3>Listing Report / Availabe within 30 Days</h3>

            <table class="wp-list-table widefat fixed striped posts">

                <tr>

                    <th>Exp. Date</th>

                    <th>Property ID <br> Address </th>

                    <th>Neighborhood<br> Listing Agent</th>

                    <th>Tenant <br> Contact Info</th>

                    <th>Landlord <br> Contact Info</th>';

    $action1 .='<th class="hiddendiv"></th>';

    $content1 .='</tr>';

    if ($my_query->have_posts()) {

        while ($my_query->have_posts()) : $my_query->the_post();



            $agent = get_field("agent");

            $author_info = is_array($agent) ? get_userdata($agent['ID']) : get_userdata($agent);



            $neigharr = '';

            $ID = get_the_ID();

            $neighbourhood = get_the_terms($ID, 'neighbourhood');

            $count = is_array($neighbourhood) ? count($neighbourhood) : 0;

            if (!empty($neighbourhood)) {

                foreach ($neighbourhood as $index => $value) {

                    $neigharr .= $value->name;

                    if ($index != $count - 1)

                        $neigharr .= ', ';

                }

            }



            $tenantcontact = get_field("cell_phone");

            if (empty($tenantcontact))

                $tenantcontact = get_field("home_phone");

            if (empty($tenantcontact))

                $tenantcontact = get_field("business_phone");



            $lease_expiry = get_field("lease_expiration");



            $content1 .= '<tr>

                         <td>' . date("m/d/Y", strtotime($lease_expiry)) . '</td>

                         <td><a href="' . get_edit_post_link() . '">' . get_the_ID() . '</a><br>' . get_field("address") . ' ' . get_field("city") . ', ' . get_field("zip_code") . '</td>

                         <td><b>' . $neigharr . '</b><br>' . $author_info->first_name . ' ' . $author_info->last_name . '</td>

                         <td>' . get_field("first_name") . ' ' . get_field("last_name") . '<br>(C) ' . $tenantcontact . '</td>                          

                         <td>' . get_field("landlord") . '<br>(C) ' . get_field("landlord_contact_#") . '</td>';

            $action2 .= '<td style="text-align:center"><a class="button button-primary button-large" href="' . get_delete_post_link() . '">Delete</a>

                            <a class="button button-primary button-large" href="?page=lease_expiring&reminder=' . get_the_ID() . '">Reminder</a>

                        </td>';

            $content2 .='</tr>';

        endwhile;

    } else {



        $content2 .= '<tr><td colspan="5">No record found</td></tr>';

    }



    $content2 .= '</table>

        </div>';

    $style .= '<style>

                            .widefat td, .widefat th {

                                color: #555;

                                text-align: center;

                                padding:15px 15px;

                            }

                            .widefat td, .widefat th {

                                width:85px;

                            }

                            .wrap h1,.wrap h3 {

                                text-align:center;

                            }

                            .wrap h1

                            {

                                font-size:20px;

                                color:#971425;

                                margin:0 !important;

                            }

                            .wrap h3

                            {

                               font-size:15px;

                            }

                            .widefat th

                            {

                               background-color:#971425;

                               color:white;

                            }

                            table {

                                border-collapse: collapse;

                            }

                

                            table, td, th {

                                border: 1px solid black;

                            }

        

                            .alternate, .striped > tbody > *:nth-child(2n+1), ul.striped > *:nth-child(2n+1) {

                                background-color: #f9f9f9;

                            }

        

                            .wrap_logo {

                                text-align:center;

                                background-color:#971425;

                                padding:15px;

                                margin:15px 0;

                            }

                                    

                         </style>';



    $logo = '<div class="wrap_logo"><img src="' . get_field('header_logo', 'option') . '" alt=""></div>';



    if ($_GET['print'] == true) {

        echo '<textarea>' . $content . '</textarea>';

        require_once(dirname(__FILE__) . '/html2pdf/html2pdf.class.php');

        $html2pdf = new HTML2PDF('P', 'A4', 'fr');

        $html2pdf->WriteHTML($logo . $content . $content1 . $content2 . $style);

        ob_end_clean();

        $html2pdf->Output('report.pdf');

    } else {

        $content2 .= '<style>.widefat td, .widefat th {

                                color: #555;

                                text-align: center;

                                padding:15px 1px;

                            }

                            .wrap_print {text-align:right;padding:20px;}

                            </style>';

        $content2 .= '<div class="wrap_print"><a class="button button-primary button-large" target="_blank" href="?page=lease_expiring&print=true">Print Report</a></div>';

        echo $content . $action1 . $content1 . $action2 . $content2;

    }

}



function clickatell_newsletter() {

    //Send Custom Message

    if (isset($_POST['send'])) {

        $phones = $_POST['phone'];

        if (!empty($phones)) {

            $myfile = fopen("clickatelllogs.txt", "a+") or die("Unable to open file!");

            $message['content'] = $_POST['message'];

            $message['to'] = $phones;

            $message['from'] = get_field('clickatell_reply_phone_number', 'option');





            $message_json = json_encode($message);

            //'{"content": "Test Message Text", "to": ["+16467737624"], "from": "+16466652159"}';

            //print_r($message_json);die;



            fwrite($myfile, "\n" . "Custom Message");



            //clickatell api to send message

            $curl = curl_init();

            curl_setopt_array($curl, array(

                CURLOPT_URL => "https://platform.clickatell.com/messages",

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_ENCODING => "",

                CURLOPT_MAXREDIRS => 10,

                CURLOPT_TIMEOUT => 30,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST => "POST",

                CURLOPT_POSTFIELDS => $message_json,

                CURLOPT_HTTPHEADER => array(

                    "accept: application/json",

                    "authorization: UYhYjXTqRC6vUpiuI207Dg==",

                    "cache-control: no-cache",

                    "content-type: application/json",

                    "postman-token: 6df14129-77a3-bb4f-c4db-89b65218e02c"

                ),

            ));





            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                $responsemessage = "Something went wrong, please try again.";

                fwrite($myfile, "\n" . "cURL Error #:" . $err);

                print_r($err);

            } else {

                $responsemessage = "Message has been sent successfully.";

                fwrite($myfile, "\n" . $response);

                print_r($response);

            }

        }

    }



    global $wpdb;





    //add new phone number

    if (isset($_POST['proceed'])) {

        $phone_number = $_POST['phonenumber'];

        $group_name = $_POST['group'];

        if (!preg_match('/([0-9]{9})/', $phone_number) || $group_name == '') {

            $responsemessage = "Invalid phone number or Empty fields.";

        } else {

            $phone_number = "+1" . $phone_number;



            $table_name = $wpdb->prefix . "phone_texting";

            $pending = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE phone_number = $phone_number");

            $ip = $_SERVER['REMOTE_ADDR'];



            if ($pending == 0) {

                $retun = $wpdb->insert($table_name, array('phone_number' => $phone_number, 'ip' => $ip, 'status' => 1, 'created' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")));

                if ($retun) {

                    $id = $wpdb->insert_id;

                    $table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";

                    $wpdb->insert($table_name1, array('group_id' => $group_name, 'phone_number_id' => $id, 'created' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")));

                    $responsemessage = "Phone number added successfully.";

                } else {

                    $responsemessage = "Something went wrong, please try again later.";

                }

            } else {

                $responsemessage = "Phone number is already subscribed.";

            }

        }

    }



    /*     * ******************** */

    $table_name = $wpdb->prefix . "phone_texting";

    //Delte subscriber

    if (!empty($_GET['delete'])) {

        $id = $_GET['delete'];

        $phone = $wpdb->get_results("DELETE FROM $table_name where id = $id");

    }



    if (!empty($_GET['activate'])) {

        $id = $_GET['activate'];

        $wpdb->update($table_name, array('code' => '', 'status' => 1, 'modified' => date("Y-m-d H:i:s")), array('id' => $id));

        $responsemessage = "Phone number successfully activated.";

    }



    $prefix = $wpdb->prefix;



    //pagination

    $rec_limit = 10;

    $phonecount = $wpdb->get_results("SELECT count(*) as number FROM $table_name");

    $rec_count = $phonecount[0]->number;



    if (isset($_GET["pagenumber"]))

        $page = (int) $_GET["pagenumber"];

    else

        $page = 1;

    $offset = ($page * $rec_limit) - $rec_limit;



    $phones = $wpdb->get_results("SELECT *, p.id as phoneid FROM $table_name as p left join " . $prefix . "clickatell_group_phonenumber as gp on gp.phone_number_id = p.id left join " . $prefix . "clickatell_group as g on g.id = gp.group_id group by p.phone_number order by g.name asc LIMIT $offset, $rec_limit");

    ?>    

    <div class="wrap report">

        <h1>CLICKATELL: Texting Subscribers</h1>      

        <?php

        if (!empty($responsemessage)) {

            echo '<div class="updated" style="border-color: #3ba1da;"><p>' . $responsemessage . '</p></div>';

            unset($responsemessage);

        }

        ?>

        <div id="tnp-body" class="half">

            <form action="" method="post">

                <table class="form-table">

                    <tbody>

                      <!--  <tr valign="top">

                            <th style="width:140px">New Phone Number </th>

                            </tr>-->

                        <tr>

                            <td>

                                <input placeholder="New Phone Number" name="phonenumber" maxlength="10"  onkeyup="if (/[^0-9\.]/g.test(this.value))

                                                this.value = this.value.replace(/[^0-9\.]/g, '')" size="24" value="" type="text">    

                                <select name="group" id="group">

                                    <option value="">Please Select group name</option>

                                    <?php

                                    $table_names = $wpdb->prefix . "clickatell_group";

                                    $groups = $wpdb->get_results("SELECT * FROM $table_names");

                                    foreach ($groups as $group) {

                                        ?>

                                        <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>

                                    <?php } ?>                               

                                </select>                



                                <input class="button button-primary" value="Proceed" name="proceed" type="submit">

                            </td>

                        </tr>

                    </tbody>

                </table>

            </form>

        </div>

        <div id="tnp-body" class="half">

            <?php

            //pagination

            $adjacents = "2";

            $page = ($page == 0 ? 1 : $page);



            $start = ($page - 1) * $rec_limit;







            $prev = $page - 1;



            $next = $page + 1;

            $setLastpage = ceil($rec_count / $rec_limit);



            $lpm1 = $setLastpage - 1;







            $setPaginate = "";

            if ($setLastpage > 1) {



                $setPaginate .= "<ul class='setPaginate'>";



                $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";



                if ($setLastpage < 7 + ($adjacents * 2)) {



                    for ($counter = 1; $counter <= $setLastpage; $counter++) {



                        if ($counter == $page)

                            $setPaginate.= "<li><a class='current_page'>$counter</a></li>";

                        else

                            $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$counter'>$counter</a></li>";

                    }

                }



                elseif ($setLastpage > 5 + ($adjacents * 2)) {



                    if ($page < 1 + ($adjacents * 2)) {



                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {



                            if ($counter == $page)

                                $setPaginate.= "<li><a class='current_page'>$counter</a></li>";

                            else

                                $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$counter'>$counter</a></li>";

                        }



                        $setPaginate.= "<li class='dot'>...</li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$lpm1'>$lpm1</a></li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$setLastpage'>$setLastpage</a></li>";

                    }



                    elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=1'>1</a></li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=2'>2</a></li>";



                        $setPaginate.= "<li class='dot'>...</li>";



                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {



                            if ($counter == $page)

                                $setPaginate.= "<li><a class='current_page'>$counter</a></li>";

                            else

                                $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$counter'>$counter</a></li>";

                        }



                        $setPaginate.= "<li class='dot'>..</li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$lpm1'>$lpm1</a></li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$setLastpage'>$setLastpage</a></li>";

                    }



                    else {



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=1'>1</a></li>";



                        $setPaginate.= "<li><a href='?page=clickatell&pagenumber=2'>2</a></li>";



                        $setPaginate.= "<li class='dot'>..</li>";



                        for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {

                            if ($counter == $page)

                                $setPaginate.= "<li><a class='current_page'>$counter</a></li>";

                            else

                                $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$counter'>$counter</a></li>";

                        }

                    }

                }



                if ($page < $counter - 1) {



                    $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$next'>Next</a></li>";



                    $setPaginate.= "<li><a href='?page=clickatell&pagenumber=$setLastpage'>Last</a></li>";

                } else {



                    $setPaginate.= "<li><a class='current_page'>Next</a></li>";



                    $setPaginate.= "<li><a class='current_page'>Last</a></li>";

                }

                $setPaginate.= "</ul>\n";

            }

            echo $setPaginate;

            ?>

        </div>

        <form method="POST" action="">

            <table class="wp-list-table widefat fixed striped posts">

                <tr>

                    <th>
                        <input type="checkbox" id="selectall">
                    </th>

                    <th>Phone Number</th>

                    <th>IP Address</th>

                    <th>Status</th>

                    <th>Group</th>

                    <th></th>

                </tr>



                <?php

                if (!empty($phones)) {
                    

                    foreach ($phones as $phone) {

                        $activate = '';

                        $status = ($phone->status == 1) ? "<p class='label-success label'>Active</p>" : "<p class='label label-danger'>Pending</p>";



                        if (!empty($phone->name)) {

                            $name = ($phone->alert == 1) ? "<p class='label-success label'>$phone->name</p>" : "<p class='label label-danger'>$phone->name</p>";

                        } else {

                            $name = '';

                        }



                        if ($phone->status == 0) {

                            $activate = '<a class="button button-primary button-large" href="?page=clickatell&activate=' . $phone->phoneid . '">Activate</a>';

                        }



                        echo '<tr>

                            <td width="20"><input type="checkbox" value="' . $phone->phone_number . '" name="phone[]" class="on-all-select"></td>

                            <td>' . $phone->phone_number . '</td>

                            <td>' . $phone->ip . '</td>

                            <td>' . $status . '</td>

                            <td>' . $name . '</td>

                            <td>' . $activate . '  <a class="button button-primary button-large" href="?page=clickatell&delete=' . $phone->phoneid . '">Delete</a></td>';

                    }

                } else {

                    '<tr><td colspan="3">No Subscriber</td></tr>';

                }

                ?>



            </table>        

            <div class="wrap report">

                <textarea name="message" style="width:400px;height:200px;" placeholder="Custom Message"></textarea></br></br>

                <input type="submit" value="Send" name="send" class="button button-primary button-large" />        

            </div> 

        </form>

    </div>







    <?php

}



/* * ************Clicktell group functionality ********************* */



function clickatell_group_page() {

    global $wpdb;

    ini_set('max_execution_time', '2000');

    //Send Custom Message

    if (isset($_POST['send'])) {



        $group = $_POST['group'];

        $group = implode(",", $group);

        $message = $_POST['message'];



        $table_name = $wpdb->prefix . "phone_texting";





        $result = $wpdb->get_results("SELECT distinct phone_number,group_id FROM wp_clickatell_group g, wp_clickatell_group_phonenumber gp, wp_phone_texting pt WHERE g.id = gp.group_id AND gp.phone_number_id = pt.id AND g.id IN ($group) order by pt.id desc");

        $phone_numbers = $result; //$wpdb->get_results($result);



        foreach ($phone_numbers as $phonen) {

            if (!empty($group)) {

                $phonenumber = $phonen->phone_number;

                

                $groups_id = $phonen->group_id;

                 $wpdb->query("INSERT INTO wp_temp_number_request (phnumber, status, group_id, message) VALUES ('$phonenumber', '0', '$groups_id', '$message ')");

            }

        }

        echo 'Message request has been processed.';

    }





    $table_name = $wpdb->prefix . "clickatell_group";



    if (!empty($_GET['delete'])) {

        $id = $_GET['delete'];

        $group = $wpdb->get_results("DELETE FROM $table_name where id = $id");



        $table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";

        $wpdb->get_results("DELETE FROM $table_name1 WHERE group_id = $id");

    }



    $groups = $wpdb->get_results("SELECT * FROM $table_name");

    ?>

    <div class="wrap report">        

        <h1>CLICKATELL: Groups</h1>  

        <?php

        if (!empty($responsemessage)) {

            echo '<div class="updated" style="border-color: #3ba1da;"><p>' . $responsemessage . '</p></div>';

            unset($responsemessage);

        }

        ?>

        <div class="addgroup">

            <a class="button button-primary button-large" href="?page=clickatell_add_group">Add New Group</a>

        </div>

        <form method="POST" action="">

            <table class="wp-list-table widefat fixed striped posts">

                <tr>

                    <th></th>

                    <th>S. NO</th>

                    <th>Name</th>

                    <th>Alerts</th>                

                    <th></th>

                </tr>

                <?php

                if (!empty($groups)) {

                    $i = 1;

                    foreach ($groups as $group) {

                        $activate = '';

                        $status = ($group->alert == 1) ? "<p class='label label-success'>Active</p>" : "<p class='label label-danger'>Inactive</p>";



                        echo '<tr>

                            <td width="20"><input type="checkbox" value="' . $group->id . '" name="group[]"></td>

                            <td width="20">' . $i . '</td>

                            <td>' . $group->name . '</td>                            

                            <td>' . $status . '</td>

                            <td><a class="button button-primary button-large" href="?page=clickatell_add_group&id=' . $group->id . '">Edit</a> ';

                        if ($group->id != 1) {

                            echo '<a class="button button-primary button-large" href="?page=clickatell_groups&delete=' . $group->id . '">Delete</a>';

                        }

                        echo '</td>';



                        $i++;

                    }

                } else {

                    '<tr><td colspan="3">No Group</td></tr>';

                }

                ?>            

            </table>

            <div class="wrap report">

                <textarea name="message" style="width:400px;height:200px;" placeholder="Custom Message"></textarea></br></br>

                <input type="submit" value="Send" name="send" class="button button-primary button-large" />        

            </div> 

        </form>

    </div>    

    <?php

}



function clickatell_add_group_page() {

    global $wpdb;

    if (isset($_POST['submit'])) {

        $id = $_POST['id'];

        $form_name = $_POST['name'];

        $form_description = $_POST['description'];

        $form_alert = $_POST['alert'];

        $form_phonenumbers = $_POST['phonenumbers'];

        $table_name = $wpdb->prefix . "clickatell_group";







        if (empty($id)) {

            $retun = $wpdb->insert($table_name, array('name' => $form_name, 'description' => $form_description, 'alert' => $form_alert, 'created' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")));

            if ($retun) {

                if (!empty($form_phonenumbers)) {

                    $group_id = $wpdb->insert_id;

                    $table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";

                    foreach ($form_phonenumbers as $phonenumber) {

                        $retun = $wpdb->insert($table_name1, array('group_id' => $group_id, 'phone_number_id' => $phonenumber, 'created' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")));

                    }

                }

                $responsemessage = "Group has been added successfully.";

            } else {

                $responsemessage = "Something went wrong, please try again later.";

            }

        } else {

            $retun = $wpdb->update($table_name, array('name' => $form_name, 'description' => $form_description, 'alert' => $form_alert, 'modified' => date("Y-m-d H:i:s")), array('id' => $id));

            if ($retun) {



                $group_id = $id;

                $table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";

                $wpdb->get_results("DELETE FROM $table_name1 WHERE group_id = $group_id");



                if (!empty($form_phonenumbers)) {

                    foreach ($form_phonenumbers as $phonenumber) {

                        $retun = $wpdb->insert($table_name1, array('group_id' => $group_id, 'phone_number_id' => $phonenumber, 'created' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")));

                    }

                }

                $responsemessage = "Group has been updated successfully.";

            } else {

                $responsemessage = "Something went wrong, please try again later.";

            }

        }

    }





    $id = '';

    $name = '';

    $description = '';

    $alert = 3;

    $phone_numbers = '';

    if (!empty($_GET['id'])) {

        $id = $_GET['id'];

        $table_name = $wpdb->prefix . "clickatell_group";

        $group = $wpdb->get_results("SELECT * FROM $table_name where id = $id");

        $name = $group[0]->name;

        $description = $group[0]->description;

        $alert = $group[0]->alert;

        $table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";

        $table_name2 = $wpdb->prefix . "phone_texting";

        $phone_numbers = $wpdb->get_results("SELECT * FROM $table_name1 where  group_id = $id ");

        $phonenumbers = array();

        if (!empty($phone_numbers)) {

            foreach ($phone_numbers as $phone_number) {

                $phonenumbers[] = $phone_number->phone_number_id;

            }

        }

        if(!empty($phonenumbers)){            

            $ids = implode(',', $phonenumbers);     

            echo 'hello';

        }

        else

        {            

            $ids = 'NULL';  

        }    



        $table_name = $wpdb->prefix . "phone_texting";





        $phone_numbers = $wpdb->get_results("SELECT * FROM $table_name where id NOT IN(

 SELECT phone_number_id

 FROM wp_clickatell_group_phonenumber

) AND status = 1 OR id IN( $ids ) ORDER BY id desc");

    } else {

        

        $table_name = $wpdb->prefix . "phone_texting";





        $phone_numbers = $wpdb->get_results("SELECT * FROM $table_name where id NOT IN(

 SELECT phone_number_id

 FROM wp_clickatell_group_phonenumber

) AND status = 1 ORDER BY id desc" );

    }

    ?>

    <div class="wrap report">

        <h1>CLICKATELL: <?php echo empty($id) ? "Add Group" : "Edit Group"; ?></h1>

        <div class="addgroup">

            <a class="button button-primary button-large" href="?page=clickatell_groups">Groups</a>

            <a class="button button-primary button-large" href="?page=clickatell_add_group">Add New Group</a>

        </div>

        <?php

        if (!empty($responsemessage)) {

            echo '<div class="updated" style="border-color: #3ba1da;"><p>' . $responsemessage . '</p></div>';

            unset($responsemessage);

        }

        ?>

        <form method="post" name="add_group">

            <ul class="form-style-1">

                <input type="hidden" name="id" value="<?php echo $id; ?>" >

                <li>

                    <label>Name <span class="required">*</span></label>

                    <input type="text" name="name" class="field-long" value="<?php echo $name; ?>">

                </li>

                <li>

                    <label>Description </label>

                    <textarea name="description" class="field-long field-textarea"><?php echo $description; ?></textarea>

                </li>

                <li>

                    <label>Alerts <span class="required">*</span></label>

                    <select name="alert" class="field-select">

                        <option value="">--Select--</option>

                        <option value="1" <?php echo ($alert == 1) ? "selected='selected'" : ""; ?>>Active</option>

                        <option value="0" <?php echo ($alert == 0) ? "selected='selected'" : ""; ?>>Inactive</option>

                    </select>

                </li>

                <li>

                    <label>Phone Numbers </label>



                    <?php

                    foreach ($phone_numbers as $phone_number) {

                        ?>

                        <div class="divphonenumber">

                            <input type='checkbox' name='phonenumbers[]' <?php echo in_array($phone_number->id, $phonenumbers) ? 'checked="checked"' : ""; ?> value='<?php echo $phone_number->id; ?>' > <span><?php echo $phone_number->phone_number; ?></span>

                        </div>    



                        <?php

                    }

                    ?>



                </li>

                <li>

                    <input type="submit" value="Save" name="submit">

                </li>

            </ul>

        </form>

    </div>    

    <?php

}

?>