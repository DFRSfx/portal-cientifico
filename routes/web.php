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
use App\Http\Controllers\TeacherPersonalEvaluationExcel;

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



Route::get('reboot', function () {

    Artisan::call('migrate');
    dd("done");
    // Artisan::call('view:clear');
    // Artisan::call('route:clear');
    // Artisan::call('config:clear');
    // Artisan::call('cache:clear');
    // //  Artisan::call('storage:link');

    // //  Artisan::call('key:generate');
});
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
        Route::get("filter", "App\Http\Controllers\AuthorController@filter")->name("author.filter");
        Route::get("{authorsId}/activities", "App\Http\Controllers\AuthorController@getAuthorActivities")->name("authors.activities");
        Route::get("{id}/statistics", "App\Http\Controllers\AuthorController@getAuthorStats")->name("authors.statistics");
        Route::get("{id}/employments", "App\Http\Controllers\AuthorController@getAuthorEmployments")->name("authors.employments");
        Route::get("{authorsId}/outputs", 'App\Http\Controllers\PublicationController@showAuthorOutputs')->name("authors.outputs");
        Route::get("{authorsId}/projects", 'App\Http\Controllers\AuthorController@projects')->name("authors.projects");
        Route::get("admin", "App\Http\Controllers\AdminController@allPublications")->name("admin");
        Route::get("update", "App\Http\Controllers\AuthorController@updateOneAuthorInfo")->name('update-info');
        Route::get("myinfo", "App\Http\Controllers\ExcelController@exportOneAuthor")->name("myinfo");
        Route::get("ceos-excel", "App\Http\Controllers\CeosController@exportOneAuthor")->name("ceos-excel");
 Route::get("ceos-excel-all", "App\Http\Controllers\CeosController@exportAllAuthors")->name("ceos-excel-all");
    });



Route::get('/authors/update-all', [AuthorController::class, 'updateAllAuthors'])->name('authors.update.all');


    Route::resource("authors", "App\Http\Controllers\AuthorController")->only([
        'index',
        "show"
    ]);



    Route::prefix('users')->group(function () {
        Route::post('/import', [UserController::class, 'import'])->name('import');
        Route::get("create", [UserController::class, "create"])->name("user.create");
        Route::post("store", [UserController::class, "store"])->name("user.store");
        Route::get("inactive", [UserController::class, "getAllInactiveUsers"])->name("user.inactive");
        Route::post("{userId}/resend-notification", [UserController::class, "reesendUserToken"])->name("user.resend-notification");
    });

    Route::prefix("statistics")->group(function () {
        Route::get("events", "App\Http\Controllers\StatisticsController@getEventsStats");
        Route::get("publications", "App\Http\Controllers\StatisticsController@getPublicationsStats");
        Route::get("", [StatisticsController::class, "index"])->name('statistics');
    });
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
