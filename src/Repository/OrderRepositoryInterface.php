<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface as BaseOrderRepositoryInterface;

/** @phpstan-ignore-next-line - extends generic interface */
interface OrderRepositoryInterface extends BaseOrderRepositoryInterface
{
    public function findCarts(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): array;

    public function findCartsGraterOrEqualNumber(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        int $cartNumber,
        ?string $machineId,
    ): array;

    public function findBiggestCartNumber(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        string $machineId,
    ): int;

    public function countCarts(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): int;

    public function findLatestNotEmptyActiveCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): ?OrderInterface;

    public function findActiveCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): ?OrderInterface;

    public function findPickedCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
        int $cartNumber,
    ): ?OrderInterface;
}
