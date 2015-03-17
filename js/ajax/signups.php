<?php

     require( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
     
     global $wpdb, $woocommerce;
     
     date_default_timezone_set('America/Los_Angeles'); 

     $dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
     $dateTo = date('Y-m-d', strtotime($_POST['dateTo']));               
               
               
             // SQL
             
               $free_pound_signups = $wpdb->get_var("SELECT count(subscription_id) 
                                                       FROM {$wpdb->prefix}subscriptions
                                                       WHERE subscription_start
                                                       BETWEEN '$dateFrom'
                                                           AND '$dateTo'
                                                       AND free_pound = 'true'
                                                       ");
                                                  
               $subscriptions_signups = $wpdb->get_var("SELECT count(subscription_id) 
                                                            FROM {$wpdb->prefix}subscriptions
                                                            WHERE subscription_start
                                                            BETWEEN '$dateFrom'
                                                                AND '$dateTo'
                                                            AND free_pound != 'true'
                                                            ");
                                                            
               $referral_signups = $wpdb->get_var("SELECT count(subscription_id) 
                                                            FROM {$wpdb->prefix}subscriptions
                                                            WHERE subscription_start
                                                            BETWEEN '$dateFrom'
                                                                AND '$dateTo'
                                                            AND source like '%referralsubscriptioncheckout%'
                                                            ");               
               
               $total_signups = $free_pound_signups + $subscriptions_signups + $referral_signups;

          ?>   
          <?php $columns = array("Free Pounds", "Subscriptions", "Referral"); ?> 
          <?php $rows = array("Signups" =>   array($free_pound_signups, $subscriptions_signups, $referral_signups),
                              "Total"   =>   array(null, null, $total_signups)); ?>
          
          <?php if ($total_signups) : ?>
               <div>
                    <h1>Signups</h1>
                    <table class="widefat fixed">
                         <thead>
                              <tr>
                                   <th></th>
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