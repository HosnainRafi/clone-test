<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Exception;

abstract class BaseController extends Controller
{
    protected $siteContentService;

    public function __construct(SiteContentService $siteContentService)
    {
        $this->siteContentService = $siteContentService;
    }

    /**
     * Get site data from request
     */
    protected function getSiteData(Request $request)
    {
        return $request->get('siteData');
    }

    /**
     * Get site ID from request
     */
    protected function getSiteId(Request $request)
    {
        return $this->siteContentService->resolveSiteId($request);
    }

    /**
     * Standard success response
     */
    protected function successResponse($message, $data = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            ...$data
        ]);
    }

    /**
     * Standard error response
     */
    protected function errorResponse($message, $code = 500, $data = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            ...$data
        ], $code);
    }

    /**
     * Handle save operation with standard error handling
     */
    protected function handleSave(Request $request, $settingsKey, $dataKey, $validationRules = [])
    {
        try {
            $siteId = $this->getSiteId($request);
            $data = $request->input($dataKey);

            // Basic validation
            if (!$data || !is_array($data)) {
                return $this->errorResponse("{$dataKey} are required and must be an array", 422);
            }

            // Additional validation if provided
            if (!empty($validationRules)) {
                $request->validate($validationRules);
            }

            // Validate JSON structure
            $this->siteContentService->validateJson($data);

            // Save to database
            $this->siteContentService->saveSiteSettings($siteId, $settingsKey, $data);

            return $this->successResponse(
                ucfirst($settingsKey) . ' configuration saved successfully!',
                [
                    'siteId' => $siteId,
                    $dataKey . 'Count' => count($data)
                ]
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to save ' . $settingsKey . ' configuration: ' . $e->getMessage()
            );
        }
    }
}
