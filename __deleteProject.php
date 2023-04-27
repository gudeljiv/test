<? include "includes/settings.php"; ?>
<? include "includes/class.project.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$project = new project;
	$project->deleteProject($_POST["id"]); 
	
	
	echo "<script>window.location = 'index.php?page=projekti&parentID=".$_POST["parentID"]."';</script>";
	
?>