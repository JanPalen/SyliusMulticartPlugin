<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Customizer;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

interface CartCustomizerInterface
{
    public function copyDefaultToBillingAddress(OrderInterface $cart, CustomerInterface $customer): void;

    public function increaseCartNumberOnCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        OrderInterface $cart,
        ?string $machineId,
    ): void;
}
