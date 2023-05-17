@extends('page.layout.index')
@section('content')
<!-- feed starts -->
<div class="feed">
  <!-- message sender starts -->
  <div class="messageSender">
    <div class="messageSender__top">
      <img class="user__avatar" src="
			<?php
                $database = app('firebase.database'); 
                $user = $database->getReference('User');
                $currentUser = $database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();  
                echo $currentUser['userAvatar'];
            ?>
            " alt="" />
      <form name="form" action="new-post" method="post" enctype="multipart/form-data"> @csrf <input class="messageSender__input" placeholder="@if(count($errors)>0)
@foreach($errors->all() as $err)
{{$err}}
@endforeach
@else What's on your mind?
@endif" type="text" name="post" />
        <input id="file-input" type="file" name="photo" />
      </form>
    </div>
    <div class="messageSender__bottom">
      <label for="file-input">
        <div class="messageSender__option">
          <span style="color: green" class="material-icons"> photo_library </span>
        </div>
      </label>
    </div>
  </div>
  <!-- message sender ends -->
  <!-- post starts --> 
  @foreach($post as $key => $item)
  @if($item['typePost']=='Share')
  <!-- share post starts -->
  <div class="post">
    <!-- user share starts -->
    <div class="post__top">
      <img class="user__avatar post__avatar" src="
					<?php
                    $userPost = $user->getChild($item['idUser'])->getValue();
                    echo $userPost['userAvatar'];
                ?>" alt="" />
      <div class="post__topInfo">
        <h3>
            <?php
                echo $userPost['userName'];
            ?> shared this post </h3>
        <p>{{date('d/m/Y', $item['dateUpdate']/1000);}}</p>
      </div>
    </div>
    <!-- user share ends -->
    <!-- shared user starts -->
    <div class="post__top" style="margin-left: 30px;">
      <img class="user__avatar post__avatar" src="
        <?php
            $postShare = $database->getReference('Post')->getChild($item['idShare'])->getValue();
            $userPost = $user->getChild($postShare['idUser'])->getValue();
            echo $userPost['userAvatar'];
        ?>" alt="" />
      <div class="post__topInfo">
        <h3> <?php
                    echo $userPost['userName'];
                ?> </h3>
        <p>{{date('d/m/Y', $postShare['dateUpdate']/1000);}}</p>
      </div>
    </div>
    <!-- shared post starts -->
    <div class="post__bottom" style="margin-left: 30px;">
      <p> <?php
                echo ($postShare['title'])?? ""; 
            ?> </p>
    </div>
    <div class="post__image">
      <img src="<?php echo ($postShare['photo'])?? ""; ?>" alt="" />
    </div>
    <!-- shared post ends -->
    <!-- like share button start -->
    <div class="post__options">
      <div class="post__option">
        <span class="material-icons"> thumb_up </span>
        <p><?php
            $like = $database->getReference('Like')->orderByKey()->equalTo($item['idPost'])->getValue();
            foreach($like as $l)
            {
              if($numlike=count($l)){
                echo $numlike;
              }
            }
        ?></p>
      </div>
      <div class="post__option">
        <span class="material-icons"> chat_bubble_outline </span>
        <p><?php
            $cmtNum = $database->getReference('Comment')->orderByKey()->equalTo($item['idPost'])->getValue();
            foreach($cmtNum as $cn)
            {
              if($cNum=count($cn)){
                echo $cNum;
              }
            }
        ?></p>
      </div>
      <div class="post__option">
        <a href="<?php echo url('share/'.$postShare['idPost']) ?>">
          <span class="material-icons"> near_me </span>
        </a>
        <p></p>
      </div>
    </div>
    <!--like share button ends  -->
    <!-- comment starts -->
    @foreach($comment as $keyCmt => $itemCmt)
    @foreach($itemCmt as $ic)
    @if($ic['idPost']==$item['idPost'])
    <div class="post__top">
      <img class="user__avatar post__avatar" src="
        <?php
            $userCmt = $user->getChild($ic['idUser'])->getValue();
            echo $userCmt['userAvatar'];
        ?>" alt="" />
      <div class="post__topInfo">
        <h3>
            <?php
                echo $userCmt['userName'];
            ?>
        </h3>
        <small style="margin-left: 2px;">{{$ic['comment']}}</small>
        <p style="margin-left: 2px;">{{date('d/m/Y', $ic['dateUpdate']/1000);}}</p>
      </div>
    </div> @endif @endforeach @endforeach
    <!-- comment ends -->
    <!-- input comment start -->
    <div class="messageSender__top">
      <img class="user__avatar" src="
            <?php
                $database = app('firebase.database'); 
                $user = $database->getReference('User');
                $currentUser = $database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();  
                echo $currentUser['userAvatar'];
            ?>
            " alt="" />
      <form name="form" action="comment/{{$item['idPost']}}" method="post" enctype="multipart/form-data" onsubmit="return validateForm('<?php echo $item['idPost']?>')">
        @csrf
        <input id="{{$item['idPost']}}" class="messageSender__input" placeholder="Write your comment" type="text" name="comment" />
      </form>
    </div>
    <!-- input comment ends -->
  </div>
  <!-- share post end --> 
  @else
  <!-- normal post start -->
  <div class="post">
    <div class="post__top">
      <img class="user__avatar post__avatar" src="
										<?php
                $userPost = $user->getChild($item['idUser'])->getValue();
                echo $userPost['userAvatar'];
            ?>" alt="" />
      <div class="post__topInfo">
        <h3> <?php
                echo $userPost['userName'];
            ?> </h3>
        <p>{{date('d/m/Y', $item['dateUpdate']/1000);}}</p>
      </div>
    </div>
    <div class="post__bottom">
      <p> <?php
        echo ($item['title'])?? ""; 
        ?> </p>
    </div>
    <div class="post__image">
      <img src="<?php echo ($item['photo'])?? ""; ?>" alt="" />
    </div>
    <div class="post__options">
      <div class="post__option">
        <span class="material-icons"> thumb_up </span>
        <p><?php
            $like = $database->getReference('Like')->orderByKey()->equalTo($item['idPost'])->getValue();
            foreach($like as $l)
            {
              if($numlike=count($l)){
                echo $numlike;
              }
            }
        ?></p>
      </div>
      <div class="post__option">
        <span class="material-icons"> chat_bubble_outline </span>
        <p><?php
            $cmtNum = $database->getReference('Comment')->orderByKey()->equalTo($item['idPost'])->getValue();
            foreach($cmtNum as $cn)
            {
              if($cNum=count($cn)){
                echo $cNum;
              }
            }
        ?></p>
      </div>
      <div class="post__option">
        <a href="<?php echo url('share/'.$item['idPost']) ?>">
          <span class="material-icons"> near_me </span>
        </a>
        <p></p>
      </div>
    </div>
    <!-- comment starts --> 
    @foreach($comment as $keyCmt => $itemCmt) 
    @foreach($itemCmt as $ic) 
    @if($ic['idPost']==$item['idPost']) 
    <div class="post__top">
      <img class="user__avatar post__avatar" src="
												<?php
                            $userCmt = $user->getChild($ic['idUser'])->getValue();
                            echo $userCmt['userAvatar'];
                        ?>" alt="" />
      <div class="post__topInfo">
        <h3> <?php
                            echo $userCmt['userName'];
                        ?> </h3>
        <small style="margin-left: 2px;">{{$ic['comment']}}</small>
        <p style="margin-left: 2px;">{{date('d/m/Y', $ic['dateUpdate']/1000);}}</p>
      </div>
    </div> @endif @endforeach @endforeach
    <!-- comment ends -->
    <!-- input comment start -->
    <div class="messageSender__top">
      <img class="user__avatar" src="
             <?php
                 $database = app('firebase.database'); 
                 $user = $database->getReference('User');
                 $currentUser = $database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();  
                 echo $currentUser['userAvatar'];
             ?>
             " alt="" />
      <form name="form" action="comment/{{$item['idPost']}}" method="post" onsubmit="return validateForm('<?php echo $item['idPost']?>')">
        @csrf
        <input id="{{$item['idPost']}}" class="messageSender__input" placeholder="Write your comment" type="text" name="comment" />
      </form>
    </div>
    <!-- input comment ends -->
  </div>
  <!-- normal post end -->
   @endif 
   @endforeach
  <!-- post ends -->
  <!-- feed ends -->

  @endsection