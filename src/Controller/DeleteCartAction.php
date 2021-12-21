<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class DeleteCartAction
{
    private ChannelContextInterface $channelContext;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private ObjectManager $em;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        ObjectManager $em,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->channelContext = $channelContext;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(string $route, int $cartNumber): Response
    {
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if ($cartNumber === $customer->getActiveCart()) {
            throw new \Exception('Cant delete active cart!');
        }

        $carts =  $this->orderRepository->findCartsByChannelAndCustomerGraterOrEqualNumber(
            $channel,
            $customer,
            $cartNumber,
        );

        /**
         * @var int $key
         * @var OrderInterface $cart
         */
        foreach ($carts as $key => $cart) {
            if ($cartNumber === $cart->getCartNumber()) {
                $this->em->remove($cart);
            }
            $cart->setCartNumber($cartNumber + $key - 1);
            $this->em->persist($cart);
        }

        $this->em->flush();

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
