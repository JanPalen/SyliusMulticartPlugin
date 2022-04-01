<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Cart\Context\ShopBasedMultiCartContext;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class ShopBasedMultiCartContextSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderRepositoryInterface $orderRepository
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $shopperContext,
            $orderRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopBasedMultiCartContext::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(CartContextInterface::class);
    }

    function it_gets_cart(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderRepositoryInterface $orderRepository,
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart,
        CurrencyInterface $currency
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('code');
        $shopperContext->getLocaleCode()->willReturn('locale_code');
        $shopperContext->getCustomer()->willReturn($customer);

        $orderRepository->countCarts($channel, $customer)->willReturn(1);

        $this->getCart()->shouldHaveType(OrderInterface::class);
    }

    function it_sets_customer_and_address_on_cart(
        CustomerInterface $customer,
        OrderInterface $cart,
        AddressInterface $defaultAddress
    ) {
        $customer->getDefaultAddress()->willReturn($defaultAddress);
    }

}
