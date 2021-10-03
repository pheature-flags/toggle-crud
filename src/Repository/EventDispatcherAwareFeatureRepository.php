<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Repository;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherAwareFeatureRepository implements FeatureRepository
{
    private FeatureRepository $featureRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(FeatureRepository $featureRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->featureRepository = $featureRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Feature $feature): void
    {
        $this->featureRepository->save($feature);

        $this->dispatchEvents($feature->release());
    }

    /**
     * @param array<object> $events
     */
    private function dispatchEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function remove(FeatureId $featureId): void
    {
        $this->featureRepository->remove($featureId);
    }

    public function get(FeatureId $featureId): Feature
    {
        return $this->featureRepository->get($featureId);
    }
}
