<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    use apiResponseTrait;
    public function create(ProductRequest $request)
    {
        DB::beginTransaction();
        //date_default_timezone_set('Africa/Cairo');
        try {
            $data = Product::create([
                    'category_id'    => $request->category_id,
                    'user_id'        => $request->user_id,
                    'name'           => $request->name,
                    'description'    => $request->description,
                    'price'          => $request->price,
                    'color'          => $request->color,
                    'prand'          => $request->prand,
                    'image'          => $request->image,
            ]);
            DB::commit();
            if ($data) {
                return $this->apiResponse('Record created', [], new ProductResource($data), [], 201);
            } else {
                return $this->apiResponse('Application error please try again', [], $data, [], 400);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->apiResponse('Application error please try again', [], $th, [], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function records()
    {
        $items = Product::where('state', '!=', 'delete')->get();
        //return data
        return $this->apiResponse('records', [], ProductResource::collection($items), [], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function record($id)
    {
        //get record data
        $data = Product::where('state', '!=', 'delete')->where('id', $id)->first();

        // check for return data
        if ($data) {
            return $this->apiResponse('Record', [], new ProductResource($data), [], 200);
            // return error record not found
        } else {
            return $this->apiResponse('This record not found', [], [], [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //date_default_timezone_set('Africa/Cairo');
        $data = Product::find($id);
        if ($data) {
            DB::beginTransaction();
            try {

                $data->update([
                    'category_id'    => $request->category_id,
                    'user_id'        => $request->user_id,
                    'name'           => $request->name,
                    'description'    => $request->description,
                    'price'          => $request->price,
                    'color'          => $request->color,
                    'prand'          => $request->prand,
                    'image'          => $request->image,
                ]);
                DB::commit();
                return $this->apiResponse('Record updated', [], new ProductResource($data), [], 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->apiResponse('Record not found', [], $th, [], 400);
            }
        } else {
            return $this->apiResponse('Record not found', $data, [], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = Product::where('id', $id)->update(['state' => 'delete']);
            DB::commit();
            if ($data) {
                return $this->apiResponse('Record deleted', [], [], 201);
            } else {
                return $this->apiResponse('Error', [], $data, [], 400);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->apiResponse('Error', [], $th, [], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchbycategory($id)
    {
        //get record data by category
        $data = Product::where('state', '!=', 'delete')->where('category_id', $id)->get();

        // check for return data
        if ($data && count($data) > 0) {
            return $this->apiResponse('Record', ['This category has: '.count($data).' products'], ProductResource::collection($data), [], 200);
            // return error record not found
        } else {
            return $this->apiResponse('This category is empty', [], [], [], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchbyTrader($id)
    {
        //get record data by category
        $data = Product::where('state', '!=', 'delete')->where('user_id', $id)->get();

        // check for return data
        if ($data && count($data) > 0) {
            return $this->apiResponse('Record', ['This Trader has: '.count($data).' products'], ProductResource::collection($data), [], 200);
            // return error record not found
        } else {
            return $this->apiResponse('This Trader has no products', [], [], [], 404);
        }
    }
}
