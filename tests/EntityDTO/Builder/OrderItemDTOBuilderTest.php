<?php
namespace inklabs\kommerce\EntityDTO;

use inklabs\kommerce\tests\Helper\TestCase\EntityDTOBuilderTestCase;

class OrderItemDTOBuilderTest extends EntityDTOBuilderTestCase
{
    public function testBuild()
    {
        $order = $this->dummyData->getOrder();
        $orderItem = $this->dummyData->getOrderItemFull($order);

        $orderItemDTO = $this->getDTOBuilderFactory()
            ->getOrderItemDTOBuilder($orderItem)
            ->withAllData()
            ->build();

        $this->assertTrue($orderItemDTO instanceof OrderItemDTO);
        $this->assertTrue($orderItemDTO->price instanceof PriceDTO);
        $this->assertTrue($orderItemDTO->product instanceof ProductDTO);
        $this->assertTrue($orderItemDTO->areAttachmentsEnabled);
        $this->assertTrue($orderItemDTO->catalogPromotions[0] instanceof CatalogPromotionDTO);
        $this->assertTrue($orderItemDTO->productQuantityDiscounts[0] instanceof ProductQuantityDiscountDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionProducts[0] instanceof OrderItemOptionProductDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionProducts[0]->optionProduct instanceof OptionProductDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionProducts[0]->optionProduct->option instanceof OptionDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionValues[0] instanceof OrderItemOptionValueDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionValues[0]->optionValue instanceof OptionValueDTO);
        $this->assertTrue($orderItemDTO->orderItemOptionValues[0]->optionValue->option instanceof OptionDTO);
        $this->assertTrue($orderItemDTO->orderItemTextOptionValues[0] instanceof OrderItemTextOptionValueDTO);
        $this->assertTrue($orderItemDTO->orderItemTextOptionValues[0]->textOption instanceof TextOptionDTO);
        $this->assertTrue($orderItemDTO->attachments[0] instanceof AttachmentDTO);
    }
}
