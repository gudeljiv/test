<? session_start(); ?>
<? include "includes/settings.php"; ?>
<? include "includes/class.racuni.php"; ?>
<?
	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	$racun = new racun;
	
	$racun_history = "0";
	if ($_POST["copyHidden"] == "") {
		if ($_POST["racun_history_vrsta"] == "PRN") {
			$_POST["vrsta_izlaznog"] = "RZPP";
			$racun_history = $_POST["racun_history"];
		}
		
		if ($_POST["racun_history_vrsta"] == "RZPP") {
			$_POST["vrsta_izlaznog"] = "RN";
			$racun_history = $_POST["racun_history"];
		}
	}
	
	// var_dump($_POST);
	
	$racun->addNewRacun(
		$_POST["rac_projekt"],
		$_POST["vrsta"],
		$_POST["rac_br"],
		$_POST["ufa"],
		$_POST["rac_brizlaznog"],
		$_POST["rac_brizlaznogID"],
		$_POST["rac_dobavljac"],
		date("Y-m-d H:i:s", strtotime($_POST["rac_datum"])),
		$_POST["rac_pdv"],
		str_replace(",",".",$_POST["rac_iznos"]),
		str_replace(",",".",$_POST["rac_neoporeziviiznos"]),
		str_replace(",",".",$_POST["rac_uplaceniiznos"]),
		$_POST["rac_valuta"],
		$_POST["rac_kom"],
		str_replace(",",".",$_POST["rac_tecaj"]),
		$_POST["rac_opis"],
		$_POST["rac_kupac"],
		$_POST["rac_mojatvrtka"],
		$_POST["rac_stavka_opis"],
		$_POST["rac_stavka_pdv"],
		$_POST["rac_stavka_iznos"],
		$_POST["rac_stavka_kom"],
		$_POST["rac_stavka_neoporeziviiznos"],
		$_POST["vrsta_izlaznog"],
		$_POST["storno"],
		date("Y-m-d H:i:s", strtotime($_POST["datum_uplate"])),
		date("Y-m-d H:i:s", strtotime($_POST["datum_placanja"])),
		$_POST["broj_izvoda"],
		$racun_history,
		$_POST["kompenzacija"],
		$_SESSION["_user"]["userID"]
	);
	
?>

	<span style='font-size: 15px;'>Račun dodan!</span><br><br>
	Račun br. <?=$_POST["rac_br"]?> je uspješno dodan.<br><br>
	<input type='button' class='button green right' id='submit' value='OK' onClick='javascript: closePopup("<?=$_POST["page"]?>","<?=$_POST["vrsta"]?>");'>
