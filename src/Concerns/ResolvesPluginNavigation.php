<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Concerns;

use Filament\Facades\Filament;
use Madbox99\FilamentKnowledge\FilamentKnowledgePlugin;
use Throwable;
use UnitEnum;

/**
 * Lets the host application move both resources into its own navigation group
 * through the plugin's fluent API, instead of hard-coding the group per resource.
 */
trait ResolvesPluginNavigation
{
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return static::plugin()?->getNavigationGroup() ?? parent::getNavigationGroup();
    }

    protected static function plugin(): ?FilamentKnowledgePlugin
    {
        try {
            $plugin = Filament::getPlugin('filament-knowledge');
        } catch (Throwable) {
            return null;
        }

        return $plugin instanceof FilamentKnowledgePlugin ? $plugin : null;
    }
}
