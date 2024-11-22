<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class DeviceUuidCookieFactory implements DeviceUuidCookieFactoryInterface
{
    private const COOKIE_NAME = 'device_uuid';

    public function getUuidFromCookie(Request $request): ?string
    {
        return $request->cookies->get(self::COOKIE_NAME);
    }

    public function setUuidCookie(Response $response): string
    {
        $uuid = $this->generateUuid();
        $response->headers->setCookie(
            new Cookie(
                self::COOKIE_NAME,
                $uuid,
                time() + 60 * 60 * 24 * 365
            )
        );

        return $uuid;
    }

    private function generateUuid(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
