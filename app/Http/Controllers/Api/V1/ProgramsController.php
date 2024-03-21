<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProgramsResource;
use App\Models\Programs;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProgramsController extends Controller
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
        $programs = Programs::paginate($items, ['*'], 'page', $page);
        return ProgramsResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $data = Programs::create($request->all());
            return new ProgramsResource($data);
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
            $program = Programs::find($id);
            if ($program == null) {
                throw new ModelNotFoundException('Program not found', 404);
            }
            return new ProgramsResource($program);
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
            $program = Programs::find($id);
            if ($program) {
                $program->update($request->all());
                return new ProgramsResource($program);
            } else {
                throw new ModelNotFoundException('Program not found', 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Not update program',
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
            $program = Programs::find($id);
            if (isset($program) && $program->delete()) {
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
