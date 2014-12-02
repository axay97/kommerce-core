<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\Service as Service;

class CartCalculator
{
    /* @var Cart */
    protected $cart;

    /* @var Service\Pricing */
    protected $pricing;

    /* @var CartTotal */
    protected $cartTotal;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
    /**
     * @return CartTotal
     */
    public function getTotal(Service\Pricing $pricing, Shipping\Rate $shippingRate = null, TaxRate $taxRate = null)
    {
        $this->pricing = $pricing;

        $this->cartTotal = new CartTotal;

        $this->calculateItemPrices();
        $this->calculateCartPriceRules();
        $this->calculateShippingPrice($shippingRate);
        $this->calculateCouponDiscounts();
        $this->calculateTaxes($taxRate);

        $this->calculateTotal();
        $this->calculateSavings();

        return $this->cartTotal;
    }

    private function calculateItemPrices()
    {
        foreach ($this->cart->getItems() as $item) {
            $price = $item->getPrice($this->pricing);

            $this->cartTotal->origSubtotal += $price->origQuantityPrice;
            $this->cartTotal->subtotal += $price->quantityPrice;

            if ($item->getProduct()->getIsTaxable()) {
                $this->cartTotal->taxSubtotal += $price->quantityPrice;
            }
        }
    }

    private function calculateCartPriceRules()
    {
        foreach ($this->cart->getCartPriceRules() as $cartPriceRule) {
            if ($cartPriceRule->isValid($this->pricing->getDate(), $this->cartTotal, $this->cart->getItems())) {
                foreach ($cartPriceRule->getDiscounts() as $discount) {
                    $price = $this->pricing->getPrice($discount->getProduct(), $discount->getQuantity());

                    $this->cartTotal->subtotal -= $price->quantityPrice;

                    if ($cartPriceRule->getReducesTaxSubtotal() and $discount->getProduct()->getIsTaxable()) {
                        $this->cartTotal->taxSubtotal -= $price->quantityPrice;
                    }

                    $this->cartTotal->cartPriceRules[] = $cartPriceRule;
                }
            }
        }

        // No subtotal below zero!
        $this->cartTotal->subtotal = max(0, $this->cartTotal->subtotal);
    }

    private function calculateCouponDiscounts()
    {
        foreach ($this->cart->getCoupons() as $coupon) {
            if ($coupon->isValid($this->pricing->getDate(), $this->cartTotal->subtotal)) {
                $newSubtotal = $coupon->getUnitPrice($this->cartTotal->subtotal);
                $discountValue = $this->cartTotal->subtotal - $newSubtotal;
                $this->cartTotal->discount += $discountValue;
                $this->cartTotal->coupons[] = $coupon;

                if ($coupon->getReducesTaxSubtotal()) {
                    $this->cartTotal->taxSubtotal -= $discountValue;
                }

                if ($coupon->getFlagFreeShipping()) {
                    $this->cartTotal->shippingDiscount = $this->cartTotal->shipping;
                    $this->cartTotal->discount += $this->cartTotal->shipping;
                }
            }
        }

        // No taxes below zero!
        $this->cartTotal->taxSubtotal = max(0, $this->cartTotal->taxSubtotal);
    }

    private function calculateShippingPrice(Shipping\Rate $shippingRate = null)
    {
        if ($shippingRate !== null) {
            $this->cartTotal->shipping = $shippingRate->cost;
        }
    }

    private function calculateTaxes(TaxRate $taxRate = null)
    {
        if ($taxRate !== null) {
            $this->cartTotal->tax = $taxRate->getTax(
                $this->cartTotal->taxSubtotal,
                ($this->cartTotal->shipping - $this->cartTotal->shippingDiscount)
            );

            if ($this->cartTotal->tax > 0) {
                $this->cartTotal->taxRate = $taxRate;
            }
        }
    }

    private function calculateTotal()
    {
        $this->cartTotal->total = (
            $this->cartTotal->subtotal
            - $this->cartTotal->discount
            + $this->cartTotal->shipping
            + $this->cartTotal->tax
        );

        // No total below zero!
        $this->cartTotal->total = max(0, $this->cartTotal->total);
    }

    private function calculateSavings()
    {
        $this->cartTotal->savings = (
            $this->cartTotal->origSubtotal
            - $this->cartTotal->subtotal
            + $this->cartTotal->discount
        );
    }
}