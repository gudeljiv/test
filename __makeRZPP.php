<? include "includes/settings.php"; ?>
<? include "includes/class.racuni.php"; ?>
<?

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$racun = new racun;
	$racun->makeRZPP($_POST["id"]); 
	
	
	echo "<script>window.location = 'index.php?page=".$_POST["page"]."&vrsta=".$_POST["vrsta"]."';</script>";
	
?>
