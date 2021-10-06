<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\RemoveFeature as RemoveFeatureCommand;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use PHPUnit\Framework\TestCase;

final class RemoveFeatureTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';

    public function testItShouldRemoveAFeature(): void
    {
        $command = RemoveFeatureCommand::withId(self::FEATURE_ID);
        $repository = $this->createMock(FeatureRepository::class);

        $expectedFeature = Feature::withId($command->featureId());

        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->featureId())
            ->willReturn($expectedFeature);

        $repository->expects($this->once())
            ->method('remove')
            ->with($this->callback(function (Feature $feature) use ($expectedFeature) {
                $this->assertEquals($expectedFeature, $feature);

                return true;
            }));

        $handler = new RemoveFeature($repository);
        $handler->handle($command);
    }
}
