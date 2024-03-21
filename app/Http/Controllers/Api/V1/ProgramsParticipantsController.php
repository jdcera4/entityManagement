<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProgramsParticipants as V1ProgramsParticipants;
use App\Models\ProgramsParticipants;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProgramsParticipantsController extends Controller
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
        $programs_participants = ProgramsParticipants::paginate($items, ['*'], 'page', $page);
        return V1ProgramsParticipants::collection($programs_participants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            if (ProgramsParticipants::validateEntities($request)) {
                $data = ProgramsParticipants::create($request->all());
                return new V1ProgramsParticipants($data);
            } else {
                throw new Exception('Combination entity id and entity type not found or no allowed', 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data no saved!',
                'error' => $e->getMessage(),
                'code' => $e->getCode()
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
            $program_participant = ProgramsParticipants::find($id);
            if ($program_participant == null) {
                throw new ModelNotFoundException('Program Participant not found', 404);
            }
            return new V1ProgramsParticipants($program_participant);
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
            $program_participant = ProgramsParticipants::find($id);
            if ($program_participant ) {
                if (ProgramsParticipants::validateEntities($request)) {
                    $program_participant->update($request->all());
                    return new V1ProgramsParticipants($program_participant);
                } else {
                    throw new Exception('Combination entity id and entity type not found or no allowed', 404);
                }
            } else {
                throw new ModelNotFoundException('Program Participant not found', 400);
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
            $program_participant = ProgramsParticipants::find($id);
            if (isset($program_participant) && $program_participant->delete()) {
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
