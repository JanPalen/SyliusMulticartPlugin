<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Entity;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface OrderInterface extends BaseOrderInterface
{
    public function getCartNumber(): ?int;

    public function setCartNumber(?int $cartNumber): void;

    public function getMachineId(): ?string;

    public function setMachineId(?string $machineId): void;

    public function isActive(): ?bool;

    public function setIsActive(?bool $isActive): void;
}
