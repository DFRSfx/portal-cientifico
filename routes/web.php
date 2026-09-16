<?php

use App\Http\Controllers\AuthorController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\CienciaVitaeController;
use App\Http\Controllers\AuthorRequestController;
use App\Http\Controllers\PublicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('auth')->group(function () {
    Route::get('get-authors-info', [AuthorRequestController::class, 'getAuthorsInfo'])->name('all-authors-info');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('dashboard', [DashboardController::class, "index"])->name('dashboard');

    /*

    output route


    */
    Route::resource('outputs', 'App\Http\Controllers\PublicationController')->only([
        'index'
    ]);
    Route::get("exportAuthors", [ExcelController::class, "exportAuthors"])->name("pub.export");

    Route::get("outputs/filter", 'App\Http\Controllers\PublicationController@filter')->name("outputs.filter");
    /*

    Authors Route

    */
    Route::prefix('authors')->group(function () {
        Route::post("{authorId}/outputs/{outputId}/quartile", "App\Http\Controllers\AuthorController@updateOutputQuartile")
            ->name('authors.outputs.quartile.update');
        Route::get("admin", "App\Http\Controllers\AdminController@allPublications")->name("admin");
        Route::get("admin/institution-search", "App\Http\Controllers\AdminController@institutionSearch")->name("admin.institution-search");
        Route::post("admin/quality/{outputId}", "App\Http\Controllers\AdminController@updateQuality")->name("admin.quality.update");
        Route::get("update", "App\Http\Controllers\AuthorController@updateOneAuthorInfo")->name('update-info');
        Route::get("update/{authorId}", "App\Http\Controllers\AuthorController@updateAuthorById")->name('authors.update.one');
        Route::post("{authorId}/metrics", "App\Http\Controllers\AuthorController@updateMetrics")->name('authors.metrics.update');
        Route::get("myinfo", "App\Http\Controllers\ExcelController@exportOneAuthor")->name("myinfo");
        Route::get("ceos-excel", "App\Http\Controllers\CeosController@exportOneAuthor")->name("ceos-excel");
        Route::get("ceos-excel-all", "App\Http\Controllers\CeosController@exportAllAuthors")->name("ceos-excel-all");
    });

    Route::prefix('users')->group(function () {
        Route::post('/import', [UserController::class, 'import'])->name('import');
        Route::get("create", [UserController::class, "create"])->name("user.create");
        Route::post("store", [UserController::class, "store"])->name("user.store");
        Route::get("inactive", [UserController::class, "getAllInactiveUsers"])->name("user.inactive");
        Route::get("active", [UserController::class, "getAllActiveUsers"])->name("user.active");
        Route::get("{userId}/edit", [UserController::class, "edit"])->name("user.edit");
        Route::patch("{userId}", [UserController::class, "update"])->name("user.update");
        Route::post("{userId}/resend-notification", [UserController::class, "reesendUserToken"])->name("user.resend-notification");
        Route::post("{userId}/activate", [UserController::class, "activate"])->name("user.activate");
        Route::post("{userId}/deactivate", [UserController::class, "deactivate"])->name("user.deactivate");
        Route::post("{userId}/resend-verification", [UserController::class, "resendVerification"])->name("user.resend-verification");
        Route::delete("{userId}", [UserController::class, "destroy"])->name("user.destroy");
    });

    Route::get("statistics", [StatisticsController::class, "index"])->name('statistics');
});

Route::get('/authors/update-all', [AuthorController::class, 'updateAllAuthors'])->name('authors.update.all');
Route::post('/authors/update-all-job', [AuthorController::class, 'startUpdateAllAuthorsJob'])->name('authors.update.job');
Route::get('/authors/update-all-job/{runId}', [AuthorController::class, 'getUpdateAllAuthorsJobStatus'])->name('authors.update.job.status');

Route::resource("authors", "App\Http\Controllers\AuthorController")->only([
    'index',
    "show"
]);

Route::prefix('authors')->group(function () {
    Route::get("filter", "App\Http\Controllers\AuthorController@filter")->name("author.filter");
    Route::get("{authorsId}/activities", "App\Http\Controllers\AuthorController@getAuthorActivities")->name("authors.activities");
    Route::get("{id}/statistics", "App\Http\Controllers\AuthorController@getAuthorStats")->name("authors.statistics");
    Route::get("{id}/employments", "App\Http\Controllers\AuthorController@getAuthorEmployments")->name("authors.employments");
    Route::get("{authorsId}/outputs", 'App\Http\Controllers\PublicationController@showAuthorOutputs')->name("authors.outputs");
    Route::get("{authorsId}/projects", 'App\Http\Controllers\AuthorController@projects')->name("authors.projects");
});

Route::prefix("statistics")->group(function () {
    Route::get("events", "App\Http\Controllers\StatisticsController@getEventsStats");
    Route::get("publications", "App\Http\Controllers\StatisticsController@getPublicationsStats");
});

require __DIR__ . '/auth.php';

Route::get('/', "App\Http\Controllers\HomeController@index")->name("home");

Route::get("help", function () {
    return view("pages.help", ["appTitle" => "Help Page"]);
})->name("help.index");

Route::get("about", function () {
    return view("pages.about", ["appTitle" => "Sobre Nós"]);
})->name("about.index");
Route::get("privacy", function () {
    return view("pages.privacy", ["appTitle" => "Politica de privacidade"]);
})->name("privacy");
Route::get("search", function () {
    return view("pages.search", ["appTitle" => "Pesquisa"]);
})->name("search.index");

Route::get('/outputs/{output}', [PublicationController::class, 'show'])->name('outputs.show');
Route::get('/outputs/{output}/coauthors', [PublicationController::class, 'coauthors'])->name('outputs.coauthors');
Route::get('/authors/{id}/pdf', [AuthorController::class, 'downloadCvPdf'])->name('authors.pdf');

/*
language Route
*/
Route::get('/lang/{locale}', function ($locale) {
    $langIsNotValid = !in_array($locale, ['en', 'pt', 'fr']);

    if ($langIsNotValid) {
        abort(400);
    }
    // Sets the selected language in the session
    session(['my_locale' => $locale]);

    return redirect()->back();
})->name("language");
