<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

final class MachineIdSubscriber implements EventSubscriberInterface
{

    private const string MACHINE_ID = 'machine_id';
    private bool $hasCookie = false;
    private static string $cartMachineId = '';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['checkCookie', 1]],
            KernelEvents::RESPONSE => [['setCookie', 0]],
        ];
    }

    public static function getCartMachineId(): string
    {
        return self::$cartMachineId;
    }

    public function checkCookie(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $this->hasCookie = $request->cookies->has(self::MACHINE_ID);

        if ($this->hasCookie) {
            self::$cartMachineId = $request->cookies->get(self::MACHINE_ID);
        } else {
            self::$cartMachineId = $this->generateMachineId();
        }
    }

    public function setCookie(ResponseEvent $event): void
    {
        if ($this->hasCookie) {
            return;
        }

        $response = $event->getResponse();

        $response->headers->setCookie(
            new Cookie(
                self::MACHINE_ID,
                self::$cartMachineId,
                time() + 60 * 60 * 24 * 365
            )
        );
    }

    private function generateMachineId(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
