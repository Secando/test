<?php

namespace App\Services;

class ReCaptchaService
{
    public function check($request)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => '6LePJgkqAAAAAHf3VlJ6TDn6KHAXPcfGNDwFIyXf',
            'response' => $request['g-recaptcha-response']
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, false);

        return $result;
    }

}