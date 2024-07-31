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
     * @var string 实际付款金额
     */
    protected string $paid_amount;

    /**
     * @var string 付款订单编号
     */
    protected string $paid_id;

    /**
     * @var Carbon 订单付款时间
     */
    protected Carbon $paid_at;

    public function __construct(int $amount, string $description = null)
    {
        $this->amount = $amount;
        $this->description = $description;
    }

    public static function create(int $amount, string $description = null): PayService
    {
        return new static($amount, $description);
    }

    /**
     * 使用 Omise Charge 接口支付
     *
     * @throws PayException
     */
    public function omiseCharge(string $omise_token): static
    {
        $config = config('omise');

        try {
            $data = \OmiseCharge::create([
                'amount'      => $this->amount,
                'currency'    => $this->currency,
                'card'        => $omise_token,
                'description' => $this->description,
            ], $config['public_key'], $config['secret_key']);
        } catch (\OmiseException $e) {
            throw new PayException($e->getMessage(), 502);
        }

        // 是否成功了？
        if (!$data->offsetGet('paid')) {
            throw new PayException(
                $data->offsetGet('failure_message'),
                403
            );
        }

        // 保存关键结果
        $this->paid_amount = $data->offsetGet('amount');
        $this->paid_id = $data->offsetGet('id');
        $this->paid_at = Carbon::parse($data->offsetGet('paid_at'));

        return $this;
    }

    /**
     * @return string
     */
    public function getPaidAmount(): string
    {
        return $this->paid_amount;
    }

    /**
     * @return string
     */
    public function getPaidId(): string
    {
        return $this->paid_id;
    }

    /**
     * @return Carbon
     */
    public function getPaidAt(): Carbon
    {
        return $this->paid_at;
    }
}

