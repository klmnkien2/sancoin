<?php
namespace Modules\Sa\Services;

use App\Helper\Uploader;
use Auth;
use DB;
use App\User;

class UserService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserList($condition)
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
                if ($keyCondition == 'email') {
                    $whereArray[] = ['key' => $keyCondition, 'operator' => 'like', 'value' => "%" . $valueCondition . "%"];
                }
            }
        }

        $primaryQuery = User::query();
        foreach ($whereArray as $where) {
            $primaryQuery->where($where['key'], $where['operator'], $where['value']);
        }
        //dd($primaryQuery);
        $total = $primaryQuery->count();
        //$primaryQuery->orderBy($sortConfig[$request['sort']][0], $sortConfig[$request['sort']][1]);
        $listUsers = $primaryQuery->skip(($page - 1) * $per)->take($per)->get();

        return [$listUsers, $total, $page, $per];
    }

    public function deleteMulti($request)
    {
        $response = ['status' => false, 'errorList' => []];
        if (!empty($request['id'])) {
            $listID = $request['id'];
            $dataList = User::select('id', 'status')->whereIn('id', $listID)->get();
            $checkInvalid = $this->checkDeleteInvalid($dataList);

            if (!empty($checkInvalid)) {
                $response['errorList'] = $checkInvalid;
                $listID = array_diff($listID, array_keys($checkInvalid));
            }

            if (!empty($listID)) {
                DB::beginTransaction();
                try {
                    User::whereIn('id', $listID)->delete();
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

    public function checkDeleteInvalid($dataList)
    {
        return [];
    }

    public function requestPlaces($request)
    {
        $response = [$this->textStatus => false, $this->textErrorList => []];
        if (!empty($request['type']) && !empty($request['id'])) {
            $listID = $request['id'];
            $dataList = Place::select('id', 'code', $this->textStatus)->whereIn('id', $listID)->get();
            list ($checkInvalid, $response['errorMessage']) = $this->checkDataInvalid($request['type'], $dataList);

            if (!empty($checkInvalid)) {
                $response[$this->textErrorList] = $checkInvalid;
                $listID = array_diff($listID, array_keys($checkInvalid));
            }
            if (!empty($listID)) {
                DB::beginTransaction();
                try {
                    $this->performRequest($request['type'], $listID);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $response[$this->textStatus] = false;
                    return $response;
                }
            }
            $response['count'] = count($listID);
            $response[$this->textStatus] = true;
        }
        return $response;
    }

    private function performRequest($type, $listID)
    {
        if (in_array($type, [self::REQUEST_PUBLISH, self::REQUEST_STOP_PUBLISHING])) {
            switch ($type) {
                case self::REQUEST_PUBLISH:
                    $status = config('config.place.status.published');
                    break;
                case self::REQUEST_STOP_PUBLISHING:
                    $status = config('config.place.status.not_published');
                    break;
                default:
                    $status = config('config.place.status.not_published');
                    break;
            }
            Place::whereIn('id', $listID)->update([$this->textStatus => $status]);
        }
    }

    private function checkDataInvalid($type, $dataList)
    {
        $validStatus = [];
        switch ($type) {
            case REQUEST_DELETE:
                $validStatus = config('config.place.status.not_published');
                break;
            case self::REQUEST_PUBLISH:
                $validStatus = config('config.place.status.not_published');
                break;
            case self::REQUEST_STOP_PUBLISHING:
                $validStatus = config('config.place.status.published');
                break;
            default:
                break;
        }
        $invalidData = [];
        $errorMessage = null;
        foreach ($dataList as $value) {
            if ($value->status != $validStatus) {
                $invalidData[$value->id] = $value->code;
            }
        }

        if (count($dataList) == 1 && $validStatus == config('config.place.status.not_published') && $type == REQUEST_DELETE) {
            $errorMessage = trans('labels_sa.SA_CID0010_M003');
        }

        return [$invalidData, $errorMessage];
    }
}
