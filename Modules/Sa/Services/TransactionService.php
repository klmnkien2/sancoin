<?php
namespace Modules\Sa\Services;

use Models\Transaction;
use Auth;
use DB;
use App\User;

class TransactionService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getList($condition)
    {
        //dd($condition);
        $page = $condition['page'];
        $per = $condition['per'];

        $whereArray = [];
        foreach ($condition as $keyCondition => $valueCondition) {
            if (!empty($valueCondition)) {
                if ($keyCondition == 'username') {
                    $whereArray[] = ['key' => $keyCondition, 'operator' => 'like', 'value' => "%" . $valueCondition . "%"];
                }
                if ($keyCondition == 'status') {
                    $whereArray[] = ['key' => $keyCondition, 'operator' => '=', 'value' => $valueCondition];
                }
                if ($keyCondition == 'from_date') {
                    $whereArray[] = ['key' => 'created_at', 'operator' => '>=', 'value' => strtotime($valueCondition)];
                }
                if ($keyCondition == 'to_date') {
//                    dd(strtotime("$valueCondition"), strtotime("$valueCondition +1 day"));
                    $dayAfter = (new \DateTime($valueCondition))->modify('+1 day')->format('Y-m-d');
                    $whereArray[] = ['key' => 'created_at', 'operator' => '<', 'value' => $dayAfter];
                }
            }
        }
//dd($whereArray);
        $primaryQuery = Transaction::query();
        foreach ($whereArray as $where) {
            $primaryQuery->where($where['key'], $where['operator'], $where['value']);
        }
        //dd($primaryQuery);
        $total = $primaryQuery->count();
        //$primaryQuery->orderBy($sortConfig[$request['sort']][0], $sortConfig[$request['sort']][1]);
        $listUsers = $primaryQuery->skip(($page - 1) * $per)->take($per)->get();

        return [$listUsers, $total, $page, $per];
    }

    public function approve($request)
    {
        $response = ['status' => false, 'errorList' => []];
        if (!empty($request['id'])) {
            $listID = $request['id'];
            $dataList = Transaction::select('id', 'status', 'type')->whereIn('id', $listID)->get();
            $checkInvalid = $this->checkApproveInvalid($dataList);

            if (!empty($checkInvalid)) {
                $response['errorList'] = $checkInvalid;
                $listID = array_diff($listID, array_keys($checkInvalid));
            }

            if (!empty($listID)) {
                DB::beginTransaction();
                try {
                    foreach ($dataList as $transaction) {
                        if ($transaction->type == 'order') {
                            continue;
                        }
                        $transaction->update(['status' => 'done']);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $response['status'] = false;
                    return $response;
                }
            }
            $response['count'] = count($listID);
            $response['status'] = true;
        }
        return $response;
    }

    public function checkApproveInvalid($dataList)
    {
        $invalidData = [];
        foreach ($dataList as $value) {
            if ($value->type == 'order') {
                $invalidData[$value->id] = $value->status;
            }
        }
        return [];
    }
}
