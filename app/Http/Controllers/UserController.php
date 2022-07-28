<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function ajaxUser($id)
    {
        return User::find($id);
    }
}
