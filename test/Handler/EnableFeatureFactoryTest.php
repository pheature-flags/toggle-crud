<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeatureFactory;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class EnableFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfEnableFeature(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with(FeatureRepository::class)
            ->willReturn($featureRepository);

        $enableFeatureFactory = new EnableFeatureFactory();

        $enableFeatureFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfEnableFeatureStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);

        $addStrategy = EnableFeatureFactory::create($featureRepository);

        $this->assertInstanceOf(EnableFeature::class, $addStrategy);
    }
}
