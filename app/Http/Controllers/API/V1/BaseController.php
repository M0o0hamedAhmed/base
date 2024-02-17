<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BaseController extends Controller
{
    public function __construct()
    {
        if(request()->lang == 'ar'){App::setlocale('ar');}else{App::setlocale('en');}

    }
}
