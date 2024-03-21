<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UsersResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $items = $request->query('items') ?? 10;
        $users = User::paginate($items, ['*'], 'page', $page);
        return UsersResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $data = User::create($request->all());
            return new UsersResource($data);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data no saved!',
                'error' => $th->getMessage(),
                'code' => $th->getCode()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(int $id)
    {
        try {
            $user = User::find($id);
            if ($user == null) {
                throw new ModelNotFoundException('User not found', 404);
            }
            return new UsersResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, int $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->update($request->all());
                return new UsersResource($user);
            } else {
                throw new ModelNotFoundException('User not found', 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Not update user',
                'error' => $th->getMessage(),
                'code' => $th->getCode()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        try {
            $user = User::find($id);
            if (isset($user) && $user->delete()) {
                return response()->json([
                    'message' => 'Deleted successfully'
                ], 200);
            } else {
                throw new Exception("No delete data", 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'No delete data',
                'error' => $th->getMessage(),
                'code' => $th->getCode()
            ], 400);
        }
    }
}
