<?php

namespace App\Services;

use App\Exceptions\PayException;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OmiseGateway implements PaymentGateway
{
    /**
     * @var string 公钥
     */
    protected string $publicKey = '';

    /**
     * @var string 私钥
     */
    protected string $secretKey = '';

    /**
     * @var string 卡片密钥
     */
    protected string $card = '';

    /**
     * @var \OmiseCharge 支付结果
     */
    protected \OmiseCharge $response;

    public function __construct(string $card = '', string $publicKey = '', string $secretKey = '')
    {
        $this->card = $card;
        $this->publicKey = $publicKey ?? config('omise.public_key');
        $this->secretKey = $secretKey ?? config('omise.secret_key');
    }

    /**
     * 从银行卡付款
     *
     * @throws PayException
     */
    public function charge(array $params): self
    {
        try {
            $this->response = \OmiseCharge::create($params + [
                    'card' => $this->card
                ], $this->publicKey, $this->secretKey);

            return $this;
        } catch (\OmiseException $e) {
            throw new PayException($e->getMessage(), 502);
        }
    }

    /**
     * @param string $card
     * @return $this
     */
    public function setCard(string $card): static
    {
        $this->card = $card;
        return $this;
    }

    /**
     * 从请求从获取信息
     *
     * @param Request $request
     * @return $this
     */
    public static function make(Request $request): static
    {
        $card = $request->input('omise_token');

        return new static($card);
    }

    /**
     * 是否成功
     *
     * @return bool
     */
    public function getPaid(): bool
    {
        return $this->response->offsetGet('paid');
    }

    /**
     * @return string 获取付款订单ID
     */
    public function getPaidId(): string
    {
        return $this->response->offsetGet('id');
    }

    /**
     * @return string 实付金额
     */
    public function getPaidAmount(): string
    {
        return $this->response->offsetGet('amount');
    }

    /**
     * @return Carbon 付款完成时间
     */
    public function getPaidAt(): Carbon
    {
        return Carbon::parse($this->response->offsetGet('paid_at'));
    }

    /**
     * @return string 获取错误信息
     */
    public function getFailureMessage(): string
    {
        return $this->response->offsetGet('failure_message');
    }
}
