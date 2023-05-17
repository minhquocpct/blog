<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Cookie;
use Carbon\Carbon;
use File;
use Kreait\Firebase\Database;
use Kreait\Firebase\Auth;

class PageController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    function dashboard(){
        $post = $this->database->getReference('Post')->getValue();
        $comment = $this->database->getReference('Comment')->getValue();
        return view('page.homepage',compact('post'),compact('comment'));
    }
    function logout(){
    	Session::forget('login_session');
        Session::flush();
        Cookie::queue(Cookie::forget('loginkey'));
        Cookie::queue(Cookie::forget('iduserlogin'));
        return redirect('login');
    }

    function newPost(Request $request){
        $this->validate($request,[
            'post'=>'required',
        ],[
            'post.required'=>'You do not enter content for this post',
        ]);
        $newPostKey =  $this->database->getReference('Post')->push()->getKey();
        $mytime = ceil(microtime(true) * 1000);
        if($request->hasFile('photo')){
            $image = $request->file('photo');  
            $firebase_storage_path = 'Post/';  
            $localfolder = 'uploads'.'/';  
            $extension = $image->getClientOriginalExtension();  
            $file= $newPostKey. '.' . $extension;  
            $image->move($localfolder, $file); 
            $uploadedfile = fopen($localfolder.$file, 'r');   
            $storage = app('firebase.storage');
            $bucket = $storage->getBucket();;
            $bucket->upload($uploadedfile,
                [   
                    'metadata' => [
                        'contentType' => 'image/jpeg'
                    ],
                    'name' => $firebase_storage_path . $newPostKey
            
                ]);
            $imageUrl = "https://firebasestorage.googleapis.com/v0/b/blog-93a0b.appspot.com/o/Post%2F".$newPostKey."?alt=media";
            File::deleteDirectory($localfolder);
    	}
    	else
    	{
            $imageUrl="";
    	}
        $postData =
        [
            'typePost' => "Post",
            'title' => $request->post,
            'photo'=> $imageUrl,
            'idUser' => Cookie::get('iduserlogin'),
            'idPost' => $newPostKey,
            'dateUpdate' => $mytime,
            'dateCreate'=> $mytime,
        ];
        $updates = [
            'Post/'.$newPostKey => $postData,
        ];
        $this->database->getReference()->update($updates);
        return redirect(url()->previous());
    }
    function share($id){
        $newPostKey =  $this->database->getReference('Post')->push()->getKey();
        $mytime = ceil(microtime(true) * 1000);
        $postData =
        [
            'typePost' => "Share",
            'idUser' => Cookie::get('iduserlogin'),
            'idShare' => $id,
            'idPost' => $newPostKey,
            'dateUpdate' => $mytime,
            'dateCreate'=> $mytime,
        ];
        $updates = [
            'Post/'.$newPostKey => $postData,
        ];
        $this->database->getReference()->update($updates);
        return redirect(url()->previous());
    }
    function comment(Request $request, $id){
        $newPostKey =  $this->database->getReference('Comment'.$id)->push()->getKey();
        $mytime = ceil(microtime(true) * 1000);
        $postData =
        [
            'idUser' => Cookie::get('iduserlogin'),
            'idPost' => $id,
            'idComment' => $newPostKey,
            'comment' => $request->comment,
            'dateUpdate' => $mytime,
            'dateCreate'=> $mytime,
        ];
        $updates = [
            'Comment/'.$id.'/'.$newPostKey=>$postData,
        ];
        $this->database->getReference()->update($updates);
        return redirect(url()->previous());
    }

    function setting(){
        $user = $this->database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();  
        return view('page.setting',compact('user') );
    }
    function settingAcc(Request $request){ 
        $user = $this->database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();
        $mytime = ceil(microtime(true) * 1000);
        if($request->hasFile('photo')){
            $image = $request->file('photo');  
            $firebase_storage_path = 'User/';  
            $localfolder = 'uploads'.'/';  
            $extension = $image->getClientOriginalExtension();  
            $file= Cookie::get('iduserlogin'). '.' . $extension;  
            $image->move($localfolder, $file); 
            $uploadedfile = fopen($localfolder.$file, 'r');   
            $storage = app('firebase.storage');
            $bucket = $storage->getBucket();;
            $bucket->upload($uploadedfile,
                [   
                    'metadata' => [
                        'contentType' => 'image/jpeg'
                    ],
                    'name' => $firebase_storage_path . Cookie::get('iduserlogin')
            
                ]);
            $imageUrl = "https://firebasestorage.googleapis.com/v0/b/blog-93a0b.appspot.com/o/User%2F".Cookie::get('iduserlogin')."?alt=media";
            File::deleteDirectory($localfolder);
    	}
    	else
    	{
            $imageUrl=$user['userAvatar'];
    	}
        $postData =
        [
            'userName' => $request->name,
            'userId' => Cookie::get('iduserlogin'),
            'userEmail' => $user['userEmail'],
            'userAvatar'=> $imageUrl,
            'dateUpdate' => $mytime,
            'dateCreate'=> $user['dateCreate'],
            'bio'=> $request->bio,
        ];
        $updates = [
            'User/'.Cookie::get('iduserlogin')=>$postData,
        ];
        $this->database->getReference()->update($updates);
        return redirect(url()->previous());
    }
    function changePass(Request $request){
        $this->validate($request,[
            'oldpassword'=>'required',
            'newpassword'=>'required|min:6|max:30',
            'confirmpassword'=>'required|same:newpassword'
        ],[
            'oldpassword.required'=>'You do not enter old password',
            'newpassword.required'=>'You do not enter new password',
            'newpassword.min'=>'New password at least 6 characters',
            'newpassword.max'=>'New password maximum 30 characters',
            'confirmpassword.required'=>'You do not confirm your new password',
            'confirmpassword.same'=>'Confirm password not same new password'
        ]);
        $auth = app('firebase.auth');
        $user = $auth->getUser(Cookie::get('iduserlogin'));
        try {
        if($signInResult = $auth->signInWithEmailAndPassword($user->email, $request->oldpassword)){
            $updatedUser = $auth->changeUserPassword($user->uid, $request->newpassword);
            return redirect('setting#password')->with('noti','Change password success');
        }
         } catch (\Exception $exception) {
            if($exception->getMessage()=="INVALID_PASSWORD"){
                return redirect('setting#password')->with('noti','Old password invalid');; 
            }
            else{
                echo $exception->getMessage();
            }
        } 
    }
    function search(Request $request){
        $userSearch = $this->database->getReference('User')->orderByChild('userName')->startAt($request->search)->endAt($request->search."\u{F8FF}")->getValue();
        return view('page.search',compact('userSearch'));
    }
}
