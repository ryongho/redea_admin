<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $result = mail($request->email, $request->title, $request->content, '', '-redea.help@gmail.com');

        dd($result);
    }

    



}
