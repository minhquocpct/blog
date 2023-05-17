@extends('auth.layout.index') 
@section('content') <div class="right-content">
  <div class="card">
    <form action="{{url('login')}}" method="POST">
      @csrf
      <div class="input-container">
        <input type="text" placeholder="Email" name="email">
      </div>
      <div class="input-container">
        <input type="password" placeholder="Password" name="password">
      </div>
      <div class="login-btn-container">
        <button class="login-btn">Log In</button>
      </div>
    </form>
    <div class="login-btn-container">
      @if(count($errors)>0)
      <div class="alert alert-danger">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        @foreach($errors->all() as $err)
        {{$err}}
        <br>
         @endforeach
      </div> 
      @endif 
      @if(session('noti')) 
      <div class="alert alert-danger">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        {{session('noti')}}
      </div>
       @endif 
    </div>
    <div class="text-small">
      <small>Don't having account?</small>
    </div>
    <div class="" style="text-align: center;">
      <a class="crt-new-ac" href="{{url('signup')}}">Create New Account</a>
    </div>
  </div>
  <div class="crt-page">
    <a>Join us</a>
    <span>for a celebrity, band or business.</span>
  </div>
</div>
@endsection