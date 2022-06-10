<?php

use App\Events\MessageNotification;
use App\Http\Controllers\AdvancedChatController;
use App\Http\Controllers\SendMessageController;
use App\Models\AdvancedChat;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-message/{name}/{message}', [SendMessageController::class, 'index']);

Route::get('/chat', function() {
    return view('chat');
});

Route::get('/advanced-chat', [AdvancedChatController::class, 'index']);
Route::get('/advanced-chat/send-message/{name}/{message}/{event}', [AdvancedChatController::class, 'send_message']);
Route::get('/advanced-chat/get-messages/{event}', [AdvancedChatController::class, 'get_messages']);

Route::post('/pusher/auth', [AdvancedChatController::class, 'pusher_auth']);


/////////////////////////////////////////////
////////////////// OBSOLETE /////////////////
Route::get('/event', function() {
    event(new MessageNotification("Ridho", "Hello there!"));
});

Route::get('/listen', function() {
    return view('listen');
});