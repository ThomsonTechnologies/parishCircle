<?php
include("./view/header.php");
include('ResizeImage.php');

$image = "";
$error_message = "";
$loggedInUserId = Login::isLoggedIn();
$path = "uploads/";
$path2 = "upload_sm/";
$valid_formats = array("jpg", "png", "gif", "bmp");

if(isset($_POST['submit']))	{
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))	{
        list($txt, $ext) = explode(".", $name);

        if(in_array($ext,$valid_formats) && $size<(2097152)) {
            $actual_image_name = time().substr($txt, 5).".".$ext;
            $resize_image_name = time().substr($txt, 5). "-sm.".$ext;

            $tmp = $_FILES['photoimg']['tmp_name'];
            $target1 = $path .$actual_image_name;
            $target2 = $path2 .$resize_image_name;
            if(move_uploaded_file($tmp, $path.$actual_image_name)){
                DB::query('UPDATE parishcircle.users SET profilepic=:actualimagename WHERE id=:userid', array(':actualimagename'=>$actual_image_name, ':userid'=>$loggedInUserId));

                $image_info = getimagesize($target1);
                $actual_width = $image_info[0];
                $actual_height = $image_info[1];
                $ratio = $actual_width / $actual_height;

                $target_height = 300;
                $target_width = $target_height *  $ratio;
                // if()
                $image_resized = ResizeImage::scaleImage($target1, $target_width, $target_height, $target2);
                $image="<h3>Please drag on the image</h3><img src='upload_sm/".$resize_image_name."' id=\"photo\"  >";


            }	else{echo "Failed";}

          } else{echo "Invalid file formats..!";}

      }	else{echo "Please select image..!";}

  }
?>




<div id="midBox">
<main id="main">
  <div class="leftaside">
    <div id="settings_box">
    <p>Account Settings</p>
    <div id="prof_update">Update profile</div>
    <div id="change_pw">Change password</div>
    <div id="uploadpic">Upload photo</div>
    </div>
  </div>

  <div id="pic_uploadcont_base">
      <div style="margin:0 auto; width:600px" id="imgbox">
          <div id="photocropbox"><?php echo $image; ?></div>
          <div id="thumbs" style="padding:5px; width:600px"></div>

          <div style="width:600px" class="photoformbox">
          <form id="cropimage" method="post" enctype="multipart/form-data">
          	Upload your image <input type="file" name="photoimg" id="photoimg" />
          	<input type="hidden" name="image_name" id="image_name" value="<?php echo($resize_image_name)?>" />
          	<input type="submit" name="submit" value="Submit" id="cropsubmit"/>
          </form>

          </div>
      </div>
  </div>
</main>
</div>
<?php include("./view/footer.php");?>
