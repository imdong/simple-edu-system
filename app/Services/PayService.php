<?php

namespace App\Services;

use App\Exceptions\PayException;
use Carbon\Carbon;

/**
 * 这是关于支付相关的简易封装
 *
 */
class PayService
{
    /**
     * @var string 货币类型
     */
    protected string $currency = 'jpy';

    /**
     * @var int 付款金额
     */
    protected int $amount;

    /**
     * @var string|null 付款描述
     */
    protected ?string $description;

    /**
     * @var PaymentGateway 支付接口
     */
    protected PaymentGateway $gateway;

    public function __construct(int $amount, string $description = null, PaymentGateway $gateway = null)
    {
        $this->amount = $amount;
        $this->description = $description;
        $this->gateway = $gateway ?: new OmiseGateway();
    }

    public static function create(int $amount, string $description = null): PayService
    {
        return new static($amount, $description);
    }

    /**
     * 指定使用的支付接口
     *
     * @param PaymentGateway $gateway
     * @return PayService
     */
    public function setGateway(PaymentGateway $gateway): PayService
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * 使用 Omise Charge 接口支付
     *
     * @throws PayException
     */
    public function charge(): PaymentGateway
    {
        $data = $this->gateway->charge([
            'amount'      => $this->amount,
            'currency'    => $this->currency,
            'description' => $this->description,
        ]);

        // 是否成功了？
        if (!$data->getPaid()) {
            throw new PayException(
                $data->getFailureMessage(),
                403
            );
        }

        return $data;
    }
}

