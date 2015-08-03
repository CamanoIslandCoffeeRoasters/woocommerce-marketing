<?php

     require( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
     
     global $wpdb, $woocommerce;
     
     date_default_timezone_set('America/Los_Angeles'); 

     $dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
     $dateTo = date('Y-m-d', strtotime($_POST['dateTo']));
     
     $dateFromSQL = date("Y-m-d", strtotime($dateFrom) - 60 * 60 * 24);
     $dateFromSQL = $dateFromSQL . " 20:45:01";
     $dateToSQL = $dateTo . " 20:45:00";               
?>
          <?php 
          $checkouts = array("Checkouts" => $wpdb->get_results("SELECT checkout as col, count(checkout) as row  
                                                            FROM {$wpdb->prefix}subscriptions
                                                            WHERE subscription_start
                                                            BETWEEN '$dateFromSQL'
                                                                AND '$dateToSQL'
                                                            AND checkout IS NOT NULL
                                                            GROUP BY (checkout)
                                                            "));
                                                            
            $sources = array("Sources" => $wpdb->get_results("SELECT source as col, count(source) as row  
                                                            FROM {$wpdb->prefix}subscriptions
                                                            WHERE subscription_start
                                                            BETWEEN '$dateFromSQL'
                                                                AND '$dateToSQL'
                                                            AND source IS NOT NULL
                                                            AND status = 'active'
                                                            GROUP BY (source)
                                                            ORDER BY row DESC
                                                            "));
            ?>
            <?php $contents = array_merge($checkouts, $sources); ?>
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
          <div>
