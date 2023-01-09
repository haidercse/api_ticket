<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Services\ImageUpload;
use App\Services\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    use ResponseTrait;
    
    public function index()
    {
        try {
            $products = Product::all();
            return $this->successResponse($products, 'Product Data get Successfully');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();
        if($request->hasFile('image')){
            $re_image = ImageUpload::upload($request, 'image', 'images/product');
        }
        $data['image'] = $re_image;
        $product = Product::create($data);
        return $this->successResponse($product, 'Product Add Successfully');
    }

    public function editById($id)
    {
        $product = Product::findOrFail($id);
        if (empty($product)) {
            return $this->errorResponse(null, 'This Product is not found.', JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->successResponse($product, 'Get Product Data Successfully');
    }
    public function update(ProductUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $product = Product::find($id);
        
        if($request->hasFile('image')){
            if(File::exists($product->image)){
                File::delete($product->image);
            }
            $re_image = ImageUpload::upload($request, 'image', 'images/product');
        }
        $data['image'] = $re_image;
       
        $product = $product->update($data);
        return $this->successResponse($product, 'Product Updated Successfully');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if (empty($product)) {
                return $this->errorResponse(null, 'This Product is not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $deleted = $product->delete();

            if (!$deleted) {
                return $this->errorResponse(null, 'Failed To delete Product.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($product, 'Product Deleted Successfully');

        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }



    }
}