<?php
	
	require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

	global $wpdb;
	
	$dateFrom = date("Y-m-d", strtotime($_POST['dateFrom']));
	$dateTo = date("Y-m-d", strtotime($_POST['dateTo']));

     $affiliates = get_option('marketing_affiliates');
     $checkouts = array('freepound', '20off', 'referral');
  
     foreach ($affiliates as $affiliate) :
          foreach ($checkouts as $checkout) :

          if ($checkout == 'referral') :
               $affiliate_orders = $wpdb->get_var("SELECT COUNT( subscription_id )
                                                       FROM {$wpdb->prefix}subscriptions
                                                       WHERE source LIKE '%referral%'
                                                  ");
          
          else: 
          $affiliate_orders = $wpdb->get_var("SELECT COUNT( subscriptions.order_id ) 
                                                  FROM  {$wpdb->prefix}subscriptions subscriptions
                                                  JOIN  {$wpdb->prefix}woocommerce_order_items items
                                                       ON subscriptions.order_id = items.order_id
                                                  WHERE subscriptions.order_id !=  ''
                                                  AND subscriptions.source =  '$affiliate'
                                                  AND items.order_item_type =  'coupon'
                                                  AND items.order_item_name = '$checkout'
                                             ");
          endif;
               // Save individual checkout and customers to row array
               $rows[$affiliate][$checkout] = $affiliate_orders;
 
          endforeach;                          
     endforeach;
     ?>
     
     <?php $columns = array("Affiliate", "Free Pounds", "Subscriptions", "Referrals"); ?> 
            
     <?php if ($rows) : ?>
               <div>
                    <h1>Affiliates</h1>
                    <table class="widefat fixed">
                         <thead>
                              <tr>
                              <?php foreach ($columns as $column) : ?>
                                   <th class="column"> <?php echo $column ?> </th>
                              <?php endforeach; ?>
                              </tr>
                         </thead>
                         <tbody>
                              <?php $counter = 0; foreach ($rows as $row => $values) : ?>
                                   <?php if ($counter % 2 == 0 ) {  
                                   echo  "<tr valign=\"center\" class=\"alternate\">";
                              }
                              else {
                                   echo "<tr>";
                              } ?>
                                   <td><?php echo $row ?></td>
                                   <?php foreach ($values as $value) : ?>
                                   <td><?php echo $value ?></td>
                                   <?php endforeach; ?>
                                   </tr>
                                   
                              <?php $counter++; endforeach; ?>
                         </tbody>
                    </table>
               </div>
          <?php else : ?> 
               <h1>No Data</h1> 
          <?php endif; ?>
