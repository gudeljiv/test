<? include "includes/settings.php"; ?>
<? include "includes/class.user.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$user = new user;
	$userID = $user->loginUser($_POST["username"],$_POST["password"]); 

	if ($userID > 0){
		$_SESSION["_user"]["userID"] = $userID;
		echo "
			<span style='font-size: 15px;'>Uspješno logiranje!</span><br><br>
			<br><br>
			<!--input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup(\"naslovna\");'-->
		";
		echo "<script>window.location = 'index.php';</script>";
	} else {
		echo "
			<span style='font-size: 15px;'>Greška!</span><br><br>
			Korisnik ne postoji<br><br>
			<input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup();'>
		";
	}
	
?>
