<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Factory\DeviceUuidCookieFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShowCartsAction
{
    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        private readonly ChannelContextInterface $channelContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Environment $twig,
        private readonly DeviceUuidCookieFactoryInterface $deviceUuidCookieFactory,
    ) {
    }

    public function __invoke(string $template): Response
    {
        $response = new Response();

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();
        $uuid = null;

        if (null === $customer) {
            $uuid = $this->deviceUuidCookieFactory->setUuidCookie($response);
        }

        $carts = $this->orderRepository->findCarts($channel, $customer, $uuid);

        $counted = $this->orderRepository->countCarts($channel, $customer, $uuid);

        $content = $this->twig->render(
            $template,
            [
                'customer' => $customer,
                'carts' => $carts,
                'counted' => $counted,
            ],
        );
        $response->setContent($content);

        return $response;
    }
}
