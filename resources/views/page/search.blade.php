@extends('page.layout.index')
@section('content')
<div class="feed">
    <h1> List user with keyword </h1>
  <!-- post starts --> 
  @foreach($userSearch as $key => $item)
     <!-- normal post start -->

  <div class="post">
        <div class="post__top">
        <img class="user__avatar post__avatar" src="
			<?php
                echo $item['userAvatar'];
            ?>" alt="" />
            <div class="post__topInfo">
        <h3> <?php
                echo $item['userName'];
            ?> </h3>
        <p>Join {{date('d/m/Y', $item['dateCreate']/1000);}}</p>
      </div>
    </div>
  </div>
  <!-- normal post end -->
   @endforeach
  <!-- post ends -->
  <!-- feed ends -->
  @endsection