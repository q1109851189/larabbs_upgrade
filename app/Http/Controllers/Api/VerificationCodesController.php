<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        if (app()->environment('production')) {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone, [
                    'template' => 'SMS_163725878',
                    'data' => [
                        'code' => $code,
                        'sign_name' => '薇妮艾拉',
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
        } else {
            $code = '123456';
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}