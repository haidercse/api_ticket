<?php

namespace App\Http\Controllers\Api\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Services\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        try {
            $permissions = Permission::all();
            return $this->successResponse($permissions, 'Permission Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
       
    }

    public function create(PermissionStoreRequest $request)
    {
        $data = [];
        foreach ($request->name as $name) {
            $data[] = [
                'guard_name' => 'web',
                'group_name' =>  $request->group_name,
                'name' =>  $name,
            ];
           
        }
        $permission = Permission::create($data);
        if($permission){
            return $this->successResponse($permission, 'Role Add Successfully');
        }else{
            return $this->errorResponse(null, 'Internal Issue.', JsonResponse::HTTP_NOT_FOUND);
        }

    }
    public function editById($id)
    {
        $permission = Permission::findById($id);
        if (empty($permission)) {
            return $this->errorResponse(null, 'This Permission is not found.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($permission, 'Get Permission Data Successfully');

    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|unique:permissions,name,'.$id,
            'group_name' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 401);
        }

        $permission = Permission::findById($id);
        $permission->guard_name = 'web';
        $permission->group_name = $request->group_name;
        $permission->name = $request->name;
        $permission->save();

        return $this->successResponse($permission, 'Permission Update successfully.');

    }

    public function destroy($id)
    {
        try {
            $organization = Permission::findById($id);
            if (empty($organization)) {
                return $this->errorResponse(null, 'This Organization is not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $deleted = $organization->delete();
            if (!$deleted) {
                return $this->errorResponse(null, 'Failed To delete Permission.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($organization, 'Permission Deleted Successfully');

        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }


    }
}
