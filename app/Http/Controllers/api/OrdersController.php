<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    use apiResponseTrait;
    public function create(OrderRequest $request)
    {
        DB::beginTransaction();
        //date_default_timezone_set('Africa/Cairo');
        try {
            $data = Order::create([
                    'customer_id'   => $request->customer_id,
                    'zip_code'      => $request->zip_code,
                    'address'       => $request->address,
                    'phone'         => $request->phone,
                    'city'          => $request->city,
            ]);
            DB::commit();
            if ($data) {
                return $this->apiResponse('Record created', [], new OrderResource($data), [], 201);
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
        $items = Order::where('state', '!=', 'delete')->get();
        //return data
        return $this->apiResponse('records', [], OrderResource::collection($items), [], 200);
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
        $data = Order::where('state', '!=', 'delete')->where('id', $id)->first();

        // check for return data
        if ($data) {
            return $this->apiResponse('Record', [], new OrderResource($data), [], 200);
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
        $data = Order::find($id);
        if ($data) {
            DB::beginTransaction();
            try {

                $data->update([
                    'customer_id'   => $request->customer_id,
                    'zip_code'      => $request->zip_code,
                    'address'       => $request->address,
                    'phone'         => $request->phone,
                    'city'          => $request->city,
                ]);
                DB::commit();
                return $this->apiResponse('Record updated', [], new OrderResource($data), [], 201);
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
            $data = Order::where('id', $id)->update(['state' => 'delete']);
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
}
