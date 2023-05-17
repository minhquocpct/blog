<div class="header">
  <!-- Search starts -->
  <div class="header__left">
    <img src="assets/img/logo.png" alt="Logo" />
    <div class="header__input">
      <span class="material-icons"> search </span>
      <form action="search" method="post" onsubmit="return validateForm('search')" >
        @csrf
        <input id="search"type="text" placeholder="Search Blog" name="search" />
      </form>
    </div>
  </div>
  <!-- Search ends -->
  <!-- Header option starts -->
  <div class="header__middle">
    <div class="header__option  active">
      <a href="
            
				<?php echo url('/') ?>
            ">
        <span class="material-icons">home</span>
      </a>
    </div>
  </div>
  <!-- Header option ends -->
  <!-- Header right starts -->
  <div class="header__right">
    <div class="header__info">
      <!-- Avatar User -->
      <img class="user__avatar" src="
                
				<?php
                    $database = app('firebase.database'); 
                    $currentUser = $database->getReference('User')->getChild(Cookie::get('iduserlogin'))->getValue();  
                    echo $currentUser['userAvatar'];
                ?>" alt="Avatar" />
      <!-- User Name -->
      <h4> <?php
                echo $currentUser['userName']
            ?> </h4>
    </div>
    <span class="material-icons"> notifications_active </span>
    <a href="
    
				<?php echo url('setting') ?>
    ">
      <span class="material-icons"> settings </span>
    </a>
    <a href="
    
				<?php echo url('logout') ?>
    ">
      <span class="material-icons"> logout </span>
    </a>
  </div>
  <!-- Header right ends -->
</div>