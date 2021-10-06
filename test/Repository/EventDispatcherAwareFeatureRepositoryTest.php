<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Repository;

use Pheature\Core\Toggle\Write\Event\FeatureWasCreated;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Repository\EventDispatcherAwareFeatureRepository;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherAwareFeatureRepositoryTest extends TestCase
{
    public function testItShouldBeAnInstanceOfFeatureRepository(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $repository = new EventDispatcherAwareFeatureRepository($featureRepository, $eventDispatcher);

        $this->assertInstanceOf(FeatureRepository::class, $repository);
    }

    public function testItShouldDispatchEventsIfSaveMethodIsCalled(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $feature = Feature::withId(FeatureId::fromString('a_pheature_id'));
        $featureRepository
            ->expects($this->once())
            ->method('save')
            ->with($feature);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function(object $event) {
                    $this->assertInstanceOf(FeatureWasCreated::class, $event);
                    return true;
                })
            );

        $repository = new EventDispatcherAwareFeatureRepository($featureRepository, $eventDispatcher);
        $repository->save($feature);
    }

    public function testItShouldRemoveAFeature(): void
    {
        $featureId = FeatureId::fromString('a_feature_id');
        $featureToRemove = Feature::withId($featureId);

        $featureRepository = $this->createMock(FeatureRepository::class);
        $featureRepository
            ->expects($this->once())
            ->method('remove')
            ->with($featureToRemove);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $repository = new EventDispatcherAwareFeatureRepository($featureRepository, $eventDispatcher);

        $repository->remove($featureToRemove);
    }

    public function testItShouldGetAFeatureByFeatureId(): void
    {
        $featureId = FeatureId::fromString('a_feature_id');
        $expectedFeature = Feature::withId($featureId);

        $featureRepository = $this->createMock(FeatureRepository::class);
        $featureRepository
            ->expects($this->once())
            ->method('get')
            ->with($featureId)
            ->willReturn($expectedFeature);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $repository = new EventDispatcherAwareFeatureRepository($featureRepository, $eventDispatcher);

        $actualFeature = $repository->get($featureId);

        $this->assertSame($expectedFeature, $actualFeature);
    }
}
