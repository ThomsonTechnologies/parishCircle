  <?php
  //include("./model/DB.php");
  //include("./model/Login.php");

  if(Login::isLoggedIn()){
    $loggedInUserIdid = Login::isLoggedIn();
    $parish_id =  DB::query('SELECT parish_id FROM parishcircle.address WHERE userid = :userid ', array(':userid'=>$loggedInUserIdid))[0]['parish_id'];
    $allfriends = DB::query('SELECT u.id, u.username, u.profilepic_sm FROM parishcircle.users u
                              INNER JOIN parishcircle.address a
                              ON u.id = a.userid
                              WHERE a.parish_id = :parishid', array(':parishid'=>$parish_id));

    $rowcount = count($allfriends);
  }

  ?>

  <div class="leftaside">
	<div id="profilepicbox">
      <div class="profpic_header"><?php echo $fullname[0]['first_name']. ' ' .$fullname[0]['last_name']?></div>
      <div class="profimg_box">
      <?php echo"<img src='upload_sm/".$profilepic."'>"; ?>
		<div id="profimgup_box"></div>
      </div>
    </div>

    <div id="aboutme_box">
    <p>Wish you all a wonderful day!</p>
    </div>

    <div id="friendsbox_base">
      <div id="friends_box">
   <?php
      foreach($allfriends as $friend){
        // echo"<div class='friendbox'>
        // <a href='profile.php?username='".$friend['username']."> <img src='upload_sm/'".$friend['profilepic_sm']." style='width25px; height:25px'\'></a></div>";
        //<a href='settings.php'/>              Settings</a>
        if($friend['id'] == $loggedInUserIdid){
          continue;
        }else{
          echo "<div class='friendbox'><a href='profile.php?username=".$friend['username']."'/><img src='upload_sm/".$friend['profilepic_sm']."'style='width:40px; height:40px'></a></div>";
        }


        // <div class='friendbox'>  <a href='profile.php'/>  <img src='upload_sm'>  </a>  </div>
      }
    ?>
    </div>
    </div>
  </div>
