<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Output;
use App\Models\OutputType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;

class HomeController extends Controller
{
    /**
     * Caches one request
     * @param string $cacheVariable
     * @param int $ttl
     * @param array $requestInformation
     * @return mixed
     */
public function index()
    {
        $lastOutputs = Output::whereNotNull('year')->with('type')->orderBy('year', 'desc')->limit(3)->get();
        $numberOfAuthors = DB::table('authors')->count();
        $numberOfOutputs = DB::table('outputs')->count();


        $ttl = 60 * 60 * 24 * 7; // 1 week

        $eventsUrl  = env('EVENTS_API_URL', 'https://investigacao.islagaia.pt/wp-json/wp/v2/tribe_events');
        $coursesUrl = env('COURSES_API_URL', 'https://ci-islagaia.pt/wp-json/learnpress/v1/courses');

        $eventsFinal = Cache::remember('external_events', $ttl, function () use ($eventsUrl) {
            try {
                $res = Http::timeout(6)->get($eventsUrl, ['per_page' => 5, '_fields' => 'title,link']);
                return $res->ok() ? $res->json() : [];
            } catch (\Throwable $e) {
                \Log::warning('Events fetch failed: '.$e->getMessage());
                return [];
            }
        });

        $coursesFinal = Cache::remember('external_courses', $ttl, function () use ($coursesUrl) {
            try {
                $res = Http::timeout(6)->get($coursesUrl, ['per_page' => 5, '_fields' => 'name,permalink']);
                return $res->ok() ? $res->json() : [];
            } catch (\Throwable $e) {
                \Log::warning('Courses fetch failed: '.$e->getMessage());
                return [];
            }
        });

        return view('pages.home', compact('lastOutputs', 'numberOfAuthors', 'numberOfOutputs', 'eventsFinal', 'coursesFinal'));
    }
}
