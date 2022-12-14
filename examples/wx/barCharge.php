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
    'amount'       => '0.01', // 微信沙箱模式，需要金额固定为0.01
    'return_param' => '123',
    'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址
    'terminal_id'  => 'web', // 终端设备号(门店号或收银设备ID) 默认值 web
    'auth_code'    => '1231212232323123123',

    // 如果是服务商，请提供以下参数
    'sub_appid'  => '', //微信分配的子商户公众账号ID
    'sub_mch_id' => '', // 微信支付分配的子商户号
];

// 使用
try {
    $client = new \zunyunkeji\payment\Client(\zunyunkeji\payment\Client::WECHAT, $wxConfig);
    $res    = $client->pay(\zunyunkeji\payment\Client::WX_CHANNEL_BAR, $payData);
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
