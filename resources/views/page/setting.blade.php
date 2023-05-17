@extends('page.layout.index')
@section('content')
<section class="py-5 my-5">
	<div class="feed">
		<h1 class="mb-5">Account Settings</h1>
		<div class="bg-white shadow rounded-lg d-block d-sm-flex">
			<div class="profile-tab-nav border-right">
				<div class="p-4">
					<div class="img-circle text-center mb-3">
                        <label for="file-input">
							<img src="{{$user['userAvatar']}}" alt="Image" class="shadow">
                        </label>
					</div>
					<h4 class="text-center">{{$user['userName']}}</h4>
				</div>
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">	
						Account
					</a>
					<a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
						Password
					</a>
				</div>
			</div>
			<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
				<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
					<h3 class="mb-4">Account Settings</h3>
                    <form action="setting-acc" method="post" enctype="multipart/form-data">
                    @csrf
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Name</label>
								  	<input name ="name" type="text" class="form-control" value="{{$user['userName']}}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Email</label>
								  	<label class="form-control">{{$user['userEmail']}}</label>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
								  	<label>Bio</label>
									  <input name="bio" type="text" class="form-control" value="<?php echo ($user['bio'])?? "" ?>">
								</div>
							</div>
						</div>
                        <input id="file-input" type="file" name="photo"/>
						<div>
							<button class="btn btn-primary">Update</button>
						</div>
                    </form>
				</div>

				<div class="tab-pane fade show" id="password" role="tabpanel" aria-labelledby="password-tab">
					<h3 class="mb-4">Password Settings</h3>
                    <form action="change-pass" method="post" enctype="multipart/form-data">
                    @csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								  	<label>Old password</label>
								  	<input name ="oldpassword" type="password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>New Password</label>
								  	<input name ="newpassword" type="password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Confirm Password</label>
									<input name="confirmpassword" type="password" class="form-control">
								</div>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
						</div>
                    </form>
					@if(count($errors)>0)
                        <div class="alert alert-danger">
						<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					@foreach($errors->all() as $err) {{$err}}<br>
                    @endforeach
                        </div> 
                    @endif 
                    @if(session('noti')) 
                        <div class="alert alert-danger">
                            {{session('noti')}}
                        </div>
                    @endif 
				</div>

			</div>
		</div>
	</div>
</section>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection