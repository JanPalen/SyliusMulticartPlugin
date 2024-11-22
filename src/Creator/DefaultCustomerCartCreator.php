<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Creator;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Factory\DeviceUuidCookieFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DefaultCustomerCartCreator implements DefaultCustomerCartCreatorInterface
{
    private const MAX_CART_COUNT = 8;

    public function __construct(
        private readonly CartContextInterface $shopBasedMultiCartContext,
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerContextInterface $customerContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly TranslatorInterface $translator,
        private readonly DeviceUuidCookieFactoryInterface $deviceUuidCookieFactory,
    ) {
    }

    public function createNewCart(Response $response): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            $uuid = $this->deviceUuidCookieFactory->setUuidCookie($response);
        }

        $carts = $this->orderRepository->countCarts($channel, $customer, $uuid);

        if (self::MAX_CART_COUNT === $carts) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.max_cart_number_reached'),
            );
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
