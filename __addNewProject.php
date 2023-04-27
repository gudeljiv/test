<? include "includes/settings.php"; ?>
<? include "includes/class.project.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$project = new project;
	$project->addNewProject($_POST["nazivProjekta"],$_POST["parentProjekta"],$_POST["budget"]); 
?>

	<span style='font-size: 15px;'>Projekt dodan!</span><br><br>
	Kategorija <?=$_POST["nazivProjekta"]?> je uspješno dodana.<br><br>
	<input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup("projekti",<?=$_POST["parentID"]?>);'>
