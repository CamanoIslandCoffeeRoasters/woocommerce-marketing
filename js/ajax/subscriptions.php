<?php

     require( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
     
     global $wpdb, $woocommerce;
     
     date_default_timezone_set('America/Los_Angeles'); 

     $dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
     $dateTo = date('Y-m-d', strtotime($_POST['dateTo']));               
               
               
             // SQL
             
               $cancelations = $wpdb->get_var("SELECT COUNT(subscription_id) 
                                                  FROM {$wpdb->prefix}subscriptions
                                                   WHERE cancel_date
                                                   BETWEEN '$dateFrom'
                                                       AND '$dateTo'
                                                   AND status = 'canceled'
                                                  ");
                                                  
               $reactivations = $wpdb->get_var("SELECT COUNT(subscription_id) 
                                                  FROM {$wpdb->prefix}subscriptions_notes
                                                   WHERE DATE(note_date)
                                                   BETWEEN '$dateFrom'
                                                       AND '$dateTo'
                                                   AND note_type = 'reactivated'
                                                  ");
                                                  
               $shipments = $wpdb->get_var("SELECT COUNT(ID) 
                                             FROM {$wpdb->posts}
                                             WHERE DATE(post_date)
                                             BETWEEN '$dateFrom'
                                                  AND '$dateTo'
                                             AND post_type = 'shop_order'
                                             AND post_status = 'wc-completed'
                                             ");                               

          ?>   
          <?php $columns = array("Cancelations", "Reactivations", "Shipments"); ?> 
          <?php $rows = array("Subscriptions" =>  array($cancelations, $reactivations, $shipments)); ?>
          
          <?php if (($cancelations) || ($reactivations) || ($shipments)) : ?>
               <div>
                    <h1>Subscriptions</h1>
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