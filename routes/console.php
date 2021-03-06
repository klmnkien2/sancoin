<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('CreateUser {username} {email} {password}', function ($username, $email, $password) {
    \App\Helper\CreateUser::compile($username, $email, $password);
})->describe('Create an user with username, email and password');

Artisan::command('CreateAdmin {username} {email} {password}', function ($username, $email, $password) {
    \App\Helper\CreateAdmin::compile($username, $email, $password);
})->describe('Create an admin with username, email and password');
