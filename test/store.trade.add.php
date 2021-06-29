<?php
require_once(__DIR__.'/../src/PrismClient.php');

// api info
$client = new PrismClient($url = 'http://xxxj5m.apihub.cn/api', $key = 'd4hyoana', $secret = '5ulfhuby223hdibzeb4a');

$cnNodeId     = '150438';//Assigned to CN store node number
$shopExNodeId = '150438';//The node number assigned to shipex

// Custom header information
$headers = array(

);

// You can set whether to use the curl or socket request method. By default, the curl method will be called first
// $client->setRequester('socket');
$client->setRequester('curl');

//Assigned to CN store Token
$client->access_token = '63ac6e298859ef17b8d6ca61506b58b41f1c031cc99b87e720a81';

// Order creation
$params = array(

  //system params
  'method'          => 'store.trade.add',
  'format'          => 'json',
  'from_node_id'    => $cnNodeId,
  'node_id'         => $cnNodeId . '_' . $shopExNodeId,
  'timestamp'       => microtime(),//
  'to_node_id'      => $shopExNodeId,
  'v'               => '1.0',

  //application params
  'tid'             => '2021062973290002',//global unique
  'created'         => '2021-06-29 09:39:47',
  'modified'        => '2021-06-29 10:22:45',
  'status'          => 'TRADE_ACTIVE',// fixed
  'pay_status'      => 'PAY_FINISH',// fixed
  'ship_status'     => 'SHIP_NO',// fixed

  'total_goods_fee'     => '210.00',//Total amount of original price of goods
  'goods_discount_fee'  => '30.00',//Discount amount
  'shipping_fee'        => '5.00',//freight
  'total_trade_fee'     => '185.00',//Total order transaction amount
  'payed_fee'           => '185.00',//Amount paid
  'payment_type'        => 'wechatPay',// Name of payment method
  'pay_time'            => '2021-06-29 10:22:45',

  'buyer_uname'         => 'wechat001',//Buyer account 
  'buyer_mobile'        => '138xxxx0000',//Buyer mobile phone
  'buyer_email'         => 'wechat@qq.com',//Buyer email

  'receiver_name'       => 'jack',
  'receiver_mobile'     => '1356xxx1930',// Mobile phone and landline cannot be empty at the same time
  'receiver_phone'      => '',//Mobile phone and landline cannot be empty at the same time
  'receiver_state'      => '上海',
  'receiver_city'       => '上海市',
  'receiver_district'   => '徐汇区',
  'receiver_address'    => '康健路康健小区xx弄xx号xx室',

  'buyer_message'       => 'Weekend delivery, thank you',

  'orders'              => json_encode(array ( 
                          'order' =>  array (
                            0 =>  array (
                                  'oid'             => '27628',//Sub Order No, global unique
                                  'type'            => 'goods',//goods or gift
                                  'items_num'       => '1',//Sub order detail quantity
                                  'price'           => '120.00',
                                  'total_order_fee' => '120.00',
                                  'discount_fee'    => '10.00',
                                  'sale_price'      => '110.00',
                                  'order_items'     =>  array (
                                        'item' => //A sub order contains only one commodity detail, and multiple goods are transferred with multiple sub orders
                                            array (
                                              0 =>  array (
                                                'bn'                => '69413137',
                                                'item_type'         => 'product',//product or gift
                                                'name'              => 'test goods',
                                                'num'               => '1',
                                                'price'             => '120.00',
                                                'total_item_fee'    => '120.00',
                                                'discount_fee'      => '10.00',
                                                'sale_price'        => '110.00',
                                              ),
                                            ),
                                    ),
                                ),
                            1 =>  array (
                                  'oid'             => '27629',//Sub Order No, global unique
                                  'type'            => 'goods',//goods or gift
                                  'items_num'       => '2',//Sub order detail quantity
                                  'price'           => '45.00',
                                  'total_order_fee' => '90.00',
                                  'discount_fee'    => '20.00',
                                  'sale_price'      => '70.00',
                                  'order_items'     =>  array (
                                        'item' => //A sub order contains only one commodity detail, and multiple goods are transferred with multiple sub orders
                                            array (
                                              0 =>  array (
                                                'bn'                => '69413138',
                                                'item_type'         => 'product',//product or gift
                                                'name'              => 'test goods 2 ',
                                                'num'               => '2',
                                                'price'             => '45.00',
                                                'total_item_fee'    => '90.00',
                                                'discount_fee'      => '20.00',
                                                'sale_price'        => '70.00',
                                              ),
                                            ),
                                    ),
                                ),
                            2 =>  array (
                                  'oid'             => '27630',//Sub Order No, global unique
                                  'type'            => 'gift',//goods or gift
                                  'items_num'       => '1',//Sub order detail quantity
                                  'price'           => '0.00',
                                  'total_order_fee' => '0.00',
                                  'discount_fee'    => '0.00',
                                  'sale_price'      => '0.00',
                                  'order_items'     =>  array (
                                        'item' => //A sub order contains only one commodity detail, and multiple goods are transferred with multiple sub orders
                                            array (
                                              0 =>  array (
                                                'bn'                => '69413139',
                                                'item_type'         => 'product',//product or gift
                                                'name'              => 'test gift',
                                                'num'               => '1',
                                                'price'             => '0.00',
                                                'total_item_fee'    => '0.00',
                                                'discount_fee'      => '0.00',
                                                'sale_price'        => '0.00',
                                              ),
                                            ),
                                    ),
                                ),
                      ),
                    )),

  'payment_lists' => json_encode(array ( 
                   'payment_list' => 
                      array (
                        0 => 
                        array (
                          'payment_id'      => '2021062973290002',
                          'pay_time'        => '2021-06-29 10:22:45',
                          'payment_name'    => 'wechatPay',
                          'payment_code'    => 'wechatPay',//wechatPay/AliPay
                          'pay_fee'         => '185.00',
                          'currency'        => 'CNY',//fixed
                          'pay_type'        => 'online',//fixed
                        ),
                    ),
                    )),
);

$output = $client->post('/oms/store.trade.add', $params, $headers);

echo $output."\n";
