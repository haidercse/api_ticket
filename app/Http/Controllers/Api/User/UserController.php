<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait;
    public function getAllClient()
    {
        try {
            $clients = User::whereHas('roles', function ($query) {
                $query->where('name', 'Client');
            });
            return $this->successResponse($clients, 'All Client get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAllWithoutClient()
    {
        try {
            $clients = User::whereHas('roles', function ($query) {
                $query->where('name','!=', 'Client');
            });
            return $this->successResponse($clients, 'All Role Without Client get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['product_id'] = $request->product_id ? $request->product_id : null;
        $data['organization_id'] = $request->organization_id ? $request->organization_id : null;

        $user = User::create($data);

        if (empty($user)) {
            return $this->errorResponse(null, 'User not created.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($user, 'User  created Successfully');
    }

    public function editById($id)
    {
        $user = User::findOrFail($id);
        if (empty($user)) {
            return $this->errorResponse(null, 'This User is not found.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($user, 'Get User Data Successfully');
    }

    public function update(Request $request,$id)
    {
        $validateUser = Validator::make($request->all(), 
        [
            'name'              => 'required|max:50|min:3',
            'email'             => 'required|unique:users,email,'.$id,
            'registration_date' => 'nullable|date',
            'username'          => 'required|unique:users,username,'.$id,
            'status'            => 'required',
            'product_id'        => 'nullable',
            'organization_id'   => 'nullable',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::find($id);
        $user = $user->update($validateUser);

        if (empty($user)) {
            return $this->errorResponse(null, 'User is not update.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($user, 'User Update successfully');

    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if (empty($user)) {
                return $this->errorResponse(null, 'This User is not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $deleted = $user->delete();

            if (!$deleted) {
                return $this->errorResponse(null, 'Failed To delete User.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($user, 'User Deleted Successfully');

        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function passwordUpdate(Request $request)
    {
        $auth_id = auth()->user()->id;
        $user =  User::find($auth_id);
        $user = $user->update([
            'password' => $request->password,
        ]);
    }
}
