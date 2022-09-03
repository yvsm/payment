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

// 订单信息
$params = [
    'mode'        => 'bank', // bank mch
    'start_time'  => strtotime('-2days'),
    'end_time'    => time(),
    'operator_id' => '9999'
];

// 使用
try {
    $client = new \zunyunkeji\payment\Client(\zunyunkeji\payment\Client::CMB, $wxConfig);
    $res    = $client->settleDownload($params);
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
