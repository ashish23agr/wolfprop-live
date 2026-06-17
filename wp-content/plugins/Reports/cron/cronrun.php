<?php 
include("../../../../wp-load.php");
global $wpdb;
 $from = get_field('clickatell_reply_phone_number', 'option');
 $from = str_replace('+', '', $from);
/* Get record from temp table that is select by client */
$result = $wpdb->prepare("select * from `wp_temp_number_request` WHERE `status` =%d LIMIT 100",0);
$phone_numbers = $wpdb->get_results($result);
//echo '<pre>';print_r($phone_numbers);
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
        $arr = array();
        foreach ($phone_numbers as $getdata) {
            
             $phnumber = $getdata->phnumber;
             $phnumber = str_replace('+', '', $phnumber);

             $arr['id'][] = $getdata->id;
             $arr['phonenumber'] []= $phnumber;
             $arr['message'] = $getdata->message;
            
        }
        
        $data = json_encode(array("content"=>$arr['message'] ,"to"=>$arr['phonenumber'],"from"=> $from));
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://platform.clickatell.com/messages",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: UYhYjXTqRC6vUpiuI207Dg==",
            "content-type: application/json",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        //print_r($response); exit;
        $arr = json_decode($response, 1);
//        echo '<pre>'; print_r($arr);
        foreach ($arr['messages'] as $updateRows) { echo "<pre>"; print_r($updateRows); ?>
                <tr>
                    <td align="center"><?php echo $updateRows['apiMessageId']; ?></td> 
                    <td align="center"><?php echo $updateRows['accepted']; ?></td>  
                    <td align="center"><?php echo $updateRows['to']; ?></td> 
                    <td align="center"><?php echo date("l jS \of F Y h:i:s A"); ?></td> 
                </tr>
        <?php
                if ($err) {
                        $responsemessage = "Something went wrong, please try again.";
                    } else {
                        $responsemessage = "Message has been sent successfully.";
                        
                        /* Update status in database */
                        if($updateRows['accepted'] ==1 and !empty($updateRows['accepted']))
                        {
                           $result = $wpdb->query("UPDATE wp_temp_number_request SET status ='1' WHERE phnumber = ".$updateRows['to']."");
                        }
                    //echo $wpdb->last_query;
                    }
                    echo $responsemessage;
                    curl_close($curl);
        }
        ?>
       

    </table>
    <?php
}
die;
?>