<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CompaniesResource;
use App\Models\Companies;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompaniesController extends Controller
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
        $companies = Companies::paginate($items, ['*'], 'page', $page);
        return CompaniesResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $data = Companies::create($request->all());
            return new CompaniesResource($data);
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
            $company = Companies::find($id);
            if ($company == null) {
                throw new ModelNotFoundException('Company not found', 404);
            }
            return new CompaniesResource($company);
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
            $company = Companies::find($id);
            if ($company) {
                $company->update($request->all());
                return new CompaniesResource($company);
            } else {
                throw new ModelNotFoundException('Company not found', 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Not update companie',
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
            $company = Companies::find($id);
            if (isset($company) && $company->delete()) {
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
