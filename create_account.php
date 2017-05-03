<?php
include_once("./model/DB.php");

if(isset($_POST['createaccount'])){
  $user_category = $_POST['usercategory'];
  $surname = $_POST['usercategory'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];

  $parish_name = "";
  $diocese_name = "";
  if($user_category == 'parishioner'){
    $parish_name = $_POST['parishname'];
    $diocese_name = $_POST['diocesename'];
  }else if($user_category == 'parishhead'){
    $parish_name = $_POST['parish_name'];
    $diocese_name = $_POST['diocesename'];
  }else if($user_category == 'diocesehead'){
    $diocese_name = $_POST['diocese_name'];
    $parish_name = $diocese_name;
  }

  $username = htmlspecialchars($_POST['username']);
  $password1 = htmlspecialchars($_POST['password1']);
  $password2 = htmlspecialchars($_POST['password2']);
  $address1 = htmlspecialchars($_POST['address1']);
  $address2 = htmlspecialchars($_POST['address2']);
  $city = htmlspecialchars($_POST['city']);
  $state = htmlspecialchars($_POST['state']);
  $country = htmlspecialchars($_POST['country']);
  $zip = filter_input(INPUT_POST, 'zip', FILTER_VALIDATE_INT);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $phone = htmlspecialchars($_POST['phone']);

  if($surname != 'Select'){
  if((strlen($fname) >= 1 && strlen($fname) <= 250) && (strlen($lname) >= 1 && strlen($lname) <= 250)){
  if (strlen($username) >= 3 && strlen($username) <= 32){
  if (!DB::query('SELECT username FROM parishcircle.users WHERE username=:username', array(':username'=>$username))) {
  if (preg_match('/[a-zA-Z0-9_]+/', $username)){
  if($password1 == $password2){
  if (strlen($password1) >= 6 && strlen($password1) <= 60) {
  if(strlen($address1) >= 0 && strlen($address1) <= 250){
  if(preg_match('/^[a-z0-9 .\-]+$/i', $address1)){
  if(strlen($address2) <= 250){
  if(preg_match('/^[a-z0-9 .\-]+$/i', $address2)){
  if(strlen($city) >= 0 && strlen($city) <= 250){
  if(strlen($state) >= 0 && strlen($state) <= 250){
  if(strlen($country) >= 0 && strlen($country) <= 250){
  if(strlen($zip) >= 0 && strlen($zip) <= 20){
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
  if (!DB::query('SELECT email FROM parishcircle.users WHERE email=:email', array(':email'=>$email))){
  if(strlen($phone) >= 0 && strlen($phone) <= 20){

        //DB::query('INSERT INTO parishCircle.users VALUES (\'\', :username, :password, :email, \'0\')',
        //array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));

  }else {
    echo 'Invalid phone number!';
  }
  }else {
    echo 'Email already exists!';
  }
  }else {
    echo 'Invalid email!';
  }
  }else {
    echo 'too long zip code!';
  }
  }else {
    echo 'too long country!';
  }
  }else {
    echo 'too long state name!';
  }
  }else {
    echo 'Too long city name!';
  }
  }else {
    echo 'Invalid address';
  }
  }else {
    echo 'too long address';
  }
  }else {
    echo 'Invalid address';
  }
  }else {
    echo 'too long address';
  }
  }else {
    echo 'Invalid password!';
  }
  }else {
    echo 'Your passwords do not match';
    header("Location: join.php");
  }
  }else {
    echo 'Invalid username';
  }
  }else {
    echo 'Username already exists';
  }
  }else {
    echo 'Invalid username';
  }
  }else {
    echo 'Name character limit exceeded';
  }
  }else {
   echo 'Select title';
  }
}

echo "\n $user_category";
echo "\n $fname";
echo "\n $lname";
echo "\n $parish_name";
echo "\n $diocese_name";
echo "\n $username";
echo "\n $password1";
echo "\n $password2";
echo "\n $address1";
echo "\n $address2";
echo "\n $city";
echo "\n $state";
echo "\n $country";
echo "\n $zip";
echo "\n $email";
echo "\n $phone";

?>
