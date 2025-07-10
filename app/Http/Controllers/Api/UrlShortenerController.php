<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="URL Shortener API Documentation",
     *      description="API documentation for URL shortening",
     *      @OA\Contact(
     *          email="support@votreapp.com"
     *      ),
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Main server"
     * )
     */
class UrlShortenerController extends Controller
{
    /**
     * Create a shortened URL.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     /**
     * @OA\Post(
     *     path="/api/shorten",
     *     summary="Shorten a URL",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="https://example.com"),
     *             @OA\Property(property="custom_code", type="string", example="custom01"),
     *             @OA\Property(property="expires_at", type="string", example="2025-07-31 23:59:59")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shortened URL"
     *     )
     * )
     */
    public function shorten(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:2048',
            'custom_code' => 'nullable|alpha_num|unique:short_urls,short_code',
            'expires_at' => 'nullable|date|after:now',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Generate a random short code if not provided
        $shortCode = $request->custom_code ?? Str::random(6);
        // Check if the short code already exists
        $shortUrl = ShortUrl::create([
            'original_url' => $request->url,
            'short_code' => $shortCode,
            'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
        ]);
        //This will create a new ShortUrl record in the database
        return response()->json([
            'short_url' => url($shortUrl->short_code),
            'original_url' => $shortUrl->original_url,
            'short_code' => $shortUrl->short_code,
            'expires_at' => $shortUrl->expires_at,
        ]);
    }
    /**
     * Redirect to the original URL.
     *
     * @param string $code
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
        /**
     * @OA\Get(
     *     path="/{code}",
     *     summary="Recover original URL",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to original URL"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found or expired"
     *     )
     * )
     */
    public function redirect($code)
    {
        // Find the short URL by its code
        $short = ShortUrl::where('short_code', $code)->first();
        // If the short URL does not exist or has expired, return an error
        if (!$short || ($short->expires_at && now()->gt($short->expires_at))) {
            return response()->json(['message' => 'URL expirée ou non trouvée'], 404);
        }
        // Increment the click count and log the click
        $short->increment('click_count');
        // This will increment the click_count field in the ShortUrl model
        $short->clicks()->create([
            'clicked_at' => now()
        ]);
        // This will create a new Click record in the database
        return redirect()->to($short->original_url);
    }
    /**
     * Get statistics for a shortened URL.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
        /**
     * @OA\Get(
     *     path="/api/stats/{code}",
     *     summary="Recover statistics from a shortened URL",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shortened URL statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="original_url", type="string", example="https://example.com"),
     *             @OA\Property(property="short_code", type="string", example="abc123"),
     *             @OA\Property(property="click_count", type="integer", example=10),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-09T23:45:00Z"),
     *             @OA\Property(property="expires_at", type="string", format="date-time", example="2025-07-31T23:59:59Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found"
     *     )
     * )
     */
    public function stats($code)
    {
        // prevent SQL injection by using parameter binding
        $short = ShortUrl::where('short_code', $code)->first();
        // If the short URL does not exist, return an error
        if (!$short) {
            return response()->json(['message' => 'URL non trouvée'], 404);
        }
        // This will retrieve the ShortUrl record from the database
        return response()->json([
            'original_url' => $short->original_url,
            'short_code' => $short->short_code,
            'click_count' => $short->click_count,
            'created_at' => $short->created_at,
            'expires_at' => $short->expires_at,
        ]);
    }
}
