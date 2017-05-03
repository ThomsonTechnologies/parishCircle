<?php
ob_start();
require_once("./model/DB.php");
include("./model/Login.php");

$fname = "";
$loggedIn = false;
if(Login::isLoggedIn()){
  $userid = Login::isLoggedIn();
  $username = DB::query('SELECT username FROM parishcircle.users WHERE id = :userid ', array(':userid'=>$userid));
  $fname = DB::query('SELECT first_name FROM parishcircle.users WHERE id = :userid ', array(':userid'=>$userid));
  $loggedIn = true;
}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Parish Circle</title>
<link href="style/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="style/imgareaselect-default.css" />
<link href="https://fonts.googleapis.com/css?family=Cuprum|Raleway|Rokkitt|Wendy+One|Ranchers|Roboto|Anton|Poppins|Bilbo+Swash+Caps|Euphoria+Script|Princess+Sofia" rel="stylesheet" type="text/css">
<script src="js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript" src="js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="js/mainjs.js"></script>

</head>

<body>
<header class="header">
<div class="headerWarpper">
<div class="logoBox"><h2><img src="img/parishLogo.png" width="184" height="51" alt=""/></h2></div>
  <div class="searchBox">
  <form action="./controller/search.php" method="get">
  <input type="text" name="search" class="search" placeholder="Search your parish">
  <input type="button" name="search" value="Search" class="searchbtn">
  </form>
  </div>
  <div id="notification_box">

  </div>
  <div class="navBox">
    <?php
      if($loggedIn){
        echo "<a href='profile.php?username=".$username[0]['username']. "' />" .$fname[0]['first_name']."</a>";
      }else {
        echo "<a href='index.php' />Home</a>";
      }
    ?>
    <a href="about.php" />About</a>
    <?php if(Login::isLoggedIn()){
      echo "<a href='settings.php'/>Settings</a>
            <a href='logout.php'/>Logout</a>";
    }else {
      echo "<a href='join.php'/>Join</>
            <a href='index.php'/>Sign in</a>";
    }?>
  </div>
</div>
</header>
