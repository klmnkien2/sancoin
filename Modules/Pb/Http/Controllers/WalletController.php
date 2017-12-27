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
use Modules\Pb\Services\WalletService;
use Modules\Pb\Services\EtherscanService;
use Modules\Pb\Services\BitcoinService;

class WalletController extends BaseController
{

    protected $walletService;
    protected $etherscanService;
    protected $bitcoinService;

    public function __construct(WalletService $walletService, EtherscanService $etherscanService, BitcoinService $bitcoinService)
    {
        $this->walletService = $walletService;
        $this->etherscanService = $etherscanService;
        $this->bitcoinService = $bitcoinService;
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
        $walletAddress = '0x0';
        $avaiableAmount = 0;
        $transactionHistory = [];

        $wallet = $this->walletService->getEthWallet(Auth::id());
        //dd($wallet);

        if (!empty($wallet)) {
            $walletAddress = '0x' . $wallet->address;
            $res = $this->etherscanService->getBalance($walletAddress);
            if (!empty($res['result'])) {
                $avaiableAmount = $res['result'];
            }

            //get transaction
            $res = $this->etherscanService->getTransactions($walletAddress, 1);
            if (!empty($res['result'])) {
                $transactionHistory = $res['result'];
            }
            //dd($transactionHistory);
        }

        return view('pb::wallet.eth', compact('messages', 'walletAddress', 'avaiableAmount', 'transactionHistory'));
    }

    public function btc(Request $request)
    {
        $messages = Common::getMessage($request);
        $walletAddress = '0x0';
        $avaiableAmount = 0;
        $transactionHistory = [];

        $wallet = $this->walletService->getEthWallet(Auth::id());
        //dd($wallet);

        if (!empty($wallet)) {
            $walletAddress = '0x' . $wallet->address;
            $res = $this->etherscanService->getBalance($walletAddress);
            if (!empty($res['result'])) {
                $avaiableAmount = $res['result'];
            }

            //get transaction
            $res = $this->etherscanService->getTransactions($walletAddress, 1);
            if (!empty($res['result'])) {
                $transactionHistory = $res['result'];
            }
            //dd($transactionHistory);
        }

        return view('pb::wallet.btc', compact('messages', 'walletAddress', 'avaiableAmount', 'transactionHistory'));
    }
}
