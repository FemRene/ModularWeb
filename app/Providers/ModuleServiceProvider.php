<?php
namespace App\Providers;

use App\Helpers\ModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        foreach (ModuleManager::listActiveModules() as $module) {
            $moduleName = $module['name'];
            $modulePath = $module['path'];
            $moduleSlug = strtolower($moduleName);

            // Load permissions from spec.yml
            $specPath = $modulePath . '/spec.yml';
            if (File::exists($specPath) && Schema::hasTable('permissions')) {
                $spec = Yaml::parseFile($specPath);
                if (!empty($spec['permissions'])) {
                    foreach ($spec['permissions'] as $perm) {
                        DB::table('permissions')->updateOrInsert(
                            ['name' => $perm['permission']],
                            [
                                'route' => $perm['route'] ?? null,
                                'description' => $perm['description'] ?? '',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }

            if (!File::isDirectory($modulePath)) {
                logger()->warning("Skipping module {$moduleName}: path does not exist");
                continue;
            }

            // Load public routes
            if (File::exists($modulePath . '/routes/web.php')) {
                $this->loadRoutesFrom($modulePath . '/routes/web.php');
            }

            // Load admin routes with middleware 'auth' + 'permission'
            if (File::exists($modulePath . '/routes/admin.php')) {
                if (Schema::hasTable('permissions')) {
                    Route::middleware(['web', 'auth', 'permission'])
                        ->name("modules.{$moduleSlug}.")
                        ->group($modulePath . '/routes/admin.php');
                } else {
                    Route::middleware(['web', 'auth'])
                        ->name("modules.{$moduleSlug}.")
                        ->group($modulePath . '/routes/admin.php');
                }
            }

            // Load views
            $viewsPath = $modulePath . '/views';
            if (File::isDirectory($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $moduleName);
            }

            // Load migrations
            if (File::isDirectory($modulePath . '/database/migrations')) {
                $this->loadMigrationsFrom($modulePath . '/database/migrations');
            }
        }
    }

    public function register()
    {
        config(['modules.modules' => ModuleManager::listActiveModules()]);
    }
}
