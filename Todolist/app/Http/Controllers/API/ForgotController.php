<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ForgotRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Mail;
use SplSubject;
use App\Mail\MyTestMail;
class ForgotController extends Controller
{
    public function forgot(ForgotRequest $request)
    {
       $email = $request->input('email');
       if(User::where('email',$email)->doesntExist())
          return response(['massage' =>'User don\'t exists'], 404);
       try{
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if ($oldToken) {
            Mail::to($email)->send(new MyTestMail($oldToken->token));
        return response(['message'=>'check your email']);
        }
        else
        {
            $token = Str::random(10);
             DB::table('password_resets')->insert([
               'email' =>$email,
               'token' =>$token
           ]);
           Mail::to($email)->send(new MyTestMail($token));
           return response(['message'=>'check your email']);
        }

     }catch (\Exception $exception){

        return response(['message' => $exception->getMessage()], 400);
     }


    }
}
