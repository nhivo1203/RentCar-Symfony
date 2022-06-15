<?php

namespace App\EventSubscriber;

use App\Event\CarEvent;
use App\Manager\Logger\CarLogger;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CarSubscriber implements EventSubscriberInterface
{
    private CarLogger $carLogger;

    public function __construct(CarLogger $carLogger)
    {
        $this->carLogger = $carLogger;
    }

    /**
     * @return array
     */
    #[ArrayShape(['car.set' => "string", 'car.update' => "string", 'car.delete' => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            'car.set' => 'onCarSet',
            'car.update' => 'onCarUpdate',
            'car.delete' => 'onCarDelete',
        ];
    }

    /**
     * @param CarEvent $event
     * @return void
     */
    public function onCarSet(CarEvent $event): void
    {
        $this->carLogger->carSet($event->getCar());
    }

    /**
     * @param CarEvent $event
     * @return void
     */
    public function onCarUpdate(CarEvent $event): void
    {
        $this->carLogger->carUpdate($event->getCar());
    }

    /**
     * @param CarEvent $event
     * @return void
     */
    public function onCarDelete(CarEvent $event): void
    {
        $this->carLogger->carDelete($event->getCar());
    }

}
