<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    use apiResponseTrait;
    public function create(UserRequest $request)
    {
        DB::beginTransaction();

            $data = User::create([
               'name'              => $request->name,
               'email'             => $request->email,
               'password'          => Hash::make($request->password),
               'type'              => $request->type,
            ]);
            DB::commit();
            if ($data) {
                return $this->apiResponse('Record created', [], new UserResource($data), [], 201);
            } else {
                return $this->apiResponse('Application error please try again', [], $data, [], 400);
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
        $items = User::where('state', '!=', 'delete')->get();
        //return data
        return $this->apiResponse('records', [], UserResource::collection($items), [], 200);
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
        $data = User::where('state', '!=', 'delete')->where('id', $id)->first();

        // check for return data
        if ($data) {
            return $this->apiResponse('Record', [], new UserResource($data), [], 200);
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
    public function update(UserRequest $request, $id)
    {
        $data = User::find($id);
        if ($data) {
            DB::beginTransaction();
            try {

                $data->update([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'password'          => Hash::make($request->password),
                    'type'              => $request->type,
                ]);

                DB::commit();
                return $this->apiResponse('Record updated', [], new UserResource($data), [], 201);
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
            $data = User::where('id', $id)->where('type', '!=', 'admin')->update(['state' => 'delete']);
            DB::commit();
            if ($data) {
                return $this->apiResponse('Record deleted', [], [], 201);
            } else {
                return $this->apiResponse('Error! you cannot delete', [], $data, [], 400);
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
    public function search($type)
    {
        //get record data
        $data = User::where('state', '!=', 'delete')->where('type', $type)->get();

        // check for return data
        if ($data) {
            return $this->apiResponse('Record', [], UserResource::collection($data), [], 200);
            // return error record not found
        } else {
            return $this->apiResponse('This record not found', [], [], [], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activeUsers()
    {
        //get record data
        $data = User::where('state', '!=', 'delete')->where('login', 'true')->get();

        // check for return data
        if ($data && count($data) > 0) {
            return $this->apiResponse('Record', ['You have: '.count($data).' active users now'], UserResource::collection($data), [], 200);
            // return error record not found
        } else {
            return $this->apiResponse('No one active', [], [], [], 404);
        }
    }

}
