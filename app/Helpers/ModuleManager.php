<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class ModuleManager
{
    // Gibt alle Module zurück, auch deaktivierte (für Admin-Bereich)
    public static function listAllModules(): array
    {
        $modules = [];
        $modulesPath = base_path('Modules');

        foreach (File::directories($modulesPath) as $moduleDir) {
            $specFile = $moduleDir . '/spec.yml';
            if (File::exists($specFile)) {
                $data = Yaml::parseFile($specFile);

                $modules[] = [
                    'name' => $data['name'] ?? basename($moduleDir),
                    'version' => $data['version'] ?? 'unknown',
                    'description' => $data['description'] ?? '',
                    'author' => $data['author'] ?? '',
                    'tabs' => $data['tabs'] ?? [],
                    'enabled' => $data['enabled'] ?? false,
                    'path' => $moduleDir,
                ];
            }
        }

        return $modules;
    }

    // Gibt nur aktivierte Module zurück
    public static function listActiveModules(): array
    {
        return array_filter(self::listAllModules(), function ($module) {
            return $module['enabled'] === true;
        });
    }

    public static function getModuleValue(string $module, string $key)
    {
        foreach (self::listAllModules() as $mod) {
            if (strtolower($mod['name']) === strtolower($module)) {
                $specFile = $mod['path'] . '/spec.yml';
                if (File::exists($specFile)) {
                    $data = Yaml::parseFile($specFile);
                    return $data[$key] ?? null;
                }
            }
        }

        return null;
    }

    public static function getAllTabs(): array
    {
        $tabs = [];

        foreach (self::listActiveModules() as $module) {
            if (!empty($module['tabs']) && is_array($module['tabs'])) {
                foreach ($module['tabs'] as $tab) {
                    // Validate required keys exist
                    if (isset($tab['name'], $tab['link'])) {
                        $tabs[] = [
                            'name' => $tab['name'],
                            'link' => $tab['link'],
                        ];
                    }
                }
            }
        }

        return $tabs;
    }
}
