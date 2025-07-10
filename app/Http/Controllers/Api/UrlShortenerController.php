<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UrlShortenerController extends Controller
{
    /**
     * Create a shortened URL.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
