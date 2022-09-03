<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/../../vendor/autoload.php';


date_default_timezone_set('Asia/Shanghai');
$wxConfig = require_once __DIR__ . '/../wxconfig.php';

$tradeNo = time() . rand(1000, 9999);
// 订单信息
$payData = [
    'body'         => 'test body',
    'subject'      => 'test subject',
    'trade_no'     => $tradeNo,
    'time_expire'  => time() + 600, // 表示必须 600s 内付款
    'amount'       => '3.01', // 微信沙箱模式，需要金额固定为3.01
    'return_param' => '123',
    'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址

    //{"h5_info": {"type":"Wap","wap_url": "https://pay.qq.com","wap_name": "腾讯充值"}}
    'scene_info' => [
        'type'     => 'Wap', // IOS  Android  Wap  腾讯建议 IOS  ANDROID 采用app支付
        'wap_url'  => 'https://Leo112g.github.io/', //自己的 wap 地址
        'wap_name' => '测试充值',
    ],
];

// 使用
try {
    $client = new \zunyunkeji\payment\Client(\zunyunkeji\payment\Client::WECHAT, $wxConfig);
    $res    = $client->pay(\zunyunkeji\payment\Client::WX_CHANNEL_WAP, $payData);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
    exit;
} catch (\zunyunkeji\payment\Exceptions\GatewayException $e) {
    echo $e->getMessage();
    exit;
} catch (\zunyunkeji\payment\Exceptions\ClassNotFoundException $e) {
    echo $e->getMessage();
    exit;
}

var_dump($res);
