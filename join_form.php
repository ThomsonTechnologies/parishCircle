<?php
include("./view/header.php");
include("./model/countries.php");

$form_error = "";
$form_error = "";
$address_title = "";
$date = new DateTime();
$timenow = $date->format('Y-m-d-H-i-s');
$usercategory = "";
$user_country = "";

if(isset($_GET['usertype']) && isset($_GET['country'])){
      $user_category = $_GET['usertype'];
      $user_country = $_GET['country'];

      $parish_list = DB::query("SELECT i.id, i.name, a.address1, a.state, a.country
                  FROM parishcircle.institution i
                  INNER JOIN parishcircle.address a on i.id = a.parish_id
                  WHERE (a.country = :country AND a.category = 'parish') AND i.category = 'parish'",
                        array(':country'=>$user_country));

      $diocese_list = DB::query("SELECT i.id, i.name, a.address1, a.state, a.country
                  FROM parishcircle.institution i
                  INNER JOIN parishcircle.address a on i.id = a.parish_id
                  WHERE (a.country = :country AND a.category = 'diocese') AND i.category = 'diocese'",
                        array(':country'=>$user_country));


}

if(isset($_POST['usercategory'])){
      $user_category = $_POST['usercategory'];
      $user_country = $_POST['usercountry'];

      $parish_list = DB::query("SELECT i.id, i.name, a.address1, a.state, a.country
                    FROM parishcircle.institution i
                    INNER JOIN parishcircle.address a on i.id = a.parish_id
                    WHERE (a.country = :country AND a.category = 'parish') AND i.category = 'parish'",
                          array(':country'=>$user_country));

      $diocese_list = DB::query("SELECT i.id, i.name, a.address1, a.state, a.country
                    FROM parishcircle.institution i
                    INNER JOIN parishcircle.address a on i.id = a.parish_id
                    WHERE (a.country = :country AND a.category = 'diocese') AND i.category = 'diocese'",
                          array(':country'=>$user_country));

}


if(isset($_POST['createaccount'])) {

      $title = $_POST['surname'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];

      $parish_id = "";
      $diocese_id = "";
      $parish_name = "";
      $diocese_name = "";
      $institution = "";
      $form_error = $user_category;
      if($user_category == 'parishioner'){
          $parish_id = $_POST['parish_id'];
          $diocese_id = $_POST['diocese_id'];
      }else if($user_category == 'parishhead'){
          if(isset($_POST['parish_name'])){
              $parish_name = $_POST['parish_name'];
              $diocese_id = $_POST['diocese_id'];
          }

      }else if($user_category == 'diocesehead'){
          if(isset($_POST['diocese_name'])){
              $diocese_name = $_POST['diocese_name'];
              $institution = $diocese_name;
          }
      }

    // $form_error = $user_category;

      $username = htmlspecialchars($_POST['username']);
      $password1 = htmlspecialchars($_POST['password1']);
      $password2 = htmlspecialchars($_POST['password2']);
      $address1 = htmlspecialchars($_POST['address1']);
      $address2 = htmlspecialchars($_POST['address2']);
      if(!isset($address2) || trim($address2) == ''){
        $address2 = ' ';
      }

      $city = htmlspecialchars($_POST['city']);
      $state = htmlspecialchars($_POST['state']);
      $zip = filter_input(INPUT_POST, 'zip', FILTER_VALIDATE_INT);
      $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
      $phone = htmlspecialchars($_POST['phone']);
      if(isset($_POST['website']))$website = htmlspecialchars($_POST['website']);

      if($title != 'Select'){
      if((strlen($fname) >= 1 && strlen($fname) <= 250) && (strlen($lname) >= 1 && strlen($lname) <= 250)){
      if (strlen($username) >= 3 && strlen($username) <= 32){
      if(!DB::query('SELECT username FROM parishcircle.users WHERE username=:username', array(':username'=>$username))){
      if (preg_match('/[a-zA-Z0-9_]+/', $username)){
      if($password1 == $password2){
      if (strlen($password1) >= 6 && strlen($password1) <= 60){
      if(strlen($address1) >= 0 && strlen($address1) <= 250){
      if(preg_match('/^[a-z0-9 .\-]+$/i', $address1)){
      if(strlen($address2) <= 250){
      if(strlen($city) >= 0 && strlen($city) <= 250){
      if(strlen($state) >= 0 && strlen($state) <= 250){
      if(strlen($zip) >= 0 && strlen($zip) <= 20){
      if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      if (!DB::query('SELECT email FROM parishcircle.users WHERE email=:email', array(':email'=>$email))){

//USER IS PARISIONER
        if($user_category == 'parishioner'){
              if(isset($parish_id) || !empty($parish_id)){
                  $website = 'na';
              			DB::query("INSERT INTO parishCircle.users VALUES ('', :title, :fname, :lname,
              											:username, :password, :category, :email, :signupdate, '', '', DEFAULT)",
              											array(':title' =>$title, ':fname' =>$fname, ':lname' =>$lname, ':username' =>$username, ':password' =>password_hash($password1, PASSWORD_BCRYPT),
              											':category' =>$user_category  , ':email' =>$email, ':signupdate' => $timenow ));

              			$user_id = DB::query('SELECT id FROM parishcircle.users WHERE username=:username', array(':username'=>$username))[0]['id'];

              			DB::query("INSERT INTO parishCircle.address VALUES ('', :userid, :parishid, :dioceseid, :address1, :address2, :city, :state, :country, :zip, :phone, :website, :category)",
              											array(':userid' =>$user_id, ':parishid' =>$parish_id, ':dioceseid' =>$diocese_id, ':address1' =>$address1, ':address2' =>$address2,
              											':city' =>$city, ':state' =>$state, ':country' =>$user_country, ':zip' =>$zip, ':phone' =>$phone, ':website' =>$website, ':category' =>$user_category));
              }else {
                      $form_error =  'Please select your parish and diocese. Contact your parish authority if you could not find your parish in the list.';
              }
  //USER IS PARISH HEAD

        }else if($user_category == 'parishhead'){
              if(isset($_POST['parish_name']) && (isset($diocese_id) || !empty($diocese_id))){
                  $form_error = $user_category;
                  $inst_category = 'parish';
                  DB::query("INSERT INTO parishCircle.users VALUES ('', :title, :fname, :lname,
                                  :username, :password, :category, :email, :signupdate, '', '', DEFAULT)",
                                  array(':title' =>$title, ':fname' =>$fname, ':lname' =>$lname, ':username' =>$username, ':password' =>password_hash($password1, PASSWORD_BCRYPT),
                                  ':category' =>$user_category  , ':email' =>$email, ':signupdate' => $timenow ));

                  $user_id = DB::query('SELECT id FROM parishcircle.users WHERE username=:username', array(':username'=>$username))[0]['id'];

                  DB::query("INSERT INTO parishCircle.institution VALUES ('', :userid, :name, :category)",
                                  array(':userid' =>$user_id, ':name' =>$parish_name, ':category' =>$inst_category));

                  $parish_id = DB::query('SELECT id FROM parishcircle.institution WHERE userid=:userid', array(':userid'=>$user_id))[0]['id'];

                  DB::query("INSERT INTO parishCircle.address VALUES ('', :userid, :parishid, :dioceseid, :address1, :address2, :city, :state, :country, :zip, :phone, :website, :category)",
                                  array(':userid' =>$user_id, ':parishid' =>$parish_id, ':dioceseid' =>$diocese_id, ':address1' =>$address1, ':address2' =>$address2,
                                  ':city' =>$city, ':state' =>$state, ':country' =>$user_country, ':zip' =>$zip, ':phone' =>$phone, ':website' =>$website, ':category' =>$inst_category));
              }else{
                      $form_error =  'Please select your diocese. Contact your diocese authority if you could not find your diocese in the list.';
              }
//USER IS DIOCESE HEAD
        }else if($user_category == 'diocesehead'){
              if(isset($diocese_name)){
                $form_error = $user_category;
                $inst_category = 'diocese';
                DB::query("INSERT INTO parishCircle.users VALUES ('', :title, :fname, :lname,
                                :username, :password, :category, :email, :signupdate, '', '', DEFAULT)",
                                array(':title' =>$title, ':fname' =>$fname, ':lname' =>$lname, ':username' =>$username, ':password' =>password_hash($password1, PASSWORD_BCRYPT),
                                ':category' =>$user_category  , ':email' =>$email, ':signupdate' => $timenow ));

                $user_id = DB::query('SELECT id FROM parishcircle.users WHERE username=:username', array(':username'=>$username))[0]['id'];

                DB::query("INSERT INTO parishCircle.institution VALUES ('', :userid, :name, :category)",
                                array(':userid' =>$user_id, ':name' =>$diocese_name, ':category' =>$inst_category));

                $parish_id = DB::query('SELECT id FROM parishcircle.institution WHERE userid=:userid', array(':userid'=>$user_id))[0]['id'];

                DB::query("INSERT INTO parishCircle.address VALUES ('', :userid, :parishid, :dioceseid, :address1, :address2, :city, :state, :country, :zip, :phone, :website, :category)",
                                array(':userid' =>$user_id, ':parishid' =>$parish_id, ':dioceseid' =>$parish_id, ':address1' =>$address1, ':address2' =>$address2,
                                ':city' =>$city, ':state' =>$state, ':country' =>$user_country, ':zip' =>$zip, ':phone' =>$phone, ':website' =>$website, ':category' =>$inst_category));
              }else{
                      $form_error =  'Please enter your diocese name.';
              }
        }else {
            $form_error =  'Please select the account cactegory';
        }

      }else{$form_error =  'Email already exists';}
      }else{$form_error =  'Invalid email address';}
      }else{$form_error =  'Invalid zip code ';}
      }else{$form_error =  'Invalid state name ';}
      }else{$form_error =  'Invalid city name ';}
      }else{$form_error =  'too long address2 ';}
      }else{$form_error =  'Invalid address';}
      }else{$form_error =  'Invalid address';}
      }else{$form_error =  'Password should be between 6 and 60 characters ';}
      }else{$form_error =  'Passwords do not match';}
      }else{$form_error =  'Invalid username';}
      }else{$form_error =  'username already exists';}
      }else{$form_error =  'too long username';}
      }else{$form_error =  'Name character limit exceeded';}
      }else{$form_error =  'Select title';}

}

?>

<div id="midBox">
<main id="main">
<div id="joinBox">
<form action="join_form.php" method="post">
 <table id="joinTable">
 <tbody>
   <tr>
     <td>&nbsp;</td>
     <td><p>Personal details</p></td>
     <td>&nbsp;</td>
     <td><input type="hidden" name="usercategory" value= <?php echo $user_category; ?>></td>
   </tr>
   <tr>
     <td>Title</td>
     <td><select name="surname" class="combo" width="60 px">
       <option selected="select">Select</option>
       <option value="Mr.">Mr.</option>
     <option value="Mrs.">Mrs.</option>
     <option value="Ms.">Ms.</option>
       <option value="Rev.">Rev.</option>
       <option value="Fr.">Fr.</option>
     <option value="Sr.">Sr.</option>
     </select></td>
     <td></td>
     <td></td>
   </tr>
   <tr>
     <td>First Name</td>
     <td><input type="text" name="fname" pattern="[a-zA-Z]{1,}" required></td>
     <td>Last Name</td>
     <td><input type="text" name="lname" pattern="[a-zA-Z]{1,}" required></td>
   </tr>

    <?php
     if($user_category == 'parishioner') {
       $address_title = 'Your home address';
       echo "<tr><td>Parish Name</td>
             <td><select name='parish_id'>";
       foreach ($parish_list as $parish) {
         echo '<option value='.'\''.$parish['id'].'\''.'>'.$parish['name']. ', ' . $parish['address1']. ', ' . $parish['state']. ', ' .$parish['country']. '</option>';
       }
       echo "</select></td>
             <td>Diocese</td>
             <td><select name='diocese_id'>";
       foreach ($diocese_list as $diocese) {
             echo '<option value='.'\''.$diocese['id'].'\''.'>'.$diocese['name']. ', ' . $diocese['address1']. ', ' . $diocese['state'].', ' .$diocese['country']. '</option>';
       }
       echo "</select></td></tr>";
     }else if($user_category == 'parishhead'){
       $address_title = 'Your parish address';
       echo "<tr><td>Parish Name</td>
            <td><input type='text' name='parish_name'></td>";
       echo "<td>Diocese</td>
             <td><select name='diocese_id'>";
       foreach ($diocese_list as $diocese) {
            echo '<option value='.'\''.$diocese['id'].'\''.'>'.$diocese['name']. ', ' . $diocese['address1']. ', ' . $diocese['state'].', ' .$diocese['country']. '</option>';
       }
            echo "</select></td></tr>";
     }else if($user_category == 'diocesehead'){
       $address_title = 'Your diocese address.';
        echo "<tr><td>Diocese Name</td>
              <td><input type='text' name='diocese_name'></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td></tr>";
     }else {

     }

    ?>

   <tr>
     <td>&nbsp;</td>
     <td><p>Login details </P></td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td>Username</td>
     <td><input type="text" name="username" ></td>
     <td></td>
     <td></td>
   </tr>
   <tr>
     <td>Password</td>
     <td><input type="password" name="password1"></td>
     <td>Repeat password</td>
     <td><input type="password" name="password2"></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td colspan="2"><p><?php echo $address_title ?></p></td>
     <td>&nbsp;</td>

   </tr>
   <tr>
     <td>Address</td>
     <td><input type="text" name="address1"></td>
     <td>Address2</td>
     <td><input type="text" name="address2"></td>
   </tr>
   <tr>
     <td>City</td>
     <td><input type="text" name="city"></td>
     <td>State</td>
     <td><input type="text" name="state"></td>
   </tr>
   <tr>
     <td>Country</td>
     <td><input type="text" name="usercountry" value= <?php echo"'" .$user_country."''"; ?> readonly></td>
     <td>Zip</td>
     <td><input type="number" name="zip"></td>
   </tr>
   <tr>
     <td>Email</td>
     <td><input type="email" name="email"></td>
     <td>Phone</td>
     <td><input type="number" name="phone"></td>
   </tr>
   <?php if($user_category == 'parishhead' || $user_category == 'diocesehead'){
     echo "
   <tr>
     <td>Website</td>
     <td><input type='text' name='website'></td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
   </tr>";
 } ?>
 </tbody>
</table>
<input type="submit" name="createaccount" value="JOIN" class="joinbtn" >
</form>

<div id="form_error_box">
<p><?php echo $form_error; ?></p>
</div>


</div>
</main>

<?php include("./view/footer.php");?>
