<? include "includes/settings.php"; ?>
<? include "includes/class.kupac.php"; ?>
<?

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$kupac = new kupac;
	$kupac->addNewKupac($_POST["name"],$_POST["address"],$_POST["postal"],$_POST["city"],$_POST["oib"],$_POST["ziro"],$_POST["swift"]); 
?>

	<span style='font-size: 15px;'>Kupac dodan!</span><br><br>
	Kupac <b><?=$_POST["name"]?></b> je uspješno dodan.<br><br>
	<input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup("kupac");'>
