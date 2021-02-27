<?php

namespace App\Http\Controllers\API;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\ForgotRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Mail;
use SplSubject;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Hash;

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

   public function reset(ResetRequest $request)
   {

    $token = $request->input('token');

    if(!$passwordReset = DB::table('password_resets')->where('token' ,$token)->first()){

        return response([

           'message' => 'Tnvalid token'
        ], status:400);

    }
        //** @var User $user */
      if (!$user = User::where('email' , $passwordReset->email)->first()){

        return response([

            'message' => 'User doesn\'t exist!'
        ],status:404);

      }

      $user->password = Hash::make($request->input('password'));
      $user->save();

      return  response([

        'message' => 'success'
      ]);
   }


}
