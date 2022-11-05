<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All the API calles handling from Modules
| Try to create or update api endponts from modules
| Admin, Seller, Buyer, Customer
|
*/

Route::get('/unauthenticated', function () {
    return redirect('/');
});
