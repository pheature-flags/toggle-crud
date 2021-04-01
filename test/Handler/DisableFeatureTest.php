<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Command;

use Pheature\Crud\Toggle\Command\DisableFeature as DisableFeatureCommand;
use Pheature\Crud\Toggle\FeatureRepository;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Model\Feature;
use Pheature\Crud\Toggle\Model\FeatureId;
use PHPUnit\Framework\TestCase;

final class DisableFeatureTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';

    public function testItShouldEnableAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $feature->enable();
        $command = DisableFeatureCommand::withId(self::FEATURE_ID);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $handler = new DisableFeature($repository);
        $handler->handle($command);
        $this->assertFalse($feature->isEnabled());
    }
}