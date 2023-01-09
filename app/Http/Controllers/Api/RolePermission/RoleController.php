<?php

namespace App\Http\Controllers\Api\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\User;
use App\Services\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        try {
            $products = Role::all();
            return $this->successResponse($products, 'Role Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAllPermission()
    {
        try {
            $permissions = Permission::all();
            return $this->successResponse($permissions, 'Permission Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getPermissionGroup()
    {
        try {
            $permission_group = User::getPermissionGroups();
            return $this->successResponse($permission_group, 'Permission Group Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(RoleStoreRequest $request )
    {
        $role =  Role::create($request->validated());

        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
            //$permission->syncRoles($roles);
        }
        return $this->successResponse($role, 'Role Add Successfully');
    }

    public function editById($id)
    {
        $role = Role::findById($id);
        if (empty($role)) {
            return $this->errorResponse(null, 'This Role is not found.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($role, 'Get Role Data Successfully');
    }

    public function update(RoleUpdateRequest $request,$id)
    {
        $role = Role::findById($id);
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->is_internal = $request->is_internal;
            $role->description = $request->description;
            $role->save();
            $role->syncPermissions($permissions);
        }
        return $this->successResponse($role, 'Get Role Data Successfully');
    }

    public function destroy($id)
    {
        $roleInfo = Role::where('id', $id)->first();
    
        if ($roleInfo->name == 'super-admin') {
            return $this->errorResponse(null, 'This is super admin, It will be not deleted', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $role = Role::findById($id);

            if (!is_null($role)) {
                if(!($roleInfo->users->isEmpty())){
                    return $this->errorResponse(null, 'This role will be not deleted', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                }else{
                    $role->delete();
                }

            }
            return $this->successResponse($role, 'Role Deleted Successfully');
        }
    }
}