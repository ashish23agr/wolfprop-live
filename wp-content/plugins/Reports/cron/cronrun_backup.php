<?php 
include("../../../../wp-load.php");
global $wpdb;
 $from = get_field('clickatell_reply_phone_number', 'option');
 $from = str_replace('+', '', $from);

// $message = "Test Message";
// $numbers = array("17189150271","19173312036");
// $data = json_encode(array("content"=>$message,"to"=>$numbers,"from"=> $from));
 
// $curl = curl_init();
//     curl_setopt_array($curl, array(
//     CURLOPT_URL => "https://platform.clickatell.com/messages",
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_CUSTOMREQUEST => "POST",
//     CURLOPT_POSTFIELDS => $data,
//     CURLOPT_HTTPHEADER => array(
//         "accept: application/json",
//         "Authorization: UYhYjXTqRC6vUpiuI207Dg==",
//         "content-type: application/json",
//     ),
//     ));

//     $response = curl_exec($curl);
//     $arr = json_decode(($response), 1);

// echo "<pre>";
// print_r($arr);
// die;
 
/* Get record from temp table that is select by client */
$result = $wpdb->prepare("select * from `wp_temp_number_request` WHERE `status` =%d LIMIT 30",0);
$phone_numbers = $wpdb->get_results($result);
/*  echo '<pre>';
 print_r($phone_numbers);
 exit; */
if (empty($phone_numbers)) {
    echo "No Result found.";
    /* Empty table if all entry status is 1 */
    $result = $wpdb->query("TRUNCATE  wp_temp_number_request");
} else {
  /*    $i = 0; */ 
    ?>
    <!-- Table view about messages that which message has been sent !-->
    <table width="100%" colspan="1" border="1" align="center">
        <tr>
            <th>Api Message Id</th> 
            <th>Accepted</th>  
            <th>To</th> 
            <th>Date</th> 
        </tr>
        <?php
        foreach ($phone_numbers as $getdata) {
            $status = $getdata->status;
            $phnumber = $getdata->phnumber;
            $group_id = $getdata->group_id;
            $gmessage = $getdata->message; 
            $id = $getdata->id;
               $phnumber = str_replace('+', '', $phnumber);
               
            if (!empty($group_id) && !empty($phnumber) && !empty($group_id)) {

                /* check status */
                if ($status == '0') {
                    /* Clickatell Api call by xml */
                    //$from = get_field('clickatell_reply_phone_number', 'option');
                    $_XMLDATA = '<SendMessageRequest>';
                    $_XMLDATA .= '<Content>'.$gmessage.'</Content>';
                    $_XMLDATA .= '<To>'.$phnumber.'</To>';
                    $_XMLDATA .= '<From>'.$from.'</From>';
                    $_XMLDATA .= '</SendMessageRequest>';
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://platform.clickatell.com/messages",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $_XMLDATA,
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/xml",
                            "Authorization: UYhYjXTqRC6vUpiuI207Dg==",
                            "content-type: application/xml",
                        ),
                    ));
                    $response = curl_exec($curl);
                                      
                    $err = curl_error($curl);
                    $rv = simplexml_load_string($response);
                    $arr = json_decode(json_encode($rv), 1);
                 
                    ?>
 
                    <tr>
                        <td align="center"><?php echo $arr['Messages']['Message']['ApiMessageId']; ?></td> 
                        <td align="center"><?php echo $arr['Messages']['Message']['Accepted']; ?></td>  
                        <td align="center"><?php echo $arr['Messages']['Message']['To']; ?></td> 
                        <td align="center"><?php echo date("l jS \of F Y h:i:s A"); ?></td> 
                    </tr>
                    <?php
                    if ($err) {
                        $responsemessage = "Something went wrong, please try again.";
                    } else {
                        $responsemessage = "Message has been sent successfully.";
                        /* Update status in database */
                        $result = $wpdb->query("UPDATE wp_temp_number_request SET status ='1' WHERE id = '$id'");
                    }
                    curl_close($curl);
                }
            }
           /*   $i++;
            if ($i == 75) {
                break;
            }  */
        }
        ?>
    </table>
    <?php
}
die;
?>