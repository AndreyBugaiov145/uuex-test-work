<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyVersionResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function store(CompanyRequest $request): JsonResponse
    {
        $company = Company::updateOrCreate(
            ['edrpou' => $request->get('edrpou')],
            ['name' => $request->get('name'), 'address' => $request->get('address')]
        );
        $company->current_version = $company->getCurrentVersion();

        $status = match ($company->getOperationStatus()) {
            'created' => Response::HTTP_CREATED,
            default => Response::HTTP_OK,
        };

        return response()->json(new CompanyResource($company))->setStatusCode($status);
    }

    public function versions(int $id): JsonResponse
    {
        $company = Company::withMax('versions as current_version', 'version')->findOrFail($id);

        return response()->json([
            'company' => new CompanyResource($company),
            'versions' => CompanyVersionResource::collection(
                $company->versions()->orderBy('version')->get()
            ),
        ]);
    }

    public function index()
    {
        $companies = Company::query()->withMax('versions as current_version', 'version')->paginate(15);

        return CompanyResource::collection($companies);
    }

    public function show(string $id)
    {
        $company = Company::find($id);

        if (! $company) {
            return response()->json([
                'status' => 'error',
                'message' => __('api.company_not_found'),
            ], 404);
        }

        return new CompanyResource($company);
    }

    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->noContent();
    }
}
