<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CaptchaServiceController;
use App\Http\Controllers\Client\CallbackController;
use App\Http\Controllers\Client\ClientHomeController;
use App\Http\Controllers\Client\SignupController;
use App\Http\Controllers\Moder\AboutController as AboutEditController;
use App\Http\Controllers\Moder\ContactsController;
use App\Http\Controllers\Moder\GalleryController;
use App\Http\Controllers\Moder\MapController;
use App\Http\Controllers\Moder\MastersController;
use App\Http\Controllers\Moder\PagesController;
use App\Http\Controllers\Moder\PriceEditController;
use App\Http\Controllers\Moder\ServicePageEditController;
use App\Http\Controllers\Moder\SheduleMasterController;
use App\Http\Controllers\Moder\SignupController as ModerSignupController;
use App\Http\Controllers\Moder\SignupSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAdminControllers\CallbacksEditController;
use App\Http\Controllers\UserAdminControllers\SignupController as UserSignupController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/reload_captcha', [CaptchaServiceController::class, 'reloadCaptcha'])->name('captcha.reload');
/*
 * HOME PAGES ROUTES
 */
Route::name('client.')
->group(function () {
    // Client home route
    Route::get('/', [ClientHomeController::class, 'index'])->name('home');

    Route::get('/{page_alias?}', [ClientHomeController::class, 'page'])
    ->where('page_alias', '^((?!login|register|dashboard|admin|api|reload_captcha|profile).)*$');

    Route::middleware('throttle:15,1')->group(function () {
        Route::post('/callback/store', [CallbackController::class, 'store'])->name('callback.store');
        Route::post('/callback/send_mail', [CallbackController::class, 'send_mail'])->name('callback.send_mail');

        Route::post('/signup/masters', [SignupController::class, 'appoint_masters'])->name('signup.masters');
        Route::post('/signup/time', [SignupController::class, 'appoint_time'])->name('signup.time');
        Route::post('/signup/check', [SignupController::class, 'appoint_check'])->name('signup.check');
        Route::post('/signup/end', [SignupController::class, 'appoint_end'])->name('signup.end');
        Route::post('/signup/list', [SignupController::class, 'signup_list'])->name('signup.list');
        Route::post('/signup/edit', [SignupController::class, 'signup_edit'])->name('signup.edit');
        Route::post('/signup/store', [SignupController::class, 'signup_store'])->name('signup.store');
        Route::post('/signup/remove', [SignupController::class, 'signup_remove'])->name('signup.remove');
        Route::post('/signup/appoint_time', [SignupController::class, 'appoint_time'])->name('signup.appoint_time');
        Route::post('/signup/get_masters', [SignupController::class, 'get_masters'])->name('signup.get_masters');
    });

    // Route::any('/{any?}', 'AppController@show')->where('any', '^((?!admin|api).)*$');
});
/*
* ADMIN PAGES ROUTES
*/
Route::prefix('admin')->name('admin.')
->middleware(['auth', 'verified'])
->group(function () {
    // Admins home route same for admin, moder, user
    Route::get('/', [AdminHomeController::class, 'getview'])->name('home');

    // ADMINS routes
    Route::middleware('isadmin')->group(function () {
        Route::controller(RegisteredUserController::class)
        ->prefix('register')
        ->name('register.')
        ->group(function () {
            Route::get('/', 'create')
            ->middleware(['auth', 'verified'])
            ->name('register');
            Route::post('/', 'store')
            ->middleware(['auth', 'verified']);
        });

        Route::controller(UsersController::class)
            ->prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('/add', 'add')->name('add');
                Route::get('/remove', 'list')->name('remove');
                Route::post('/remove', 'remove')->name('post_remove');
                Route::get('/change', 'list')->name('change');
                Route::post('/change', 'show')->name('show');
                Route::post('/change/store', 'store')->name('store');
            });

        Route::controller(SignupSettingsController::class)
            ->prefix('signup')
            ->name('signup.')
            ->group(function () {
                Route::get('/settings', 'settings')->name('settings');
                Route::post('/settings', 'store')->name('settings.store');
            });

        Route::controller(LogsController::class)
            ->prefix('logs')
            ->name('logs.')
            ->group(function () {
                Route::post('/list', 'show')->name('show');
                Route::get('/list', 'index')->name('show');
                Route::get('/list', 'index')->name('list');
            });

        Route::prefix('cache')
            ->name('cache.')
            ->group(function () {
                Route::get('/clear', function () {
                    Artisan::call('cache:clear');
                    Artisan::call('config:cache');
                    Artisan::call('view:clear');
                    Artisan::call('route:clear');
                    Artisan::call('clear-compiled');
                    Artisan::call('event:cache');

                    return response('Сброс кэша выполнен!');
                })->name('clear');
            });
    });
    /*
    * ADMIN AND MODER ROUTES
    */
    Route::middleware('ismoder')->group(function () {
        Route::controller(ContactsController::class)
        ->prefix('contacts')
        ->name('contacts.')
        ->group(function () {
            Route::get('/', 'index')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/remove', 'index')->name('remove');
            Route::post('/remove', 'destroy')->name('destroy');
            Route::get('/edit', 'index')->name('edit');
            Route::post('/edit', 'edit')->name('post_edit');
            Route::post('/edit/update', 'update')->name('update');
        });

        // Route::resource('pages', PagesController::class);
        Route::controller(PagesController::class)
        ->prefix('pages')
        ->name('pages.')
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/remove', 'index')->name('edit');
            Route::post('/remove', 'destroy')->name('remove');
            Route::get('/edit', 'index')->name('edit');
            Route::post('/edit', 'edit')->name('edit.form');
            Route::post('/update', 'update')->name('update');
        });

        Route::controller(ServicePageEditController::class)
        ->prefix('service_page')
        ->name('service_page.')
        ->group(function () {
            Route::get('/', 'index')->name('edit');
            Route::get('/create', 'create')->name('create');
            Route::get('/services', 'services_edit')->name('services_edit');
            Route::post('/services', 'go')->name('go');
            Route::get('/content', 'content')->name('content');
            Route::post('/content', 'content')->name('post_content');
        });

        Route::controller(PriceEditController::class)
        ->prefix('price')
        ->name('price.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::get('/edit', 'post_edit')->name('post_edit');
            Route::post('/edit', 'post_edit')->name('post_edit');
            Route::post('/update', 'update')->name('update');
        });

        Route::controller(AboutEditController::class)
        ->prefix('about_editor')
        ->name('about_editor.')
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/remove', 'index')->name('remove');
            Route::post('/remove', 'destroy')->name('destroy');
            Route::get('/edit', 'index')->name('edit');
            Route::post('/edit', 'edit')->name('post_edit');
            Route::post('/edit/update', 'update')->name('update');
        });

        Route::controller(GalleryController::class)
        ->prefix('gallery')
        ->name('gallery.')
        ->group(function () {
            Route::get('/', 'index')->name('edit');
            Route::post('/', 'go')->name('go');
        });

        Route::controller(MapController::class)
        ->prefix('map')
        ->name('map.')
        ->group(function () {
            Route::get('/', 'index')->name('edit');
            Route::post('/', 'go')->name('go');
        });

        Route::controller(MastersController::class)
        ->prefix('masters')
        ->name('masters.')
        ->group(function () {
            Route::get('/', 'index')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::post('/remove', 'destroy')->name('remove');
            Route::get('/edit', 'index')->name('list');
            Route::post('/edit', 'edit')->name('edit.form');
            Route::post('/edit/update', 'update')->name('update');
            Route::get('/shedule', [SheduleMasterController::class, 'index'])->name('shedule');
            Route::post('/shedule/edit', [SheduleMasterController::class, 'edit'])->name('shedule.edit');
            Route::post('/shedule/store', [SheduleMasterController::class, 'store'])->name('shedule.store');
        });

        Route::controller(ModerSignupController::class)
        ->prefix('signup')
        ->name('signup.')
        ->group(function () {
            Route::get('/by_date', 'by_date')->name('by_date');
            Route::get('/by_master', 'by_master')->name('by_master');
            Route::post('/by_master', 'post_by_master')->name('post_by_master');
            Route::get('/by_client', 'by_client')->name('by_client');
            Route::post('/by_client/post', 'post_by_client')->name('by_client.post');

            Route::get('/past', 'past')->name('past_list');
            Route::get('/future', 'future')->name('future_list');

            Route::post('/edit', 'edit')->name('edit');
            Route::post('/get_master_times', 'get_master_times')->name('get_master_times');
            Route::post('/get_masters', 'get_masters')->name('get_masters');
            Route::post('/edit/post', 'post_edit')->name('edit.post');

            Route::post('/remove', 'remove')->name('remove');
        });
    });

    /*
    * ADMIN AND MODER AND USER ROUTES
    */
    Route::middleware('isuser')->group(function () {
        Route::controller(CallbacksEditController::class)
        ->prefix('callbacks')
        ->name('callbacks.')
        ->group(function () {
            Route::get('/need', 'need')->name('need');
            Route::post('/need', 'update')->name('update');
            Route::get('/completed', 'completed')->name('completed');
            Route::post('/completed', 'destroy')->middleware('ismoder')->name('remove');
        });

        Route::controller(UserSignupController::class)
        ->prefix('master_signup')
        ->name('master_signup.')
        ->group(function () {
            Route::get('/list', 'list')->name('list');
        });
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.DIRECTORY_SEPARATOR.'auth.php';
// load functions files in composer.json
// require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'func.php';
// require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'sanitize_functions.php';
