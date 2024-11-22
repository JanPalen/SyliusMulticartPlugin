<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Model;

trait OrderTrait
{
    protected ?int $cartNumber = 1;

    protected ?string $uuid = null;

    public function getCartNumber(): ?int
    {
        return $this->cartNumber;
    }

    public function setCartNumber(?int $cartNumber): void
    {
        $this->cartNumber = $cartNumber;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }
}
