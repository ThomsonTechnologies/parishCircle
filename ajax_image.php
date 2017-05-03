<?php
include_once('./model/DB.php');
include('./model/Login.php');
$loggedInUserId = Login::isLoggedIn();
$t_width = 170;	// Maximum thumbnail width
$t_height = 170;	// Maximum thumbnail height
$new_name = "small".$loggedInUserId.".jpg"; // Thumbnail image name
$path = "upload_sm/";
if(isset($_GET['t']) and $_GET['t'] == "ajax")
	{
		extract($_GET);
		$ratio = ($t_width/$w);
		$nw = ceil($w * $ratio);
		$nh = ceil($h * $ratio);
		$nimg = imagecreatetruecolor($nw,$nh);
		$im_src = imagecreatefromjpeg($path.$img);
		imagecopyresampled($nimg,$im_src,0,0,$x1,$y1,$nw,$nh,$w,$h);
		imagejpeg($nimg,$path.$new_name,90);
		DB::query('UPDATE parishcircle.users SET profilepic_sm=:newname WHERE id=:userid', array(':newname'=>$new_name, ':userid'=>$loggedInUserId));
		echo $new_name."?".time();
		exit;
	}

	?>
