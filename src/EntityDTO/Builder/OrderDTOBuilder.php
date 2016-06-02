<?php
namespace inklabs\kommerce\EntityDTO\Builder;

use inklabs\kommerce\Entity\Order;
use inklabs\kommerce\EntityDTO\OrderDTO;

class OrderDTOBuilder implements DTOBuilderInterface
{
    use IdDTOBuilderTrait, TimeDTOBuilderTrait;

    /** @var Order */
    protected $entity;

    /** @var OrderDTO */
    protected $entityDTO;

    /** @var DTOBuilderFactoryInterface */
    protected $dtoBuilderFactory;

    public function __construct(Order $order, DTOBuilderFactoryInterface $dtoBuilderFactory)
    {
        $this->entity = $order;
        $this->dtoBuilderFactory = $dtoBuilderFactory;

        $this->entityDTO = $this->getEntityDTO();
        $this->setId();
        $this->setTime();
        $this->entityDTO->referenceNumber = $this->entity->getReferenceNumber();
        $this->entityDTO->externalId      = $this->entity->getExternalId();
        $this->entityDTO->totalItems      = $this->entity->totalItems();
        $this->entityDTO->totalQuantity   = $this->entity->totalQuantity();

        $this->entityDTO->status = $this->dtoBuilderFactory
            ->getOrderStatusTypeDTOBuilder($this->entity->getStatus())
            ->build();

        if ($this->entity->getShippingAddress() !== null) {
            $this->entityDTO->shippingAddress = $this->dtoBuilderFactory
                ->getOrderAddressDTOBuilder($this->entity->getShippingAddress())
                ->build();
        }

        if ($this->entity->getBillingAddress() !== null) {
            $this->entityDTO->billingAddress = $this->dtoBuilderFactory
                ->getOrderAddressDTOBuilder($this->entity->getBillingAddress())
                ->build();
        }

        if ($this->entity->getTotal() !== null) {
            $this->entityDTO->total = $this->dtoBuilderFactory
                ->getCartTotalDTOBuilder($this->entity->getTotal())
                ->withAllData()
                ->build();
        }

        if ($this->entity->getShipmentRate() !== null) {
            $this->entityDTO->shipmentRate = $this->dtoBuilderFactory
                ->getShipmentRateDTOBuilder($this->entity->getShipmentRate())
                ->build();
        }

        if ($this->entity->getTaxRate() !== null) {
            $this->entityDTO->taxRate = $this->dtoBuilderFactory
                ->getTaxRateDTOBuilder($this->entity->getTaxRate())
                ->build();
        }

        foreach ($this->entity->getShipments() as $shipment) {
            $this->entityDTO->shipments[] = $this->dtoBuilderFactory
                ->getShipmentDTOBuilder($shipment)
                ->build();
        }
    }

    protected function getEntityDTO()
    {
        return new OrderDTO;
    }

    public function withUser()
    {
        $user = $this->entity->getUser();
        if (! empty($user)) {
            $this->entityDTO->user = $this->dtoBuilderFactory
                ->getUserDTOBuilder($user)
                ->build();
        }
        return $this;
    }

    public function withItems()
    {
        foreach ($this->entity->getOrderItems() as $orderItem) {
            $this->entityDTO->orderItems[] = $this->dtoBuilderFactory
                ->getOrderItemDTOBuilder($orderItem)
                ->withAllData()
                ->build();
        }
        return $this;
    }

    public function withPayments()
    {
        foreach ($this->entity->getPayments() as $payment) {
            $this->entityDTO->payments[] = $this->dtoBuilderFactory
                ->getPaymentDTOBuilder($payment)
                ->build();
        }
        return $this;
    }

    public function withCoupons()
    {
        foreach ($this->entity->getCoupons() as $coupon) {
            $this->entityDTO->coupons[] = $this->dtoBuilderFactory
                ->getCouponDTOBuilder($coupon)
                ->build();
        }
        return $this;
    }

    public function withAllData()
    {
        return $this
            ->withUser()
            ->withItems()
            ->withPayments()
            ->withCoupons();
    }

    protected function preBuild()
    {
    }

    public function build()
    {
        $this->preBuild();
        return $this->entityDTO;
    }
}
