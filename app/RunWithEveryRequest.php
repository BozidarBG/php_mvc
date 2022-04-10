<?php

namespace App\app;

use App\core\LoginWithCookie;

class RunWithEveryRequest
{

    public function __construct()
    {
        LoginWithCookie::LoginWithCookie();
    }


}