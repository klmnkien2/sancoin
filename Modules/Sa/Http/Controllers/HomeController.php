<?php

namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sa\Helper\Util;
use Modules\Sa\Services\UserService;
use Modules\Sa\Services\TransactionService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\User;
use Models\Transaction;
use Models\BtcWallet;
use Models\EthWallet;
use Models\VndWallet;

class HomeController extends BaseController
{
    use AuthenticatesUsers;
    protected $userService;
    protected $transService;

    public function __construct(UserService $userService, TransactionService $transService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->transService = $transService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $members = User::count();
        $totalBtc = BtcWallet::sum('balance');
        $totalEth = EthWallet::sum('balance');
        $totalVnd = VndWallet::sum('amount');
        return view('sa::index')->with(compact('members', 'totalBtc', 'totalEth', 'totalVnd'));
    }

    public  function systemFee(Request $request)
    {
        $searchDate =  $request->has('search_date') ? $request->get('search_date') : '';
        $dayAfter = (new \DateTime($searchDate))->modify('+1 day')->format('Y-m-d');
        list($fee_eth, $fee_btc) = $this->transService->getReport($searchDate, $dayAfter);
        $request->flash();
//        $fee_btc = 0;
//        $fee_eth = 0;
        return view('sa::home.system_fee')->with(compact('fee_btc', 'fee_eth'));
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
        list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $condition);

        $request->flash();
        return view('sa::home.user_list')->with(compact('listUsers', 'paginationText', 'paginationHtml', 'condition', 'per', 'page', 'total'));
    }

    public function transList(Request $request)
    {
        $condition = [
            'username' => $request->has('username') ? $request->get('username') : '',
            'status' => $request->has('status') ? $request->get('status') : '',
            'from_date' => $request->has('from_date') ? $request->get('from_date') : '',
            'to_date' => $request->has('to_date') ? $request->get('to_date') : '',
            'per' => $request->has('per') && is_numeric($request->get('per')) ? $request->get('per') : QUANTITY_PER_PAGE,
            'page' => $request->has('page') && is_numeric($request->get('page')) ? $request->get('page') : 1,
            'sort' => $request->has('sort') && is_numeric($request->get('sort')) ? $request->get('sort') : 1
        ];
        list($listData, $total, $page, $per) = $this->transService->getList($condition);
        list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $condition);

        $request->flash();
        return view('sa::home.trans_list')->with(compact('listData', 'paginationText', 'paginationHtml', 'condition', 'per', 'page', 'total'));
    }

    public function userDelete(Request $request)
    {
        $response = $this->userService->deleteMulti($request->all());

        try {
            if ($response['status'] && $response['count'] > 0) {
                $condition = $request->get('condition');
                parse_str($condition, $condition);
                $condition = [
                    'username' => !empty($condition['username']) ? $condition['username'] : '',
                    'email' => !empty($condition['email']) ? $condition['email'] : '',
                    'per' => !empty($condition['per']) ? $condition['per'] : QUANTITY_PER_PAGE,
                    'page' => !empty($condition['page']) ? $condition['page'] : 1,
                    'sort' => !empty($condition['sort']) ? $condition['sort'] : 1,
                ];
                list ($listUsers, $total, $page, $per) = $this->userService->getUserList($condition);
                //return response()->json($listUsers);
                list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $condition, route('admin.user_list'));
                //return response()->json($listUsers);
                $response['dataHtml'] = view('sa::home.user_list_datatable', compact('listUsers', 'page', 'per'))->render();
                $response['paginationText'] = $paginationText;
                $response['paginationHtml'] = $paginationHtml;
            }
        } catch (\Exception $ex) {
            //should Log
        }

        return response()->json($response);
    }

    public function userVerify(Request $request)
    {
        $response = $this->userService->verifyMulti($request->all());

        try {
            if ($response['status'] && $response['count'] > 0) {
                $condition = $request->get('condition');
                parse_str($condition, $condition);
                $condition = [
                    'username' => !empty($condition['username']) ? $condition['username'] : '',
                    'email' => !empty($condition['email']) ? $condition['email'] : '',
                    'per' => !empty($condition['per']) ? $condition['per'] : QUANTITY_PER_PAGE,
                    'page' => !empty($condition['page']) ? $condition['page'] : 1,
                    'sort' => !empty($condition['sort']) ? $condition['sort'] : 1,
                ];
                list ($listUsers, $total, $page, $per) = $this->userService->getUserList($condition);
                list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $condition, route('admin.user_list'));
                $response['dataHtml'] = view('sa::home.user_list_datatable', compact('listUsers', 'page', 'per'))->render();
                $response['paginationText'] = $paginationText;
                $response['paginationHtml'] = $paginationHtml;
            }
        } catch (\Exception $ex) {
            //should Log
            return response()->json($ex);
        }

        return response()->json($response);
    }

    public function transApprove(Request $request)
    {
        $response = $this->transService->approve($request->all());
        try {
            if ($response['status'] && $response['count'] > 0) {
                $condition = $request->get('condition');
                parse_str($condition, $condition);
                $condition = [
                    'username' => !empty($condition['username']) ? $condition['username'] : '',
                    'status' => !empty($condition['status']) ? $condition['status'] : '',
                    'from_date' => !empty($condition['from_date']) ? $condition['from_date'] : '',
                    'to_date' => !empty($condition['to_date']) ? $condition['to_date'] : '',
                    'per' => !empty($condition['per']) ? $condition['per'] : QUANTITY_PER_PAGE,
                    'page' => !empty($condition['page']) ? $condition['page'] : 1,
                    'sort' => !empty($condition['sort']) ? $condition['sort'] : 1,
                ];
                list ($listData, $total, $page, $per) = $this->transService->getList($condition);
                list($paginationText, $paginationHtml) = Util::render_pagination($total, $per, $page, $condition, route('admin.trans_list'));

                $response['dataHtml'] = view('sa::home.trans_list_datatable', compact('listData', 'page', 'per'))->render();
                $response['paginationText'] = $paginationText;
                $response['paginationHtml'] = $paginationHtml;
                //return response()->json($listData);
            }
        } catch (\Exception $ex) {
            //should Log
            return response()->json($ex);
        }

        return response()->json($response);
    }

    public function getLogout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login'));
    }


}
