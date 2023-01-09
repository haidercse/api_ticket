<?php

namespace App\Http\Controllers\Api\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        try {
            $organizations = Organization::all();
            return $this->successResponse($organizations, 'Organization Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validateData = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'short_name' => 'required',
                'address' => 'nullable'
            ]
        );

        if ($validateData->fails()) {
            return $this->errorResponse($validateData->errors(), 'validation error', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $organization = new Organization();
        $organization->name = $request->name;
        $organization->short_name = $request->short_name;
        $organization->address = $request->address;
        $organization->save();

        return $this->successResponse($organization, 'Organization Add Successfully');
    }
    public function editById($id)
    {
        $organization = Organization::findOrFail($id);
        if (empty($organization)) {
            return $this->errorResponse(null, 'This Organization is not found.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($organization, 'Get Organization Data Successfully');
    }
    public function update(Request $request, $id)
    {
        $validateData = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'short_name' => 'required',
                'address' => 'nullable'
            ]
        );

        if ($validateData->fails()) {
            return $this->errorResponse($validateData->errors(), 'validation error', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $organization = Organization::find($id);
        $organization->name = $request->name;
        $organization->short_name = $request->short_name;
        $organization->address = $request->address;
        $organization->save();

        return $this->successResponse($organization, 'Organization Updated Successfully');
    }

    public function destroy($id)
    {
        try {
            $organization = Organization::findOrFail($id);
            if (empty($organization)) {
                return $this->errorResponse(null, 'This Organization is not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $deleted = $organization->delete();

            if (!$deleted) {
                return $this->errorResponse(null, 'Failed To delete Product.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($organization, 'Organization Deleted Successfully');

        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }



    }
}