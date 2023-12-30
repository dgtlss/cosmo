<?php

namespace Dgtlss\Cosmo\Models;
use Illuminate\Database\Eloquent\Model;

class CosmoError extends Model
{

    public static function createError($e)
    {
        // We need to get the error message from the exception
        $error = $e->getMessage();

        // We need to get the error code from the exception
        $code = $e->getCode();

        // We need to get the file name from the exception
        $file = $e->getFile();

        // We need to get the line number from the exception
        $line = $e->getLine();

        // We need to get the trace from the exception
        $trace = $e->getTraceAsString();

        // We need to get the date and time from the exception
        $date = date('Y-m-d');
        $time = date('H:i:s');

        if(auth()->check()){
            // We need to get the user id from the user
            $user_id = auth()->user()?->id;

            // We need to get the user email from the user
            $user_email = auth()->user()?->email;

            // We need to get the user name from the user
            $user_name = auth()->user()?->name;
        }

        // We need to get the user ip address from the user
        $user_ip = request()->ip();

        // We need to get the user agent from the user
        $user_agent = request()->userAgent();

        // We need to get the user url from the user
        $user_url = request()->fullUrl();

        // We need to get the user method from the user
        $user_method = request()->method();

        // We need to get the user headers from the user
        $user_headers = json_encode(request()->header());

        // We need to get the user cookies from the user
        $user_cookies = json_encode(request()->cookie());

        // We need to get the user exception from the user
        $user_exception = json_encode($e);

        // We need to get the user trace from the user
        $user_trace = json_encode($e->getTrace());

    }

    public static function latestError()
    {
        return CosmoError::latest()->first();
    }
}