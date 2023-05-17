<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;
use Carbon\Carbon;
use Session;
use Cookie;

class AuthController extends Controller
{
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    function autoLogin(){
        if(Cookie::get('loginkey')){
            $signInResult = $this->auth->signInWithRefreshToken(Cookie::get('loginkey'));
            Session::put('login_session','key');
            return redirect('/');
        }
        else{
            return redirect('login');
        }
    }
    function getLogin()
    {
    	return view('auth.login');
    }
    function  postLogin(Request $request){
        $this ->validate($request,[
            'email'=>'required',
            'password'=>'required',
        ],[
            'email.required'=>'You do not enter email',
            'password.required'=>'You do not enter password',
        ]);
        try {
            if($signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password)){
                Session::put('login_session','key');
                Cookie::queue('loginkey', $signInResult->refreshToken(),44640);
                Cookie::queue('iduserlogin', $signInResult->firebaseUserId(),44640);
                return redirect('/');
            }
        } catch (\Exception $exception) {
            if($exception->getMessage()=="EMAIL_NOT_FOUND"){
                return redirect('login')->with('noti','Your email invalid');; 
            }
            else if($exception->getMessage()=="INVALID_PASSWORD"){
                return redirect('login')->with('noti','Your password invalid');; 
            }
            else{
                echo $exception->getMessage();
            }
        } 
    }
    function getSignup(){
    	return view('auth.signup');
    }
    function postSignup(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required|min:6|max:30',
            'confirm-password'=>'required|same:password'
        ],[
            'email.required'=>'You do not enter email',
            'password.required'=>'You do not enter password',
            'password.min'=>'Password at least 6 characters',
            'password.max'=>'Password maximum 30 characters',
            'confirm-password.required'=>'You do not confirm your password',
            'confirm-password.same'=>'Confirm password not same password'
        ]);
        $email = $request->email;
        $pos = strpos($email, '@');
        $tail = substr($email, $pos);
        $name = str_replace($tail,"",$email);
        $requestCreateUser = \Kreait\Firebase\Request\CreateUser::new()
        ->withUnverifiedEmail($request->email)
        ->withClearTextPassword($request->password);
        try {
            if($createdUser = $this->auth->createUser($requestCreateUser)){
                $user = $this->auth->getUserByEmail($request->email);
                $database = app('firebase.database');
                $mytime = ceil(microtime(true) * 1000);
                $postKey = $user->uid;
                $postData =
                [
                    'userName' => $name,
                    'userId' => $user->uid,
                    'userEmail' => $request->email,
                    'userAvatar'=> "https://firebasestorage.googleapis.com/v0/b/blog-93a0b.appspot.com/o/t%E1%BA%A3i%20xu%E1%BB%91ng.png?alt=media&token=55aaf24a-0285-40ee-8587-0f53a310f499",
                    'dateUpdate' => $mytime,
                    'dateCreate'=> $mytime,
                ];
                
                $postRef = $database->getReference('User')->getChild($postKey)->set($postData);
                return redirect('login');
            }
        } catch (\Exception $exception) {
            if($exception->getMessage()=="The email address is already in use by another account.aaaa"){
                return redirect('signup')->with('noti','The email address is already in use by another account');  
            }
            else{
                return redirect('signup')->with('noti',$exception->getMessage());
            }
        }
    }
}
