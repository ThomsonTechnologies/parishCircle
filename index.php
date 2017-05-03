<?php include("./view/header.php");
$error_message = "";

if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
      if (DB::query('SELECT username FROM parishcircle.users WHERE username=:username', array(':username'=>$username))){
            if (password_verify($password, DB::query('SELECT password FROM parishcircle.users WHERE username=:username', array(':username'=>$username))[0]['password'])){
              echo 'Logged in!';
              $cstrong = True;
              $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
              $user_id = DB::query('SELECT id FROM parishcircle.users WHERE username=:username', array(':username'=>$username))[0]['id'];

              DB::query('INSERT INTO parishcircle.login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
              setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
              setcookie("SNID_", 1, time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

              header("Location: profile.php?username=$username");

            }else{$error_message = "incorrect password!";}
      }else{$error_message = "User not registered!";}
}

?>

<div id="midBox">
<main id="main">
<div id="loginBox">
  <form action="index.php" method="post">
  <table  id="loginTable">
  <tbody>
  <tr>
      <td colspan="2"><h3>Login</h3></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><input type="text" name="username"></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="password" name="password"></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td></td>
    </tr>
  </tbody>
</table>
<input type="submit" name="login" value="login" id="loginbtn">

  </form>
  </div>
  
  <div id="parish_image"><img src="img/parish.png" width="423" height="470" alt=""/></div>
<div id="greetingbox">
  <p>Lets build a vibrant Parish Community.</p>
</div>
<div id="errorBox"><p><?php echo $error_message; ?></p></div>
</main>
</div>
<?php include("./view/footer.php");?>
