<? include "includes/settings.php"; ?>
<? include "includes/class.project.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$project = new project;
	$arrs = $project->getProjectInfo($_POST["id"]); 
	

	
	
	//echo "<script>window.location = 'index.php?page=projekti&parentID=".$_POST["parentID"]."';</script>";
	
?>

		<form name="editProjectForm" id="editProjectForm" action="<?=$PHP_SELF?>" method="POST" autocomplete="off">
			<input type="hidden" name="editP" value="1">
			<input type="hidden" name="id" value="<?=$arrs[0]["id"]?>">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Naziv</td>
					<td class="fieldValue" width="325"><input type="text" name="nazivProjekta" value="<?=$arrs[0]["name"]?>" class="input style" style="width: 285px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Budget</td>
					<td class="fieldValue" width="325"><input type="text" name="budget" value="<?=number_format($arrs[0]["budget"], "2", ",", "")?>" class="input style" style="width: 285px" /></td>
				</tr>
				</tr>
					<td>&nbsp;</td>
					<td align="right">
						<input type='button' class='button green right' id='submit' value='Odustani' onClick='javascript: closePopup();'>
						<button type="submit" class='button green right'>Promijeni</button>
					</td>
				</tr>
			</table>
		</form>
