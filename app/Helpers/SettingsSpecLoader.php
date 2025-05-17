<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class SettingsSpecLoader
{
    public static function getAllSettings()
    {
        $settings = [];

        foreach (ModuleManager::listActiveModules() as $module) {
            $moduleName = is_array($module) ? $module['name'] : $module;
            $specPath = base_path("modules/{$moduleName}/spec.yml");

            if (File::exists($specPath)) {
                $yaml = Yaml::parseFile($specPath);
                if (isset($yaml['settings'])) {
                    foreach ($yaml['settings'] as $setting) {
                        $settings[$setting['key']] = $setting + ['module' => $module];
                    }
                }
            }
        }

        return $settings;
    }

    public static function getAllSettingsGroupedByModule(): array
    {
        $grouped = [];

        foreach (ModuleManager::listActiveModules() as $module) {
            $specPath = $module['path'] . '/spec.yml';
            if (File::exists($specPath)) {
                $data = Yaml::parseFile($specPath);
                if (!empty($data['settings'])) {
                    $grouped[$module['name']] = $data['settings'];
                }
            }
        }

        return $grouped;
    }
}
