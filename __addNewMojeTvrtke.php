<? include "includes/settings.php"; ?>
<? include "includes/class.mojetvrtke.php"; ?>
<?

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$mojetvrtke = new mojetvrtke;
	$mojetvrtke->addNewMojeTvrtke($_POST["name"],$_POST["address"],$_POST["postal"],$_POST["city"],$_POST["oib"],$_POST["ziro"],$_POST["swift"],$_POST["mbs"],$_POST["bank"]); 
?>

	<span style='font-size: 15px;'>Tvrtka dodana!</span><br><br>
	Tvrtka <b><?=$_POST["name"]?></b> je uspješno dodana.<br><br>
	<input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup("mojetvrtke");'>
