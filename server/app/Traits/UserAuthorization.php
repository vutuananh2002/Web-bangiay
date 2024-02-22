<?php

namespace App\Traits;

use App\Enums\AccountStatusEnum;
use App\Enums\AccountVerifyEnum;
use App\Models\Role;
use App\Enums\UserRoleEnum;

trait UserAuthorization
{
    protected $permissionList = null;

    /**
     * Get the roles that belong to the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Check if user has role.
     * 
     * @param \App\Enums\UserRoleEnum $role
     * @return mixed
     */
    public function hasRole($role)
    {
        return $this->roles->contains('role', $role);
    }

    /**
     * Check if user has permission.
     * 
     * @param \App\Enums\UserPermissionsEnum|null $permission
     * @return mixed
     */
    public function hasPermission($permission = null)
    {
        if (is_null($permission)) {
            return $this->getPermissions()->count() > 0;
        }

        return $this->getPermissions()->contains('permission', $permission);
    }

    /**
     * Get permission list of the role.
     * 
     * @return mixed
     */
    private function getPermissions()
    {
        $role = $this->roles->first();

        if ($role) {
            if (!$role->relationLoaded('permissions')) {
                $role->load('permissions');
            }

            $this->permissionList = $this->roles->pluck('permissions')->flatten();
        }

        return $this->permissionList ?? collect();
    }

    /**
     * Determine whether user is Administrator.
     * 
     * @return bool
     */
    public function isAdministrator()
    {
        return ($this->hasRole(UserRoleEnum::Administrator) || $this->hasRole(UserRoleEnum::SuperAdministrator));
    }

    /**
     * Determine whether user is Super Administrator.
     * 
     * @return bool
     */
    public function isSuperAdministrator()
    {
        return $this->hasRole(UserRoleEnum::SuperAdministrator);
    }

    /**
     * Determine whether user's account is active.
     * 
     * @return bool
     */
    public function isActive() {
        return ($this->is_verified === AccountVerifyEnum::Verified && $this->status === AccountStatusEnum::Active);
    }

    /**
     * Mark the given user's status is active.
     * 
     */
    public function markUserAsActive() {
        $this->is_verified = AccountVerifyEnum::Verified;
        $this->status = AccountStatusEnum::Active;
        $this->save();
    }
}
