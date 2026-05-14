<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product:slug}', [HomeController::class, 'productShow'])->name('products.show');
Route::post('/products/{product:slug}/enquiry', [HomeController::class, 'storeEnquiry'])->name('products.enquiry.store')->middleware('throttle:5,1');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store')->middleware('throttle:5,1');
Route::get('/dealers', [HomeController::class, 'dealers'])->name('dealers.index');
Route::get('/api/dealers/search', [HomeController::class, 'searchDealers'])->name('dealers.search');

Route::get('/health', function () {
    $checks = [
        'db' => true,
        'storage' => true,
        'queue' => true,
        'mail' => true,
        'queue_driver' => config('queue.default'),
        'mail_driver' => config('mail.default'),
    ];

    try {
        DB::connection()->getPdo();
    } catch (Throwable $e) {
        $checks['db'] = false;
    }

    try {
        Storage::disk('public')->listContents('/');
    } catch (Throwable $e) {
        $checks['storage'] = false;
    }

    try {
        if (config('queue.default') !== 'sync') {
            Queue::connection()->size('__health');
        }
    } catch (Throwable $e) {
        $checks['queue'] = false;
    }

    try {
        if (config('mail.default') === 'log') {
            $checks['mail'] = true;
        } else {
            $mailer = Mail::mailer(config('mail.default'));
            $checks['mail'] = (bool) method_exists($mailer, 'getSymfonyTransport');
        }
    } catch (Throwable $e) {
        $checks['mail'] = false;
    }

    return response()->json([
        'status' => ! in_array(false, $checks, true) ? 'healthy' : 'degraded',
        'checks' => $checks,
    ]);
});

Route::get('/sitemap.xml', function () {
    $urls = [
        url('/'),
        route('about'),
        route('gallery'),
        route('events'),
        route('careers'),
        route('contact'),
        route('products.index'),
        route('dealers.index'),
    ];
    $productUrls = Product::query()->pluck('slug')->map(fn($slug) => route('products.show', $slug))->toArray();
    $urls = array_merge($urls, $productUrls);

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    foreach ($urls as $url) {
        $xml .= '  <url><loc>' . e($url) . '</loc><lastmod>' . now()->toDateString() . '</lastmod></url>' . PHP_EOL;
    }
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
});
