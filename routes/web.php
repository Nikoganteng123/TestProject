<?php

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('Test email', function ($message) {
        $message->to('2281014@unai.edu')->subject('Testing');
    });

    return 'Email sent!';
});

