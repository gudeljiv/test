<? include "includes/settings.php"; ?>
<? include "includes/class.dobavljac.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$dobavljac = new dobavljac;
	$dobavljac->deleteDobavljac($_POST["id"]); 
	
	
	echo "<script>window.location = 'index.php?page=dobavljaci';</script>";
	
?>