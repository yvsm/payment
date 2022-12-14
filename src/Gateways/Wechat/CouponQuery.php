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

/**
 * @package Payment\Gateways\Wechat
 * @author  : Leo
 * @email   : dayugog@gmail.com
 * @date    : 2019/4/7 10:03 AM
 * @version : 1.0.0
 * @desc    : 查询代金券批次
 **/
class CouponQuery extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'mmpaymkttransfers/querycouponsinfo';

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
        $selfParams = [
            'coupon_id'  => $requestParams['coupon_id'] ?? '',
            'openid'     => $requestParams['openid'] ?? '',
            'stock_id'   => $requestParams['stock_id'] ?? '',
            'op_user_id' => $requestParams['op_user_id'] ?? '',
            'version'    => '1.0',
            'type'       => 'XML',
        ];

        return $selfParams;
    }
}
