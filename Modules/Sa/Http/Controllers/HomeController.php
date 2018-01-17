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

    public function userList(Request $request)
    {
        list($listClientManager, $total, $page, $per) = $this->clientManagerService->getClientManagerList($request->all());
        list($paginationText, $paginationHtml) = render_pagination($total, $per, $page, $request->all());
        $messages = Common::getMessage($request);
        return view('sa::home.user_list')->with(compact('messages'));
    }

    public function getLogout()
    {
        Auth::guard('web_sa')->logout();
        return redirect(route('sa.login'));
    }


}
