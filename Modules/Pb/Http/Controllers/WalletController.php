<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use App\Helper\Image;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\ProfileValidator;
use App\User;
use Models\Attachment;
use Models\Profile;
use Auth, DB;
use Illuminate\Support\Facades\Storage;

class WalletController extends BaseController
{

    public function __construct()
    {
        //TODO
    }

    public function profile(Request $request)
    {
        $messages = Common::getMessage($request);
        $profile = Profile::where('user_id', Auth::id())->first();
        $attachmentUrls = [];
        if ($profile) {
            $attachments = Attachment::where(['ref_id' => $profile->id, 'type' => 'profiles'])->get();
            foreach ($attachments as $attachment) {
                $attachmentUrls[] = Storage::url($attachment->url);
            }
        }
        return view('pb::wallet.profile', compact('messages', 'profile', 'attachmentUrls'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $profileValidator = new ProfileValidator();
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $validator = $this->checkValidator($data, $profileValidator->validate());
            if (! $validator->fails()) {
                do {
                    $photoArr = isset($data['images']) ? $data['images'] : [];
                    //dd($photoArr);
                    // OTHER LOGIC VALIDATE BUSINESS
                    if (count($photoArr) > 3) {
                        $error = [
                            'common' => [trans('messages.message.profile_upload_over_3')]
                        ];
                        break;
                    }
                    // INSERT OR UPDATE RECORD
                    DB::beginTransaction();
                    unset($data['images']);
                    $profile = Profile::where('user_id', Auth::id())->first();
                    if(!$profile) {
                        $profile = Profile::create($data);
                    } else {
                        $profile->fullname = $data['fullname'];
                        $profile->id_number = $data['id_number'];
                        $profile->id_created_at = $data['id_created_at'];
                        $profile->id_created_by = $data['id_created_by'];
                        $profile->address = $data['address'];
                        $profile->save();
                    }
                    if ($profile) {
                        if (count($photoArr) > 0) {
                            Attachment::where('ref_id', $profile->id)->delete();
                        }
                        foreach ($photoArr as $photo) {
                            $attachment = [];
                            $attachment['ref_id'] = $profile->id;
                            $attachment['name'] = $photo->getClientOriginalName();
                            $attachment['url'] = $photo->storePublicly('public/images');
                            $attachment['type'] = 'profiles';
                            Attachment::create($attachment);
                            //dd($res);
                        }
                    }
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
            //dd($error);
            Common::setMessage($request, 'error', $error);
        } else {
            Common::setMessage($request, 'success', ['common' => [trans('messages.message.up_profile_successfully')]]);
        }

        return redirect(route('pb.getProfile'));
    }

    public function eth(Request $request)
    {
        $messages = Common::getMessage($request);

        return view('pb::wallet.eth', compact('messages'));
    }
}
