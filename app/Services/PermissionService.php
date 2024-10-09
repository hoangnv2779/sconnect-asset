<?php

namespace App\Services;

use App\Http\Resources\PermissionInfoResource;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\UserPermissionRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function __construct(
        protected PermissionRepository $permissionRepository,
        protected RolePermissionRepository $rolePermissionRepository,
        protected UserPermissionRepository $userPermissionRepository,
    ) {

    }

    public function createPermission($data)
    {
        $permission = $this->permissionRepository->getFirst(['name' => $data['name']]);
        if (!empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2044,
            ];
        }

        $data['created_by'] = Auth::id();
        DB::beginTransaction();
        try {
            $permission = $this->permissionRepository->create($data);

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                $insertUsersPermission = resolve(UserPermissionService::class)->insertUsersPermission($userIds, $permission->id);
                if (!$insertUsersPermission) {
                    DB::rollBack();

                    return  [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2051,
                    ];
                }
            }

            $roleIds = $data['role_ids'] ?? [];
            if (!empty($roleIds)) {
                $insertRolePermissions = resolve(RolePermissionService::class)->insertRolesPermission($roleIds, $permission->id);
                if (!$insertRolePermissions) {
                    DB::rollBack();

                    return  [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2050,
                    ];
                }
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getListPermission($filters)
    {
        $data = $this->permissionRepository->getListing($filters, ['id', 'name', 'description']);

        return $data->toArray();
    }

    public function deletePermissionById($id)
    {
        $permission = $this->permissionRepository->find($id);
        if (empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2047,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$permission->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2046,
                ];
            }

            $this->userPermissionRepository->deleteByPermissionId($id);
            $this->rolePermissionRepository->deleteByPermissionId($id);

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updatePermission($data, $id)
    {
        $permission = $this->permissionRepository->find($id);
        if (empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2047,
            ];
        }

        $data['updated_by'] = Auth::id();
        $permission->fill($data);
        if (!$permission->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2048,
            ];
        }

        return ['success' => true];
    }

    public function findPermission($id)
    {
        $permission = $this->permissionRepository->getFirst(['id' => $id], with: ['usersPermission', 'rolesPermission']);

        return PermissionInfoResource::make($permission)->resolve();
    }
}
