<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace zunyunkeji\payment\Gateways\Wechat;

use zunyunkeji\payment\Contracts\IGatewayRequest;
use zunyunkeji\payment\Exceptions\GatewayException;
use zunyunkeji\payment\Payment;

/**
 * @package Payment\Gateways\Wechat
 * @author  : Leo
 * @email   : dayugog@gmail.com
 * @date    : 2019/4/1 8:23 PM
 * @version : 1.0.0
 * @desc    : 扫码支付（Native支付）
 **/
class QrCharge extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'pay/unifiedorder';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        try {
            return $this->requestWXApi(self::METHOD, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $limitPay = self::$config->get('limit_pay', '');
        if ($limitPay) {
            $limitPay = $limitPay[0];
        } else {
            $limitPay = '';
        }
        $nowTime    = time();
        $timeExpire = intval($requestParams['time_expire']);
        if (!empty($timeExpire)) {
            $timeExpire = date('YmdHis', $timeExpire);
        } else {
            $timeExpire = date('YmdHis', $nowTime + 1800); // 默认半小时过期
        }

        $receipt   = $requestParams['receipt'] ?? false;
        $totalFee  = bcmul($requestParams['amount'], 100, 0);
        $sceneInfo = $requestParams['scene_info'] ?? '';
        if ($sceneInfo) {
            $sceneInfo = json_encode(['store_info' => $sceneInfo]);
        } else {
            $sceneInfo = '';
        }

        $selfParams = [
            'device_info'      => $requestParams['device_info'] ?? '',
            'body'             => $requestParams['subject'] ?? '',
            'detail'           => $requestParams['body'] ?? '',
            'attach'           => $requestParams['return_param'] ?? '',
            'out_trade_no'     => $requestParams['trade_no'] ?? '',
            'fee_type'         => self::$config->get('fee_type', 'CNY'),
            'total_fee'        => $totalFee,
            'spbill_create_ip' => $requestParams['client_ip'] ?? '',
            'time_start'       => date('YmdHis', $nowTime),
            'time_expire'      => $timeExpire,
            'goods_tag'        => $requestParams['goods_tag'] ?? '',
            'notify_url'       => self::$config->get('notify_url', ''),
            'trade_type'       => 'NATIVE',
            'product_id'       => $requestParams['product_id'] ?? '',
            'limit_pay'        => $limitPay,
            'openid'           => $requestParams['openid'] ?? '',
            'receipt'          => $receipt === true ? 'Y' : '',
            'scene_info'       => $sceneInfo,
        ];

        return $selfParams;
    }
}
