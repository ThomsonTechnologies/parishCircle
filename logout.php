<?php
include('./view/header.php');
//include('./model/Login.php');
// if (!Login::isLoggedIn()) {
//         die("Not logged in.");
// }
if (isset($_POST['confirm'])) {
        if (isset($_POST['alldevices'])) {
                DB::query('DELETE FROM parishcircle.login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));
                header("Location: index.php");
        } else {
                if (isset($_COOKIE['SNID'])) {
                        DB::query('DELETE FROM parishcircle.login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
                        header("Location: index.php");
                }
                setcookie('SNID', '1', time()-3600);
                setcookie('SNID_', '1', time()-3600);
                header("Location: index.php");
        }
}
?>
<div id="midBox">
<main id="main">
<div id="loginBox">
<h4>Logout of your Account?</h4>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
        <input type="checkbox" name="alldevices" value="alldevices"> Logout of all devices?<br />
        <input type="submit" name="confirm" value="Confirm">
</form>
</div>
</main>
</div>
<?php include("./view/footer.php");?>
