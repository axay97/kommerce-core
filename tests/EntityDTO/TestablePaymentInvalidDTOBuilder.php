<?php
namespace inklabs\kommerce\tests\EntityDTO;

use inklabs\kommerce\EntityDTO\Builder\AbstractPaymentDTOBuilder;

class TestablePaymentInvalidDTOBuilder extends AbstractPaymentDTOBuilder
{
    public function __construct(TestablePaymentInvalid $testablePaymentInvalid)
    {
        parent::__construct($testablePaymentInvalid);
    }
}
