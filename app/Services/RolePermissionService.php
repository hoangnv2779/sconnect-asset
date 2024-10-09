<?php

namespace App\Services;

use App\Repositories\RolePermissionRepository;

class RolePermissionService
{
    public function __construct(
        protected RolePermissionRepository $rolePermissionRepository,
    ) {

    }

    public function insertRolePermissions(array $permissionIds, $roleId)
    {
        $dataInsertRolePermissions = [];
        foreach ($permissionIds as $permissionId) {
            $dataInsertRolePermissions[] = [
                'permission_id' => $permissionId,
                'role_id'       => $roleId,
            ];
        }

        return $this->rolePermissionRepository->insert($dataInsertRolePermissions);
    }

    public function insertRolesPermission(array $roleIds, $permissionId)
    {
        $dataInsertRolePermissions = [];
        foreach ($roleIds as $roleId) {
            $dataInsertRolePermissions[] = [
                'permission_id' => $permissionId,
                'role_id'       => $roleId,
            ];
        }

        return $this->rolePermissionRepository->insert($dataInsertRolePermissions);
    }

    public function updateRolePermissions(array $permissionIds, $roleId)
    {
        $rolePermissions  = $this->rolePermissionRepository->getListing(['role_id' => $roleId]);
        $permissionIdsOld = $rolePermissions->pluck('permission_id')->toArray();

        $newPermissionIds    = array_diff($permissionIds, $permissionIdsOld);
        $removePermissionIds = array_diff($permissionIdsOld, $permissionIds);

        if (!empty($newPermissionIds)) {
            $insertRolePermissions = $this->insertRolePermissions($newPermissionIds, $roleId);
            if (!$insertRolePermissions) {
                return false;
            }
        }

        if (!empty($removePermissionIds)) {
            $this->rolePermissionRepository->deleteRolePermissions($removePermissionIds, $roleId);
        }

        return true;
    }
}
