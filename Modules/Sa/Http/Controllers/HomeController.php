<?php

namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sa\Helper\Common;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class HomeController extends BaseController
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('sa::index');
    }



    public function getLogout()
    {
        Auth::guard('web_sa')->logout();
        return redirect(route('sa.login'));
    }


}
