<?php

namespace App\EventSubscriber;

use App\Event\RentEvent;
use App\Manager\Logger\RentLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RentSubscriber implements EventSubscriberInterface
{
    private RentLogger $rentLogger;

    public function __construct(RentLogger $rentLogger)
    {
        $this->rentLogger = $rentLogger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'rent.set' => 'onRentSet',
            'rent.update' => 'onRentUpdate',
            'rent.delete' => 'onRentDelete',
        ];
    }

    /**
     * @param RentEvent $event
     * @return void
     */
    public function onRentSet(RentEvent $event): void
    {
        $this->rentLogger->rentSet($event->getRent());
    }

    /**
     * @param RentEvent $event
     * @return void
     */
    public function onRentUpdate(RentEvent $event): void
    {
        $this->rentLogger->rentUpdate($event->getRent());
    }

    /**
     * @param RentEvent $event
     * @return void
     */
    public function onRentDelete(RentEvent $event): void
    {
        $this->rentLogger->rentDelete($event->getRent());
    }
}
