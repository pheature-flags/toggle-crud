<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Container\ContainerInterface;

final class CreateFeatureFactory
{
    public function __invoke(ContainerInterface $container): CreateFeature
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return self::create($featureRepository);
    }

    public static function create(FeatureRepository $featureRepository): CreateFeature
    {
        return new CreateFeature($featureRepository);
    }
}
