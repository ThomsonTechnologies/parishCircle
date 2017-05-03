<?php
include("./view/header.php");
include("./model/countries.php");

$join_start = "";

?>
<div id="midBox">
<main id="main">
<div id="joinBox">

<table  id="tablejoinstart">
<form action="join.php" method="post">
  <tbody>
    <tr>
      <td>I am a</td>
      <td><select name="user_category">
		<option value="select">Select</option>
      	<option value="parishioner">Parishioner</option>
        <option value="parishhead">Parish head</option>
        <option value="diocesehead">Diocese respresentative</option>
      </select></td>
      <td>from</td>
      <td><select name="user_country">
		    <option value="select">Select</option>
        	<?php foreach ($countries as $country) {
          	echo"<option value='$country'>$country</option>";
        }
        ?>
      </select></td>
      <td><input type="submit" name="joinstart" value="Continue" class="btncontinue"></td>
    </tr>
    <tr>
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
 </form>
</table>
<?php
if(isset($_POST['joinstart'])){
	$user_category = $_POST['user_category'];
 	$user_country = $_POST['user_country'];

	if($user_category != 'select' && $user_country != 'select'){
    header( "Location: join_form.php?usertype=$user_category&country=$user_country" );
		include("join_form.php");
    // $error_message = $user_country;
	}else {
		$error_message = "Please select profile category and your country to continue.";
	}

}else {
	  $error_message = " Please select profile category and your country to continue.";
}
?>

<div class='warningbox'><?php echo $error_message; ?></div>

</div>
</main>

<?php include("./view/footer.php");?>
