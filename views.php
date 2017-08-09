<?php
/**
 * Register the various view template files to a given function or methods of Controller. 
 * The NamedView farcade will enable tags, data, user preferenes to be passed to the view automatically. 
 * Future works will enable mapping views to routes and support for multiple view types. 
 * Currently only php and blade.php template supported *
 * @author frier17 (a17s)
 */

//define identities
$default = \App\NamedView\NamedView::ID_OPT_DEFAULT;
$admin = \App\NamedView\NamedView::ID_OPT_ADMIN;
$owner = \App\NamedView\NamedView::ID_OPT_OWNER;


// define the registered views for the application
NamedView::register('HomeController@index', $view='home.blade.php', $identity = $default,
    $data = [], $messages = [], function($view, $identity) {
    NamedView::map('HomeController@index', $view, $identity);
});

NamedView::register(
    $names = [
    'AccommodationController@cancelBooking',
    'AccommodationController@createUserBooking',
    'AccommodationController@showUserBooking',
    'AccommodationController@storeUserBooking',
    ], $view='accommodation.booking.show.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AccommodationController@index',
    $view = 'accommodation.index.blade.php',
    $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AccommodationController@show', $view = 'accommodation.show.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register($names = [
    'AnnouncementController@broadcastByEmail',
    'AnnouncementController@broadcastBySMS'
    ], $view = 'admin.announcement.broadcast.blade.php', $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register($names = 'AnnouncementController@index',
    $view = 'admin.announcement.broadcast.index.blade.php', $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register($names = ['AnnouncementController@create', 'AnnouncementController@edit'],
    $view = 'admin.announcement.broadcast.create.blade.php', $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AnnouncementController@destroy',
    $view = 'admin.announcement.broadcast.destroy.blade.php', $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AnnouncementController@showBroadcast', $view = 'admin.announcement.broadcast.show.blade.php',
    $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register([
    'AnnouncementController@broadcastByEmail',
    'AnnouncementController@broadcastBySMS'
    ], $view = 'admin.announcement.broadcast.show.blade.php', $identity = $admin, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AnnouncementController@attention', $view = 'announcement.attention.blade.php',
    $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AnnouncementController@index', $view = 'announcement.index.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('AnnouncementController@show', $view = 'announcement.show.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('CalendarController@indexGlobalCalendar', $view = 'calendar.global.blade.php',
    $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register([
    'CalendarController@createUserCalendar',
    'CalendarController@editUserCalendar'
    ], $view = 'calendar.user.create.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('CalendarController@editUserCalendar',
    $view = 'calendar.user.create.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('CalendarController@showUserCalendar',
    $view = 'calendar.user.show.blade.php', $identity =  $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('SpeakerController@index',
    $view = 'conference.speakers.index.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('SpeakerController@show',
    $view = 'conference.speakers.show.blade.php',
    $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@userPay',
    $view = 'payment.create.blade.php',
    $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@confirmPay',
    $view = 'payment.confirm.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@index',
    $view = 'payment.index.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@show',
    $view = 'payment.show.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@cancel',
    $view = 'payment.cancel.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@storeConfirmPay',
    $view = 'payment.store.confirm.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('PaymentController@storeUserPay',
    $view = 'payment.store.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('ProfileController@edit',
    $view = 'profile.edit.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('ProfileController@show',
    $view = 'profile.show.blade.php', $identity = $owner, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});


NamedView::register('UserController@createSubscription',
    $view = 'subscription.create.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

NamedView::register('UserController@destroySubscription',
    $view = 'subscription.destroy.blade.php', $identity = $default, function($names, $view, $identity) {
    NamedView::map($names, $view, $identity);
});

