<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

interface PaymentGateway
{
    /**
     * 实行支付
     *
     * @param array $params
     * @return PaymentGateway
     */
    public function charge(array $params): self;

    /**
     * 从请求从获取信息
     *
     * @param Request $request
     * @return $this
     */
    public static function make(Request $request): static;

    /**
     * 是否成功
     *
     * @return bool
     */
    public function getPaid(): bool;

    /**
     * @return string 获取付款订单ID
     */
    public function getPaidId(): string;

    /**
     * @return string 实付金额
     */
    public function getPaidAmount(): string;

    /**
     * @return Carbon 付款完成时间
     */
    public function getPaidAt(): Carbon;

    /**
     * @return string 获取错误信息
     */
    public function getFailureMessage(): string;
}
