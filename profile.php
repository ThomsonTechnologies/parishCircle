
<?php
include("./view/header.php");
include('./controller/Post.php');
include('./controller/Comment.php');

if (isset($_GET['username'])) {

  if(DB::query('SELECT username FROM parishcircle.users WHERE username=:username', array(':username'=>$_GET['username']))){
    $username = DB::query('SELECT username FROM parishcircle.users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
    $userid = DB::query('SELECT id FROM parishcircle.users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
    $fullname = DB::query('SELECT first_name, last_name FROM parishcircle.users WHERE id = :userid ', array(':userid'=>$userid));
    $loggedInUserId = Login::isLoggedIn();
    $parish_id = DB::query('SELECT parish_id FROM parishcircle.address WHERE userid = :userid ', array(':userid'=>$loggedInUserId))[0]['parish_id'];
    $parishhead_id = DB::query('SELECT userid FROM parishcircle.institution WHERE id = :parishid', array(':parishid'=>$parish_id))[0]['userid'];
    $profilepic = DB::query('SELECT profilepic_sm FROM parishcircle.users WHERE id = :userid', array(':userid'=>$userid))[0]['profilepic_sm'];
        if(isset($_POST['mypost'])){
          Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
        }

        if (isset($_GET['postid'])) {
          Post::likePost($_GET['postid'], $loggedInUserId);
        }

        if (isset($_POST['postcomment'])) {
        Comment::createComment($_POST['commentbody'], $_GET['postid'], $loggedInUserId);
        }

        $allPosts = "";

        if($userid == $loggedInUserId){
          $allPosts = Post::getAllPosts($parish_id);
        } else {
          $allPosts = DB::query('SELECT * FROM parishcircle.posts WHERE user_id = :userid', array(':userid'=>$userid));
        }


        $parish_posts = Post::getParishPosts($parishhead_id);

  }else {
    echo 'User not found';
  }
}

?>

<div id="midBox">
<main id="main">
<div id="menubarbox"></div>

<div id="upload_wondow">
  <div class="upload_box">
    <div class="upload_header">Upload profile picture</div>
    	<div class="profpic_upload_box">
        	<form action="upload.php" enctype="multipart/form-data" method="post" >
            Photo <input name="image" size="30" type="file" class="genbtn"/>
            <input name="upload" type="submit" value="Upload" class="genbtn"/>
          </form>
        </div>
  </div>
</div>

<?php include("./view/aside_left.php"); ?>

  <div id="prof_contentbox">
<div id="prof_toolbar">
    	<div id="prof_nav_box">
        	<div id="profhome"><a href="#" />Home</a></div>
          <div id="homeparish"><a href="#" />Parish</a></div>
          <div id="homediocese"><a href="#" />Diocese</a></div>
      </div>
    </div>

<div id="profhome_contbox">


       <?php
       if($loggedInUserId == $userid){
         echo"<div id='post_out_box'>
         <div class='mypost_box'>
         <form method='post'>
         <textarea rows='3' cols='75' name='postbody' class='selfpost' placeholder='Whats on your mind ?'></textarea>&nbsp;
         <input type='submit' name='mypost' class='selfpostbtn' value='Post'>
         </form>
         </div>
         </div>";
       }
      ?>



	  <div id="post_in_box">
      <?php
      $i = 0;
      foreach($allPosts as $p){
        $poster_name = DB::query('SELECT first_name, last_name FROM parishcircle.users WHERE id = :userid ', array(':userid'=>$p['user_id']));
        $posterpic = DB::query('SELECT profilepic_sm FROM parishcircle.users WHERE id = :userid', array(':userid'=>$p['user_id']))[0]['profilepic_sm'];
        $i++;
        echo "<div class='postwrapper_block' id='postwrapper" .$i. "' >


        <div class='postbox' id='postbox".$i."'>
        <div class='postbox_header'>
        <div class='poster_img_box'><img src='upload_sm/".$posterpic."' style='width:50px; height:50px' ></div>
        <div class='poster_details_box'>" .$poster_name[0]['first_name']. ' ' .$poster_name[0]['last_name']. "</br><span id='posttime'> " .date_format(date_create($p['posted_at']), 'g:ia \o\n l jS F Y'). "</span></div>
        </div>

        <div class='post_body_box'>" .html_entity_decode($p['body']). "</div>

        <div class='likesbox'>";
        if(!DB::query('SELECT post_id FROM parishcircle.post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))){
          echo "<form action='profile.php?username=$username&postid=" .$p['id']. "' method='post'>
                <input type='submit' name='like' value='Like' class='likesbtn'>
                <span>".$p['likes']." likes</span>
            </form>";
        }else {
          echo "<form action='profile.php?username=$username&postid=" .$p['id']. "' method='post'>
                <input type='submit' name='unlike' value='UnLike' class='likesbtn'>
                <span>".$p['likes']." likes</span>
            </form>";
        }
        echo "</div>
        <div class='commentbtn_box' id='commentbtnbox".$i."'>Comment</div>
        </div>"; //postbox

        echo" <div class='commentbase_block' id='commentbox".$i."'>

        <div class='commentpost_block'>
        <form action='profile.php?username=$username&postid=" .$p['id']. "' method='post'>
        <textarea name='commentbody' class='comment_body'></textarea>
        <input type='submit' name='postcomment' value='Comment'class='comentpost_btn'>
        </form>
        </div>";

        $comments = Comment::displayComments($p['id']);

        foreach($comments as $comment){
        $commentor_name = DB::query('SELECT first_name, last_name FROM parishcircle.users WHERE id = :userid ', array(':userid'=>$comment['user_id']));
        $commentor_pic = DB::query('SELECT profilepic_sm FROM parishcircle.users WHERE id = :userid', array(':userid'=>$comment['user_id']))[0]['profilepic_sm'];
        echo"<div class='comments_block'>

        <div class='comment_header_box'>
        <div class='commentorimg_box'><img src='upload_sm/".$commentor_pic."' style='width25px; height:25px' ></div>
        <div class='commentor_name_box'>".$commentor_name[0]['first_name']. ' ' .$commentor_name[0]['last_name']."</div>
        </div>
        <div class='comment_body_box'>".$comment['comment']."</div>

        </div>";
        }

        echo"</div></div>"; //commentbase_block and postwrapper_block
     }?>

    </div>
	</div>

  <div id="parish_contbox">
    <div id="post_in_box">
      <?php
      foreach($parish_posts as $p){
        $poster_name = DB::query('SELECT first_name, last_name FROM parishcircle.users WHERE id = :parisheadid ', array(':parisheadid'=>$p['user_id']));
        $posterpic = DB::query('SELECT profilepic_sm FROM parishcircle.users WHERE id = :userid', array(':userid'=>$p['user_id']))[0]['profilepic_sm'];
        //$post_date = date_create_from_format('d/M/Y:H:i:s', $p['posted_at']);
        //$postdate = $post_date ->getTimestamp();
        echo "<div class='postbox'>
        <div class='postbox_header'>
        <div class='poster_img_box'><img src='upload_sm/".$posterpic."' style='width50px; height:50px' ></div>
        <div class='poster_details_box'>" .$poster_name[0]['first_name']. ' ' .$poster_name[0]['last_name']. "</br><span id='posttime'> " .date_format(date_create($p['posted_at']), 'g:ia \o\n l jS F Y'). "</span></div>
        </div>
        <div class='post_body_box'>" .htmlspecialchars($p['body']). "</div>
        <div class='likesbox'>";
        if(!DB::query('SELECT post_id FROM parishcircle.post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))){
          echo "<form action='profile.php?username=$username&postid=" .$p['id']. "' method='post'>
                <input type='submit' name='like' value='Like' class='likesbtn'>
                <span>".$p['likes']." likes</span>
            </form>";
        }else {
          echo "<form action='profile.php?username=$username&postid=" .$p['id']. "' method='post'>
                <input type='submit' name='unlike' value='UnLike' class='likesbtn'>
                <span>".$p['likes']." likes</span>
            </form>";
        }

        echo "</div>
        </div>";
     }?>
    </div>

  </div>

  <div id="diocese_contbox">Diocese</div>

  </div>



  <div id="right_aside">right aside box</div>

</main>
</div>

<?php include("./view/footer.php");?>
