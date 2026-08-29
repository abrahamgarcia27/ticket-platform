<?php

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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
            
Route::get('/events', [EventController::class, 'index'])->middleware('auth')->name('event.index');
Route::get('/event-create', [EventController::class, 'create'])->middleware('auth')->name('event.create');
Route::get('/event-edit/{id}', [EventController::class, 'edit'])->middleware('auth')->name('event.edit');
Route::post('/event-update/{id}', [EventController::class, 'update'])->middleware('auth')->name('event.update');
Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
Route::get('/list-events', [EventController::class, 'events'])->name('event.list-events');
Route::post('/event-store', [EventController::class, 'store'])->middleware('auth')->name('event.store');
Route::delete('/event-delete/{id}', [EventController::class, 'destroy'])->middleware('auth')->name('event.destroy');
Route::get('/create-event', [EventController::class, 'createGoogleEvent']);
Route::get('/event-clone/{id}', [EventController::class, 'clonar'])->name('event.clonar');


Route::get('/tickets/{id}', [TicketController::class, 'index'])->middleware('auth')->name('ticket.index');
Route::get('/tickets-create/{id}', [TicketController::class, 'create'])->middleware('auth')->name('ticket.create');
Route::post('/ticket-store/{id}', [TicketController::class, 'store'])->middleware('auth')->name('ticket.store');
Route::get('/ticket-edit/{id}', [TicketController::class, 'edit'])->middleware('auth')->name('ticket.edit');
Route::post('/ticket-update/{id}', [TicketController::class, 'update'])->middleware('auth')->name('ticket.update');
Route::delete('/ticket-delete/{id}', [TicketController::class, 'destroy'])->middleware('auth')->name('ticket.destroy');

Route::post('/order-store', [OrderController::class, 'store'])->name('order.store');
Route::get('/orders-by-ticket/{id}', [OrderController::class, 'index'])->middleware('auth')->name('order.ticket');
Route::get('/orders-by-event/{id}', [OrderController::class, 'orderByEvent'])->middleware('auth')->name('order.event');
Route::get('/test', [OrderController::class, 'test'])->middleware('auth');
Route::get('/successpage/{codes}', [OrderController::class, 'successpage'])->name('successpage');

Route::get('/test/verify-order-email/{orderId}', [OrderController::class, 'resendOrderEmail'])->name('resendOrderEmail');

Route::get('/pdf/{code}', [OrderController::class, 'pdf'])->name('pdf');
Route::get('/qr/{code}', [OrderController::class, 'generateQr'])->name('qr.generate');
Route::get('/report/event/{id}/pdf', [OrderController::class, 'downloadEventOrdersPdf'])->name('report.event.pdf')->middleware('signed');

Route::get('/sms', [SmsController::class, 'index'])->middleware('auth')->name('sms.index');
Route::get('/contact-list', [SmsController::class, 'indexContactList'])->middleware('auth')->name('contactlist.index');
Route::get('/sms-create', [SmsController::class, 'create'])->middleware('auth')->name('sms.create');
Route::post('/add-campaign', [SmsController::class, 'addCampaingStore'])->middleware('auth')->name('addcampaign.store');
Route::get('/contact-list-create', [SmsController::class, 'createContactList'])->middleware('auth')->name('contactlist.create');
Route::post('/contact-list-store', [SmsController::class, 'storeContactList'])->middleware('auth')->name('contactlist.store');
Route::get('/add-contacts/{id}-{name}', [SmsController::class, 'addContactsTo'])->middleware('auth')->name('contacts.add');
Route::post('/add-contacts-to/{id}-{name}', [SmsController::class, 'addCcontactsToStore'])->middleware('auth')->name('addcontacts.store');

Route::post('/save-tickets-session', [StripeController::class, 'saveTicketsSession']);
Route::post('/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::get('/success', [StripeController::class, 'success'])->name('stripe.success');


Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	//Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	//Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
