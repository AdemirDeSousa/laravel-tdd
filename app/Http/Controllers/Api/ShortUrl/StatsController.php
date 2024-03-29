<?php

namespace App\Http\Controllers\Api\ShortUrl;

use App\Facades\Actions\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    public function lastVisit(ShortUrl $shortUrl)
    {
        return response()->json([
            'last_visit' => $shortUrl->last_visit?->toIso8601String()
        ]);
    }

    public function visits(ShortUrl $shortUrl)
    {
        $visits = $shortUrl->visits()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m-%d')")
            ->get();

        return [
            'total' => $shortUrl->visits()->count(),
            'visits' => $visits
        ];
    }
}

