<?php
namespace Modules\RoleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'route', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
