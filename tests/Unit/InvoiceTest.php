<?php

namespace Tests\Unit;

use App\Exceptions\PayException;
use App\Models\Course;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Services\OmiseGateway;
use App\Services\PayService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Tests\TestCase;

class InvoiceTest extends TestCase
{

    /**
     * A basic unit test example.
     */
    public function test_create_invoice(): void
    {
        // 创建订单测试
        $c = Course::factory()->make(['id' => 1]);
        $i = InvoiceService::create($c);

        $this->assertTrue($c->id == $i->course_id);
        $this->assertTrue($i->status == Invoice::STATUS_CREATED);
    }

    public function test_send_invoice()
    {
        $i = Invoice::factory()->make([
            'status' => Invoice::STATUS_CREATED,
        ]);;

        // 发送成功
        (new InvoiceService($i))->send(new FormRequest());
        $this->assertTrue($i->status == Invoice::STATUS_SENT);

        // 状态不对
        $i = Invoice::factory()->make([
            'status' => Invoice::STATUS_SENT,
        ]);
        try {
            (new InvoiceService($i))->send(new FormRequest());
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @throws PayException
     */
    public function test_pay_invoice()
    {
        // 创建一个 Mock 对象来模拟 OmiseGateway
        $mockGateway = $this->getMockBuilder(OmiseGateway::class)
            ->setConstructorArgs([
                'token',
                'pub_token',
                'sec_token'
            ])
            ->getMock();

        // 设置模拟对象的返回值或者预期行为
        $mockGateway->expects($this->once())
            ->method('charge')
            ->willReturnSelf();
        $mockGateway->expects($this->once())
            ->method('getPaid')
            ->willReturn(true);
        $mockGateway->expects($this->once())
            ->method('getPaidId')
            ->willReturn('chrg_test_123');
        $mockGateway->expects($this->once())
            ->method('getPaidAmount')
            ->willReturn('10086');
        $now = Carbon::now();
        $mockGateway->expects($this->once())
            ->method('getPaidAt')
            ->willReturn($now);

        $mockGateway->setCard('token');

        $data = PayService::create(10086, 'Test')
            ->setGateway($mockGateway)
            ->charge();

        $this->assertTrue($data->getPaidId() == 'chrg_test_123');
        $this->assertTrue($data->getPaidAmount() == '10086');
        $this->assertTrue($data->getPaidAt() == $now);
    }
}
