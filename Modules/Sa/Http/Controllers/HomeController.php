<?php

namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sa\Helper\Util;
use Modules\Sa\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class HomeController extends BaseController
{
    use AuthenticatesUsers;
    protected $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

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
        $condition = [
            'username' => $request->has('username') ? $request->get('username') : '',
            'email' => $request->has('email') ? $request->get('email') : '',
            'per' => $request->has('per') && is_numeric($request->get('per')) ? $request->get('per') : QUANTITY_PER_PAGE,
            'page' => $request->has('page') && is_numeric($request->get('page')) ? $request->get('page') : 1,
            'sort' => $request->has('sort') && is_numeric($request->get('sort')) ? $request->get('sort') : 1
        ];
        list($listUsers, $total, $page, $per) = $this->userService->getUserList($condition);
        list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $request->all());

        return view('sa::home.user_list')->with(compact('listUsers', 'paginationText', 'paginationHtml', 'condition', 'per', 'page', 'total'));
    }

    public function userDelete(Request $request)
    {
        $response = $this->userService->deleteMulti($request->all());
        if ($request->has('reload_list') && $response['status'] && $response['count'] > 0) {
            list ($listUsers, $total, $page, $per) = $this->userService->getUserList($request->all()['condition']);
            list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $request->all()['condition'], route('admin.user_list'));

            $response['dataHtml'] = view('sa::home.user_list_datatable', compact('listUsers', 'page', 'per'))->render();
            $response['paginationText'] = $paginationText;
            $response['paginationHtml'] = $paginationHtml;
        }
        return response()->json($response);
    }

    public function getLogout()
    {
        Auth::guard('web_sa')->logout();
        return redirect(route('sa.login'));
    }


}
