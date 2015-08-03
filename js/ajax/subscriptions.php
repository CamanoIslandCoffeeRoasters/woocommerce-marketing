<?php

     require( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
     
     global $wpdb, $woocommerce;
     
     date_default_timezone_set('America/Los_Angeles'); 

     $dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
     $dateTo = date('Y-m-d', strtotime($_POST['dateTo']));
                    
     $dateFromSQL = date("Y-m-d", strtotime($dateFrom) - 60 * 60 * 24);
     $dateFromSQL = $dateFromSQL . " 20:45:01";
     $dateToSQL = $dateTo . " 20:45:00";          
               
             // SQL
                                                  

           $reactivations = array("Reactivations" => $wpdb->get_results("SELECT added_by as col, count(subscription_id) as row  
                                                            FROM {$wpdb->prefix}subscriptions_notes
                                                            WHERE note_date
                                                            BETWEEN '$dateFromSQL'
                                                                AND '$dateToSQL'
                                                            AND note_type = 'reactivate'
                                                            GROUP BY (added_by)
                                                            "));

           $cancelations = array("Cancelations" => $wpdb->get_results("SELECT cancel_reason as col, count(subscription_id) as row  
                                                            FROM {$wpdb->prefix}subscriptions
                                                            WHERE cancel_date
                                                            BETWEEN '$dateFromSQL'
                                                                AND '$dateToSQL'
                                                            AND status = 'canceled'
                                                            GROUP BY (cancel_reason)
                                                            "));
                                                            
           $shipments = array("Shipments" => $wpdb->get_results("SELECT post_status as col, COUNT(ID) as row
                                                             FROM {$wpdb->posts}
                                                             WHERE post_date
                                                             BETWEEN '$dateFromSQL'
                                                                  AND '$dateToSQL'
                                                             AND post_type = 'shop_order'
                                                             AND post_status = 'wc-completed'
                                                             "));
                                             
           $sources = array("Sources" => $wpdb->get_results("SELECT DISTINCT(source) as col, COUNT(subscription_id) as row
                                                                FROM {$wpdb->prefix}subscriptions
                                                                WHERE status = 'active'
                                                                "));
               ?>
                <?php $contents = array_merge($reactivations, $cancelations, $shipments); ?>
            <div>
            <?php foreach ($contents as $title => $data) : ?>
               <?php $total = 0; ?>
               <div style="width:25%;">
                    <table style="width:100%;" class="widefat striped">
                         <thead>
                              <tr>
                                   <th class="column"> <b><?php echo $title ?></b></th>
                                   <th></th>
                              </tr>
                         </thead>
                         <tbody>
                              <?php foreach ($data as $content) : ?>
                                   <tr>
                                        <td><?php echo ucwords($content->col) ?></td>
                                        <td><?php echo $content->row ?></td>
                                   </tr>
                                <?php $total += $content->row; ?>
                              <?php $counter++; endforeach; ?>
                              <tfoot>
                                  <tr>
                                      <th><b>Total</b></th>
                                      <th><b><?php echo $total; ?></b></th>
                                  </tr>
                              </tfoot>
                         </tbody>
                    </table>
                    <br /><hr /><br />
                </div>               
            <?php endforeach; ?>
            </div>