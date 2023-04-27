<? include "includes/settings.php"; ?>
<? include "includes/class.racuni.php"; ?>
<? include "includes/class.dobavljac.php"; ?>
<? include "includes/class.project.php"; ?>
<? include "includes/class.mojetvrtke.php"; ?>
<? include "includes/class.kupac.php"; ?>
<?
	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	if($_POST["type"] == "check"){
		$query = "select * from racuni where racuni.rac_br LIKE '%".$_POST["brojRacunaCheckUnique"]."%' AND racuni.`vrsta` = 'I'";
		$result = mysqli_query($_SESSION["con"],$query);
		echo mysqli_num_rows($result);
	}
	
	if($_POST["type"] == "getAll"){
		$query = "
			SELECT 
				racuni.`id`,
				racuni.`rac_br`,
				racuni.`rac_datum`,
				racuni.`rac_iznos`,
				kupac.`name` AS kupac,
				mojetvrtke.`name` AS tvrtka
			FROM racuni 
			LEFT JOIN kupac ON kupac.`id` = racuni.`rac_kupac`
			LEFT JOIN mojetvrtke ON mojetvrtke.`id` = racuni.`rac_tvrtka`
			WHERE racuni.`rac_br` LIKE '%".$_POST["brojRacunaCheckUnique"]."%' AND racuni.`vrsta` = 'I'
			ORDER BY racuni.`rac_datum` DESC
			LIMIT 15
		";
		$result = mysqli_query($_SESSION["con"],$query);
		
		echo "<tr><td>ID</td><td>broj računa</td><td>datum</td><td>iznos</td><td>tvrtka</td><td>kupac</td></tr>";
		while($row = mysqli_fetch_array($result)){
			$t = explode (" ",$row["rac_datum"]);
			$tempD = explode ("-",$t[0]);
			$datumRacuna = $tempD[2].".".$tempD[1].".".$tempD[0];
			$vrijemeRacuna = $t[1];
			
			if($row["id"] == $_POST["selected"]) $selected = " style='background-color: #EAEAEA; cursor: pointer;' "; else $selected = " style='cursor: pointer;' ";
			
			echo "<tr ".$selected." onclick=\"javascript:selectUnique(".$row["id"].",'".$row["rac_br"]."');\"><td>".$row["id"]."</td><td>".$row["rac_br"]."</td><td>".$datumRacuna." ".$vrijemeRacuna."</td><td>".$row["rac_iznos"]."</td><td>".$row["tvrtka"]."</td><td>".$row["kupac"]."</td></tr>";
		}
	}
?>