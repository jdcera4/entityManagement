<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ChallengesResource;
use App\Models\Challenges;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ChallengesController extends Controller
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
        $challenges = Challenges::paginate($items, ['*'], 'page', $page);
        return ChallengesResource::collection($challenges);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $data = Challenges::create($request->all());
            return new ChallengesResource($data);
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
            $challenge = Challenges::find($id);
            if ($challenge == null) {
                throw new ModelNotFoundException('Challenge not found', 404);
            }
            return new ChallengesResource($challenge);
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
            $challenge = Challenges::find($id);
            if ($challenge) {
                $challenge->update($request->all());
                return new ChallengesResource($challenge);
            } else {
                throw new ModelNotFoundException('Challenge not found', 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Not update challenge',
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
            $challenge = Challenges::find($id);
            if (isset($challenge) && $challenge->delete()) {
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
