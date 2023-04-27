<? include "includes/settings.php"; ?>
<? include "includes/class.dobavljac.php"; ?>
<?
	session_start(); 

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$dobavljac = new dobavljac;
	$arrs = $dobavljac->getDobavljacInfo($_POST["id"]); 
	

	
	
	//echo "<script>window.location = 'index.php?page=projekti&parentID=".$_POST["parentID"]."';</script>";
	
?>

		<form name="editProjectForm" id="editProjectForm" action="<?=$PHP_SELF?>" method="POST" autocomplete="off">
			<input type="hidden" name="editD" value="1">
			<input type="hidden" name="id" value="<?=$arrs[0]["id"]?>">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Naziv</td>
					<td class="fieldValue" width="325"><input type="text" name="name" value="<?=$arrs[0]["name"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Adresa</td>
					<td class="fieldValue" width="325"><input type="text" name="address" value="<?=$arrs[0]["address"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Poštanski broj</td>
					<td class="fieldValue" width="325"><input type="text" name="postal" value="<?=$arrs[0]["postal"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Grad</td>
					<td class="fieldValue" width="325"><input type="text" name="city" value="<?=$arrs[0]["city"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>OIB</td>
					<td class="fieldValue" width="325"><input type="text" name="oib" value="<?=$arrs[0]["oib"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Žiro</td>
					<td class="fieldValue" width="325"><input type="text" name="ziro" value="<?=$arrs[0]["ziro"]?>" class="input style" style="width: 275px" /></td>
				</tr>
				<tr>
					<td class="fieldKey" width="125"><div class="crosspiece100"></div>Swift</td>
					<td class="fieldValue" width="325"><input type="text" name="swift" value="<?=$arrs[0]["swift"]?>" class="input style" style="width: 275px" /></td>
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
