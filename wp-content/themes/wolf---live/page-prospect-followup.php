<?php

// To send email to agent for prospect
  $logfile = dirname(__FILE__) . "/prospectslog.txt";
$myfile = fopen($logfile, "a+") or die("Unable to open file!");
$txt = 'Date: ' . date('Y-m-d') . PHP_EOL;
fwrite($myfile, $txt);
fclose($myfile);

$args = array(
    'post_type' => array('prospect'),
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'email_alerts_on',
            'value' => 'Yes',
            'compare' => '='
        ),
        array(
            'key' => 'follow-up_date',
            'value' => date('Y-m-d'),
            'compare' => '='
        )
    )
);
$prospects = new WP_Query($args);
if ($prospects->have_posts()) {
    while ($prospects->have_posts()) : $prospects->the_post();

        $agent = get_field("prospect_source");
        $state = get_field('state');

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
												<td scope="col"><span style="font-size:14px">This is followup for ' . get_the_title() . ' prospect, prospect details given below:</span></td>
											</tr>
											<tr>
												<table style="width:400px;border-collapse: collapse;" cellspacing="0" cellpadding="10" border="1">
												<tbody>
													<tr>
														<td>Prospect Type</td>
														<td>' . get_field('prospect_type') . '</td>	
													</tr>													
													<tr>
														<td>First Name</td>
														<td>' . get_field('first_name') . '</td>	
													</tr>
													<tr>
														<td>Last Name</td>
														<td>' . get_field('last_name') . '</td>	
													</tr>
													<tr>
														<td>Business Phone</td>
														<td>' . get_field('business_phone') . '</td>	
													</tr>
													<tr>
														<td>Cell Phone</td>
														<td>' . get_field('cell_phone') . '</td>	
													</tr>
													<tr>
														<td>Home Phone</td>
														<td>' . get_field('home_phone') . '</td>	
													</tr>
													<tr>
														<td>Address</td>
														<td>' . get_field('address') . '</td>	
													</tr>
													<tr>
														<td>City</td>
														<td>' . get_field('city') . '</td>	
													</tr>
													<tr>
														<td>State</td>
														<td>' . $state->name . '</td>	
													</tr>
													<tr>
														<td>Zip</td>
														<td>' . get_field('zip') . '</td>	
													</tr>
													<tr>
														<td>Email Address</td>
														<td>' . get_field('email_address') . '</td>	
													</tr>
													<tr>
														<td>Pets</td>
														<td>' . get_field('pets') . '</td>	
													</tr>
													<tr>
														<td>Bed Rooms</td>
														<td>' . get_field('bed_rooms') . '</td>	
													</tr>
													<tr>
														<td>Bathrooms</td>
														<td>' . get_field('bathrooms') . '</td>	
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
        $subject = 'Prospect Followup';
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: WOLF PROPERTIES <admin@wolfprop.com>');
        $logfile = dirname(__FILE__) . "/prospectslog.txt";
        if (wp_mail($to, $subject, $body, $headers)) {

            echo 'Success';
            $myfile = fopen($logfile, "a+") or die("Unable to open file!");
            $txt = 'Date: ' . date('Y-m-d') . ' Agent: ' . $to . ' Status: Sent' . PHP_EOL;
            fwrite($myfile, $txt);
            fclose($myfile);
        } else {
            echo 'Failed';
            $myfile = fopen($logfile, "a+") or die("Unable to open file!");
            $txt = 'Date: ' . date('Y-m-d') . ' Agent: ' . $to . ' Status: Failed' . PHP_EOL;
            fwrite($myfile, $txt);
            fclose($myfile);
        }

    endwhile;
}
?>