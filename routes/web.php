<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\WhiteRabbitController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\CampaignController;

Route::get('/', [LandingPageController::class, 'show']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/site-check', [SiteController::class, 'show'])->name('sitecheck');
    Route::post('/site/store', [SiteController::class, 'store'])->name('site.store');
    Route::delete('/site/{id}', [SiteController::class, 'destroy'])->name('site.destroy');

    Route::post('/webhook/store', [WebhookController::class, 'store'])->name('webhook.store');
    Route::delete('/webhook/delete/{id}', [WebhookController::class, 'destroy'])->name('webhook.destroy');


    Route::get('/dashboard/site-report', [SiteController::class, 'index'])->name('sitereport');
    Route::post('/email/config', [EmailController::class, 'store'])->name('email.config.store');
    Route::post('/email/send', [EmailController::class, 'sendDenunciaEmail'])->name('email.send');
    Route::delete('/email/config/{id}', [SiteController::class, 'destroyConfig'])->name('email.config.destroy');


    Route::get('/dashboard/landing', [LandingPageController::class, 'view'])->name('landing');
    Route::post('/landing/create', [LandingPageController::class, 'storeAndLanding'])->name('landing_pages.store');
    Route::post('/landing/store/backup', [LandingPageController::class, 'storeBackupLinks'])->name('backup_links.store');
    Route::delete('/landing-page/{id}', [LandingPageController::class, 'destroyBackup'])->name('delete.backup');
    Route::get('/landing-pages/{id}/edit', [LandingPageController::class, 'edit'])->name('landingPages.edit');
    Route::delete('/landing-pages/{id}', [LandingPageController::class, 'destroy'])->name('landingPages.destroy');
    Route::post('/shopify/{id}/store', [LandingPageController::class, 'storeShopifyLanding'])->name('shopify.store');


    // WHITE RABBIT

    Route::get('/dashboard/whiterabbit', [WhiteRabbitController::class, 'dashboard'])->name('view.rabbit');
    Route::get('/dashboard/whiterabbit/domains', [WhiteRabbitController::class, 'domains'])->name('view.rabbit.domains');
    Route::get('/dashboard/whiterabbit/campaigns', [WhiteRabbitController::class, 'campaigns'])->name('view.rabbit.campaigns');
    Route::get('/dashboard/whiterabbit/requests', [WhiteRabbitController::class, 'requests'])->name('view.rabbit.requests');
    Route::get('/dashboard/whiterabbit/campaign', [WhiteRabbitController::class, 'campaign'])->name('view.rabbit.campaign');

    Route::post('/dashboard/whiterabbit/domain/store', [DomainController::class, 'store'])->name('store.rabbit.domain');
    Route::post('/dashboard/whiterabbit/campaign/store', [CampaignController::class, 'store'])->name('store.rabbit.campaign');

    Route::get('/dashboard/whiterabbit/request/{id}', [WhiteRabbitController::class, 'RequestShow'])->name('cloacker.rabbit.request.show');

    Route::get('/dashboard/whiterabbit/rules', [WhiteRabbitController::class, 'rules'])->name('view.rabbit.rules');
    Route::post('/dashboard/whiterabbit/rules/store', [WhiteRabbitController::class, 'storeRule'])->name('store.rabbit.rules');
});


Route::get('/{id}', [WhiteRabbitController::class, 'cloacker'])->name('cloacker.rabbit');
Route::get('/{id}/safe', [WhiteRabbitController::class, 'safePage'])->name('cloacker.rabbit.safe');