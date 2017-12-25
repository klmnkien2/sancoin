<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use App\Helper\Image;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\ProfileValidator;
use App\User;
use Auth, DB;
use Modules\Pb\Services\UserService;

class WalletController extends BaseController
{

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function profile()
    {
        return view('pb::wallet.profile');
    }

    public function updateProfile(Request $request)
    {
        try {
            $profileValidator = new ProfileValidator();
            $data = $request->all();
            $validator = $this->checkValidator($data, $profileValidator->validate());
            if (! $validator->fails()) {
                do {
                    // OTHER LOGIC VALIDATE BUSINESS
//                     if (false) {
//                         break;
//                     }
                    // INSERT OR UPDATE RECORD
                    DB::beginTransaction();
                    $building_rs = $this->buildingService->createBuilding($buildingData);
                    $photoArr = isset($data['images']) ? $data['images'] : [];
                    foreach ($photoArr as $photo) {

                    }
                    $data_file = Image::saveMany("PF", Auth::id(), $photoArr);
                    if ($data_file[$this->textStatus]) {
                        $building_rs['file_path'] = $data_file['data'][0];
                    } else {
                        DB::rollback();
                        $message_error = [trans('labels_c.C_M007')];
                        Common::setMessage($request, MESSAGE_STATUS_ERROR, $message_error);
                        $is_error = true;
                    }
                    $building_rs->save();
                    DB::commit();
                    $success = true;
                    break;
                } while (true);
            } else {
                $error = $validator->getMessageBag()->getMessages();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            DB::rollback();
            dd($e);
            $error = [
                'common' => [trans('messages.message.reg_common_fail')]
            ];
        }

        if (empty($success)) {
            Common::setMessage($request, 'error', $error);
        } else {
            Common::setMessage($request, 'success', [trans('messages.message.up_profile_successfully')]);
        }

        return redirect(route('pb.getProfile'));
    }
}
