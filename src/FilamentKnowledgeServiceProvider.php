<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentKnowledgeServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-knowledge';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->discoversMigrations()
            ->runsMigrations();
    }
}
