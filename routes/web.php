<?php

use App\Post;
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

Auth::routes();

// Family
Route::get('/family', 'FamilyController@index')->name('family');
Route::get('/create-family', 'FamilyController@createFamily')->name('family-create');
Route::get('/delete-family', 'FamilyController@deleteFamily')->name('family-delete');
Route::get('/add-to-family/{id}', 'FamilyController@addToFamily')->name('family-add');
Route::get('/remove-from-family/{id}', 'FamilyController@removeFromFamily')->name('family-remove');
Route::get('/accept-family-request', 'FamilyController@acceptFamilyRequest')->name('family-accept');
Route::get('/reject-family-request', 'FamilyController@rejectFamilyRequest')->name('family-reject');
Route::get('/cancel-family-request', 'FamilyController@cancelFamilyRequest')->name('family-cancel');
Route::get('/view-family-members', 'FamilyController@viewFamilyMembers')->name('family-view');
Route::get('/leave-family', 'FamilyController@leaveFamily')->name('family-leave');

// Friends
Route::get('/friends', 'FriendController@index')->name('friends');
Route::get('/send-friend-request/{id}', 'FriendController@sendFriendRequest')->name('friend-add');
Route::get('/accept-friend-request/{id}', 'FriendController@acceptFriendRequest')->name('friend-accept');
Route::get('/cancel-friend-request/{id}', 'FriendController@cancelFriendRequest')->name('friend-cancel');
Route::get('/reject-friend-request/{id}', 'FriendController@rejectFriendRequest')->name('friend-reject');
Route::get('/unfriend/{id}', 'FriendController@unfriend')->name('unfriend');

// Posts
Route::get('/home', 'PostController@index')->name('home');
Route::get('/list', 'PostController@posts')->name('home');
Route::post('/post', 'PostController@store')->name('post-store');
Route::get('/post/delete', 'PostController@trash')->name('post-trash');

// Trash
Route::get('/trash', 'TrashController@index')->name('trash');
Route::get('/trash-restore', 'TrashController@restore')->name('trash-restore');

