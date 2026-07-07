<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            // You can easily add more URLs here as you create format landing pages or blog posts:
            // [
            //     'loc' => url('/convert/jpg-to-png'),
            //     'lastmod' => '2026-07-07',
            //     'changefreq' => 'weekly',
            //     'priority' => '0.9',
            // ],
        ];

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
