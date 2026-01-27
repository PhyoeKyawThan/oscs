<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request){
        if($request->method() == "POST"){

        }else{
            return view("customer.login.index");
        }
    }

    public function signup(Request $request){
        if($request->method() == "POST"){

        }else{
            return view("customer.signup.index");
        }
    }
}
