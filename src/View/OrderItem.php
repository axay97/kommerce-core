<?php
namespace inklabs\kommerce\View;

use inklabs\kommerce\Entity;

class OrderItem implements ViewInterface
{
    /** @var int */
    public $id;

    /** @var int */
    public $quantity;

    /** @var int */
    public $created;

    /** @var int */
    public $updated;

    /** @var Price */
    public $price;

    /** @var Product */
    public $product;

    /** @var string */
    public $sku;

    /** @var string */
    public $name;

    /** @var string */
    public $discountNames;

    /** @var OrderItemOptionProduct[] */
    public $orderItemOptionProducts = [];

    /** @var OrderItemOptionValue[] */
    public $orderItemOptionValues = [];

    /** @var OrderItemTextOptionValue[] */
    public $orderItemTextOptionValues = [];

    /** @var CatalogPromotion[] */
    public $catalogPromotions = [];

    /** @var ProductQuantityDiscount[] */
    public $productQuantityDiscounts = [];

    public function __construct(Entity\OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;

        $this->id            = $orderItem->getId();
        $this->quantity      = $orderItem->getQuantity();
        $this->sku           = $orderItem->getSku();
        $this->name          = $orderItem->getName();
        $this->discountNames = $orderItem->getDiscountNames();
        $this->created       = $orderItem->getCreated();
        $this->updated       = $orderItem->getUpdated();

        $this->price = $orderItem->getPrice()->getView()
            ->withAllData()
            ->export();

        $this->product = $this->orderItem->getProduct()->getView()
            ->export();
    }

    public function export()
    {
        unset($this->orderItem);
        return $this;
    }

    public function withCatalogPromotions()
    {
        foreach ($this->orderItem->getCatalogPromotions() as $catalogPromotion) {
            $this->catalogPromotions[] = $catalogPromotion->getView()
                ->export();
        }
        return $this;
    }

    public function withProductQuantityDiscounts()
    {
        foreach ($this->orderItem->getProductQuantityDiscounts() as $productQuantityDiscount) {
            $this->productQuantityDiscounts[] = $productQuantityDiscount->getView()
                ->export();
        }
        return $this;
    }

    public function withOrderItemOptionProducts()
    {
        foreach ($this->orderItem->getOrderItemOptionProducts() as $orderItemOptionProduct) {
            $this->orderItemOptionProducts[] = $orderItemOptionProduct->getView()
                ->withAllData()
                ->export();
        }

        return $this;
    }

    public function withOrderItemOptionValues()
    {
        foreach ($this->orderItem->getOrderItemOptionValues() as $orderItemOptionValue) {
            $this->orderItemOptionValues[] = $orderItemOptionValue->getView()
                ->withAllData()
                ->export();
        }

        return $this;
    }

    public function withOrderItemTextOptionValues()
    {
        foreach ($this->orderItem->getOrderItemTextOptionValues() as $orderItemTextOptionValue) {
            $this->orderItemTextOptionValues[] = $orderItemTextOptionValue->getView()
                ->withAllData()
                ->export();
        }

        return $this;
    }

    public function withAllData()
    {
        return $this
            ->withCatalogPromotions()
            ->withProductQuantityDiscounts()
            ->withOrderItemOptionProducts()
            ->withOrderItemOptionValues()
            ->withOrderItemTextOptionValues();
    }
}