<? include "includes/settings.php"; ?>
<? include "includes/class.racuni.php"; ?>
<?

	session_start();

	$web = new site_defaults;
	$web->DBConnect();
	mysqli_query($_SESSION["con"],"SET CHARACTER SET utf8");
	mysqli_query($_SESSION["con"],"SET NAMES 'utf8'");

	if ($_POST["vrsta"]=="I"){
		$query = "select count(*) as rows from racuni where rac_br='".$_POST["rac_br"]."' and vrsta='I' and rac_tvrtka = ".$_POST["rac_tvrtka"]." and year(rac_datum) = ".$_SESSION["_user"]["year"];
		$result = mysqli_query($_SESSION["con"],$query);
		$row = mysqli_fetch_assoc($result);
		echo $row["rows"];
	} else {
		$query = "select count(*) as rows from racuni where rac_br='".$_POST["rac_br"]."' and vrsta='U' and rac_dobavljac = ".$_POST["rac_dobavljac"]." and year(rac_datum) = 2017".$_SESSION["_user"]["year"];
		$result = mysqli_query($_SESSION["con"],$query);
		$row = mysqli_fetch_assoc($result);
		echo $row["rows"];
	}
		
?>
