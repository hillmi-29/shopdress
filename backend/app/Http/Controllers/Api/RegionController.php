<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RegionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct(
        protected RegionService $regionService
    ) {
    }

    /**
     * List all Indonesian provinces.
     */
    public function provinces(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->regionService->getProvinces(),
        ]);
    }

    /**
     * List regencies / cities (kabupaten / kota) by province code.
     */
    public function regencies(Request $request): JsonResponse
    {
        $provinceCode = trim((string) $request->query('province_code'));
        if ($provinceCode === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter province_code wajib diisi.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->regionService->getRegencies($provinceCode),
        ]);
    }

    /**
     * List districts (kecamatan) by regency code.
     */
    public function districts(Request $request): JsonResponse
    {
        $regencyCode = trim((string) $request->query('regency_code'));
        if ($regencyCode === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter regency_code wajib diisi.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->regionService->getDistricts($regencyCode),
        ]);
    }

    /**
     * List villages (kelurahan / desa) with postal codes by district code.
     */
    public function villages(Request $request): JsonResponse
    {
        $districtCode = trim((string) $request->query('district_code'));
        if ($districtCode === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter district_code wajib diisi.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->regionService->getVillages($districtCode),
        ]);
    }
}
