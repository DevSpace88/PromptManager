<?php
//
//use App\Http\Controllers\ProfileController;
//use App\Http\Controllers\ApiKeyController;
//use App\Http\Controllers\DashboardController;
//use App\Http\Controllers\PlaygroundController;
//use App\Http\Controllers\PromptController;
//use App\Http\Controllers\SettingsController;
//use App\Http\Controllers\WorkflowController;
//use Illuminate\Foundation\Application;
//use Illuminate\Support\Facades\Route;
//use Inertia\Inertia;
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//*/
//
//// Landing (Guest)
//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//})->name('welcome');
//
//// Authenticated Routes
//Route::middleware(['auth', 'verified'])->group(function () {
//    // Dashboard
//    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//
//    // Profile
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//
//    // Prompts
//    Route::resource('prompts', PromptController::class);
//    Route::post('/prompts/{prompt}/versions/{version}/set-current', [PromptController::class, 'setVersion'])
//        ->name('prompts.versions.set-current');
//
//    // Workflows
//    Route::resource('workflows', WorkflowController::class);
//    Route::post('/workflows/{workflow}/execute', [WorkflowController::class, 'execute'])
//        ->name('workflows.execute');
//    Route::get('/executions/{executionLog}/status', [WorkflowController::class, 'getExecutionStatus'])
//        ->name('executions.status');
//    Route::post('/workflows/export/{workflow}', [WorkflowController::class, 'exportWorkflow'])
//        ->name('workflows.export');
//    Route::post('/workflows/import', [WorkflowController::class, 'importWorkflow'])
//        ->name('workflows.import');
//
//    // Playground
//    Route::get('/playground', [PlaygroundController::class, 'index'])->name('playground.index');
//    Route::get('/playground/{prompt}', [PlaygroundController::class, 'show'])->name('playground.show');
//    Route::post('/playground/test', [PlaygroundController::class, 'test'])->name('playground.test');
//    Route::post('/playground/compare', [PlaygroundController::class, 'compare'])->name('playground.compare');
//
//    // API Keys
//    Route::resource('api-keys', ApiKeyController::class)->except(['show', 'edit', 'update']);
//    Route::put('/api-keys/{apiKey}', [ApiKeyController::class, 'update'])->name('api-keys.update');
//
//    // Settings
//    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
//    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
//});
//
//// Catch-all route for SPA
//Route::get('/{any}', function () {
//    return Inertia::render('NotFound');
//})->where('any', '.*')->middleware(['auth']);
//
///*
//|--------------------------------------------------------------------------
//| Auth Routes
//|--------------------------------------------------------------------------
//*/
//
//require __DIR__ . '/auth.php';
//
///*
//|--------------------------------------------------------------------------
//| Preview Pages Routes
//|--------------------------------------------------------------------------
//*/
//
//require __DIR__ . '/preview.php';


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\PlaygroundController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
//    Route::get('/dashboard', function () {
//        return Inertia::render('Dashboard');
//    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Prompts
    Route::resource('prompts', PromptController::class);
    Route::post('/prompts/{prompt}/versions/{version}/set-current', [PromptController::class, 'setVersion'])
        ->name('prompts.versions.set-current');

    // Workflows
    Route::resource('workflows', WorkflowController::class);
    Route::post('/workflows/{workflow}/execute', [WorkflowController::class, 'execute'])
        ->name('workflows.execute');
    Route::get('/executions/{executionLog}/status', [WorkflowController::class, 'getExecutionStatus'])
        ->name('executions.status');
    Route::post('/workflows/export/{workflow}', [WorkflowController::class, 'exportWorkflow'])
        ->name('workflows.export');
    Route::post('/workflows/import', [WorkflowController::class, 'importWorkflow'])
        ->name('workflows.import');

    // Playground
    Route::get('/playground', [PlaygroundController::class, 'index'])->name('playground.index');
    Route::get('/playground/{prompt}', [PlaygroundController::class, 'show'])->name('playground.show');
    Route::post('/playground/test', [PlaygroundController::class, 'test'])->name('playground.test');
    Route::post('/playground/compare', [PlaygroundController::class, 'compare'])->name('playground.compare');

    // API Keys
    Route::resource('api-keys', ApiKeyController::class)->except(['show', 'edit', 'update']);
    Route::put('/api-keys/{apiKey}', [ApiKeyController::class, 'update'])->name('api-keys.update');

    // Settings
    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->name('settings.index');

    Route::put('/settings', function (Illuminate\Http\Request $request) {
        // Implementiere hier die Logik zum Aktualisieren der Einstellungen

        return redirect()->route('settings.index')->with('success', 'Einstellungen wurden aktualisiert');
    })->name('settings.update');
});

//// Catch-all route for SPA,
//Route::get('/{any}', function () {
//    return Inertia::render('NotFound');
//})->where('any', '.*')->middleware(['auth']);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Preview Pages Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/preview.php';
