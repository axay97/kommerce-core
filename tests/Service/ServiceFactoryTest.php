<?php
namespace inklabs\kommerce\Service;

use inklabs\kommerce\Service\Import\ImportOrderItemService;
use inklabs\kommerce\Service\Import\ImportOrderService;
use inklabs\kommerce\Service\Import\ImportPaymentService;
use inklabs\kommerce\Service\Import\ImportUserService;
use inklabs\kommerce\tests\Helper\TestCase\ServiceTestCase;

class ServiceFactoryTest extends ServiceTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setupEntityManager();
    }

    public function testGetServices()
    {
        $serviceFactory = $this->getServiceFactory();
        $this->assertTrue($serviceFactory->getCart() instanceof CartService);
        $this->assertTrue($serviceFactory->getImageService() instanceof ImageService);
        $this->assertTrue($serviceFactory->getImportOrder() instanceof ImportOrderService);
        $this->assertTrue($serviceFactory->getImportOrderItem() instanceof ImportOrderItemService);
        $this->assertTrue($serviceFactory->getImportPayment() instanceof ImportPaymentService);
        $this->assertTrue($serviceFactory->getImportUser() instanceof ImportUserService);
        $this->assertTrue($serviceFactory->getOrder() instanceof OrderService);
        $this->assertTrue($serviceFactory->getUser() instanceof UserService);
    }
}
