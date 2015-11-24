<?php
namespace inklabs\kommerce\Action\Order\Handler;

use inklabs\kommerce\Action\Order\SetOrderStatusCommand;
use inklabs\kommerce\Entity\Order;
use inklabs\kommerce\Service\OrderServiceInterface;
use inklabs\kommerce\tests\Helper\DoctrineTestCase;
use Mockery;

class SetOrderStatusHandlerTest extends DoctrineTestCase
{
    public function testHandle()
    {
        $orderService = Mockery::mock(OrderServiceInterface::class);
        $orderService->shouldReceive('setOrderStatus')
            ->once();
        /** @var OrderServiceInterface $orderService */

        $command = new SetOrderStatusCommand(1, Order::STATUS_SHIPPED);
        $handler = new SetOrderStatusHandler($orderService);
        $handler->handle($command);
    }
}
