<? include "includes/settings.php"; ?>
<? include "includes/class.kupac.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$kupac = new kupac;
	$kupac->deleteKupac($_POST["id"]); 
	
	
	echo "<script>window.location = 'index.php?page=kupci';</script>";
	
?>