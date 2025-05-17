<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Modules\RoleManager\Models\Role;
use Modules\RoleManager\Models\Permission;

return new class extends Migration
{
    public function up()
    {
        try {
            DB::beginTransaction();

            // 1. Verify all required tables exist
            if (!Schema::hasTable('roles') || !Schema::hasTable('permissions') || !Schema::hasTable('role_permission')) {
                throw new \Exception('Required tables are missing');
            }

            // 2. Process roles in small batches (removed withTrashed())
            Role::whereNotNull('permissions')
                ->orderBy('id')
                ->chunk(100, function ($roles) {
                    foreach ($roles as $role) {
                        $this->migrateRolePermissions($role);
                    }
                });

            DB::commit();
            Log::info('Permission migration completed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission migration failed: ' . $e->getMessage());
            throw $e; // Re-throw to mark migration as failed
        }
    }

    protected function migrateRolePermissions(Role $role)
    {
        try {
            // 1. Decode JSON permissions
            $permissions = json_decode($role->permissions, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($permissions)) {
                Log::warning("Invalid JSON permissions for role {$role->id}");
                return;
            }

            // 2. Process each permission
            foreach ($permissions as $permissionName) {
                if (!is_string($permissionName)) {
                    continue;
                }

                // 3. Find or create permission
                $permission = Permission::firstOrCreate(
                    ['name' => trim($permissionName)],
                    [
                        'route' => $this->generateRouteFromName($permissionName),
                        'description' => $this->generateDescription($permissionName)
                    ]
                );

                // 4. Attach if not already exists
                if (!$role->permissions()->where('permission_id', $permission->id)->exists()) {
                    $role->permissions()->attach($permission->id);
                    Log::debug("Attached permission {$permission->name} to role {$role->name}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to migrate permissions for role {$role->id}: " . $e->getMessage());
        }
    }

    protected function generateRouteFromName(string $name): string
    {
        return str_replace('_', '.', $name);
    }

    protected function generateDescription(string $name): string
    {
        return ucfirst(str_replace('_', ' ', $name));
    }

    public function down()
    {
        // This is a one-way data migration
        // No rollback needed as it doesn't modify schema
    }
};
