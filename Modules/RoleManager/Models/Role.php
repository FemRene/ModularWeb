<?php
namespace Modules\RoleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'json_permissions',  // if you renamed the JSON permissions column as recommended
        'permissions',  // if you renamed the JSON permissions column as recommended
    ];

    protected $casts = [
        'json_permissions' => 'array'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function getAllPermissionsAttribute()
    {
        $dbPermissions = $this->permissions()->pluck('name')->toArray();
        $jsonPermissions = $this->json_permissions ?? [];

        return array_unique(array_merge($dbPermissions, $jsonPermissions));
    }
}
