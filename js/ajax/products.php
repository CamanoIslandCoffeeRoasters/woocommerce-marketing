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
                   
           $products = array("Products" => $wpdb->get_results("SELECT items.order_item_name as col, count(meta.meta_value) as row, meta.meta_value as product_id, meta1.meta_value as pounds
                                                                FROM {$wpdb->posts} posts
                                                                JOIN {$wpdb->prefix}woocommerce_order_items items ON posts.ID = items.order_id
                                                                JOIN {$wpdb->prefix}woocommerce_order_itemmeta meta ON items.order_item_id = meta.order_item_id
                                                                JOIN {$wpdb->prefix}woocommerce_order_itemmeta meta1 ON ((items.order_item_id = meta1.order_item_id) AND (meta1.meta_key = 'pa_pack'))
                                                                WHERE post_type = 'shop_order'
                                                                AND post_status = 'wc-completed'
                                                                AND date(post_date) 
                                                                    BETWEEN '$dateFromSQL' 
                                                                        AND '$dateToSQL'
                                                                AND items.order_item_type = 'line_item'
                                                                AND meta.meta_key = '_product_id'
                                                                GROUP BY meta.meta_value
                                                                ORDER BY meta1.meta_value ASC, col ASC"
                                                            )
                                                        );


               ?>
                <?php $contents = array_merge($products); ?>
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
                                <?php $total += $content->row * (int) str_replace('lb', '', $content->pounds) ; ?>
                              <?php endforeach; ?>
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