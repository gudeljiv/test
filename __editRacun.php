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

	$racun = new racun;
	// echo $_POST["id"];
	$arrs = $racun->getRacunInfo($_POST["id"]); 
	
	//echo "<script>window.location = 'index.php?page=projekti&parentID=".$_POST["parentID"]."';</script>";
	$t = explode (" ",$arrs[0]["rac_datum"]);
	$tempD = explode ("-",$t[0]);
	$datum = $tempD[2].".".$tempD[1].".".$tempD[0];
	$vrijeme = $t[1];
	
	$t = explode (" ",$arrs[0]["datum_uplate"]);
	$tempD = explode ("-",$t[0]);
	$datumUplate = $tempD[2].".".$tempD[1].".".$tempD[0];
	$vrijemeUplate = $t[1];
	
	$t = explode (" ",$arrs[0]["datum_placanja"]);
	$tempD = explode ("-",$t[0]);
	$datumPlacanja = $tempD[2].".".$tempD[1].".".$tempD[0];
	$vrijemePlacanja = $t[1];
?>
<script>
	$("#storno").live('click',function(event){
		event.stopPropagation();
		var selID = $("#storno").attr('item-id');
		$.ajax({
			url: "__stornoRacun.php",
			type: "POST",
			data: { id:selID,page:"<?=$_POST["page"]?>",vrsta:"<?=$_POST["vrsta"]?>" },
			success: function(msg) {                    
				$('#appendDiv').html(msg);
				closePopup();
			}
		});
	});
   
	$(document).ready(function() {
		calculateBrutto("<?=$arrs[0]["vrsta"]?>");
		checkBruttoInput("<?=$arrs[0]["vrsta"]?>");
		
		$("#rac_brizlaznog_vezno").focus(function() {
			$("#uniqueIzlazniVezni").fadeIn();
			fillCheckIzlazniUnique(this.value);
		});
		
		$("#rac_brizlaznog_vezno").focusout(function() {
			$("#uniqueIzlazniVezni").fadeOut();
		});
		
		$("#rac_brizlaznog_vezno").keyup(function() {
			var input = this.value;
			$.ajax({
				url: "__checkIzlazniUnique.php",
				type: "POST",
				data: { brojRacunaCheckUnique:input,type:"check" },
				success: function(msg) {                    
					console.log(msg);
					if(msg>0){
						fillCheckIzlazniUnique(input);
					} else {
						$("#uniqueIzlazniVezni p").html("Ne postoji racun sa tim brojem u bazi!");
						$("#rac_brizlaznog_ID").val("");
					}
				}
			});
		});
		
		function fillCheckIzlazniUnique(input) {
			var selected = $("#rac_brizlaznog_ID").val();
			console.log(selected);
			$.ajax({
				url: "__checkIzlazniUnique.php",
				type: "POST",
				data: { brojRacunaCheckUnique:input,type:"getAll",selected:selected },
				success: function(msg) {                    
					console.log(msg);
					$("#uniqueIzlazniVezni p").html("<table class='uniqueIzlazniVezni'><tr>" + msg + "</tr></table>");
				}
			});
		}
		
		

		$(".valutaU").change(function() {
			console.log("catch");
			$.ajax({
				url: "__valuta.php",
				type: "POST",
				data: { valuta:$(".valutaU").val() },
				success: function(msg) {                    
					$(".tecajU").val(msg);
				},
				error: function (request, status, error) {
					alert(request.responseText);
				}
			});
		});
		
		$(".valutaI").change(function() {
			console.log("catch");
			$.ajax({
				url: "__valuta.php",
				type: "POST",
				data: { valuta:$(".valutaI").val() },
				success: function(msg) {                    
					$(".tecajI").val(msg);
				},
				error: function (request, status, error) {
					alert(request.responseText);
				}
			});
		});

		
	});
</script>

<div id="uniqueIzlazniVezni">
	<span>
		<p></p>
	</span>
</div>


<? if($arrs[0]["vrsta"]=="U"){ ?>
		<form name="editProjectFormU" id="editProjectFormI" action="<?=$PHP_SELF?>" method="POST" autocomplete="off">
			<input type="hidden" name="editR" value="1">
			<input type="hidden" name="id" value="<?=$arrs[0]["id"]?>">
			<input type="hidden" name="vrsta" value="<?=$arrs[0]["vrsta"]?>">
			<input type="hidden" name="rac_tecaj" value="<?=$arrs[0]["rac_tecaj"]?>">
			<input type="hidden" name="rac_valuta" value="<?=$arrs[0]["rac_valuta"]?>">
			<input type="hidden" name="page" value="<?=$_POST["page"]?>">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>UFA</td>
					<td colspan="5"><input type="text" name="ufa" value="<?=$arrs[0]["ufa"]?>" class="input style customW"></td>
				</tr>
				<tr>
					<td>Broj računa</td>
					<td><input type="text" name="rac_br" value="<?=$arrs[0]["rac_br"]?>" class="input style customW"></td>
					<td>Valuta</td>
					<td>
						<select name="rac_valuta" class="select style customW valutaU" style="width: 83px;">
							<?
								$query = "select * from valute order by id asc";
								$result = mysqli_query($_SESSION["con"],$query);
								while ($row = mysqli_fetch_array($result)){
									if($row["valuta"] == $arrs[0]["rac_valuta"]) $selected = "selected"; else $selected = "";
									echo '<option value="'.$row["valuta"].'" '.$selected.'>'.$row["valuta"].'</option>';
								}
							?>
						</select>
						<div style="display: inline-block; margin-left: 50px;">Kom.: <input type="text" name="rac_kom" id="rac_komU" onkeyup="calculateBrutto('U')" class="input style customW" style="width: 23px;" value="<?=$arrs[0]["rac_kom"]?>"></div>
					</td>
					<td>Netto iznos<br><span style="font-size: 8px;">(ex. 10000,00)</span></td>
					<td><input type="text" name="rac_iznos" id="rac_iznosU" onkeyup="javascript: calculateBrutto('U')" value="<?=str_replace(".",",",$arrs[0]["rac_iznos"])?>" class="input style customW" style="width: 83px" value="0">&nbsp;&nbsp;<input type="text" readonly name="rac_iznos_bruttoU" id="rac_iznos_bruttoU" class="input style customW" style="width: 83px;" value="0"></td>
				</tr>
				<tr>
					<td>Broj izlaznog računa</td>
					<td>
						<input type="hidden" name="rac_brizlaznogID" value="<?=$arrs[0]["rac_brizlaznogID"]?>" id="rac_brizlaznog_ID">
						<input type="text" name="rac_brizlaznog" value="<?=$arrs[0]["rac_brizlaznog"]?>" class="input style customW" id="rac_brizlaznog_vezno">
					</td>
					<td>Tečaj</td>
					<td><input type="text" name="rac_tecaj" value="<?=$arrs[0]["rac_tecaj"]?>" class="input style customW tecajU"></td>
					<td>Neoporezivi iznos<br><span style="font-size: 8px;">(ex. 10000,00)</span></td>
					<td><input type="text" name="rac_neoporeziviiznos" id="rac_neoporeziviiznosU" onkeyup="javascript: calculateBrutto('U')" value="<?=str_replace(".",",",$arrs[0]["rac_neoporeziviiznos"])?>" class="input style customW" value="0"></td>
				</tr>
				<tr>
					<td>Projekt</td>
					<td>
						<select name="rac_projekt" class="select style customW">
							<? 
								$project = new project;
								$project->getProjectTree(0,$arrs[0]["rac_projekt"]);
							?>
						</select>
					</td>
					<td>Moja tvrtka</td>
					<td>
						<select name="rac_mojatvrtka" class="select style customW">
							<? 
								$mojetvrtke = new mojetvrtke;
								$mojetvrtke->getMojeTvrtkeList($arrs[0]["rac_tvrtka"]); 
							?>
						</select>
					</td>
					<td>PDV <?=$arrs[0]["rac_pdv"]?></td>
					<td>
						<select name="rac_pdv" class="select style customW" id="rac_pdvU" onchange="javascript: calculateBrutto('U');" onkeyup="javascript: calculateBrutto('U')">
							<option value="0" <? if($arrs[0]["rac_pdv"] == "0") echo "selected"; ?>>0%</option>
							<option value="10" <? if($arrs[0]["rac_pdv"] == "10") echo "selected"; ?>>10%</option>
							<option value="13" <? if($arrs[0]["rac_pdv"] == "13") echo "selected"; ?>>13%</option>
							<option value="15" <? if($arrs[0]["rac_pdv"] == "15") echo "selected"; ?>>15%</option>
							<option value="17" <? if($arrs[0]["rac_pdv"] == "17") echo "selected"; ?>>17%</option>
							<option value="20" <? if($arrs[0]["rac_pdv"] == "20") echo "selected"; ?>>20%</option>
							<option value="25" <? if($arrs[0]["rac_pdv"] == "25") echo "selected"; ?>>25%</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Dobavljač</td>
					<td>
						<select name="rac_dobavljac" class="select style customW">
							<? 
								$dobavljac = new dobavljac;
								$dobavljac->getDobavljacList($arrs[0]["rac_dobavljac"]); 
							?>
						</select>
					</td>
					<td>Datum računa<br><span style="font-size: 8px;">(ex. 15.10.2013 14:05:15)</span></td>
					<td><input type="text" name="rac_datum" id="rac_datumU" onkeyup="checkDatumRacuna('U')" value="<?=$datum?> <?=$vrijeme?>" class="input style customW"></td>
					<td>Uplaćeni brutto iznos<br><span style="font-size: 8px;">(ex. 10000,00)</span></td>
					<td><input type="text" name="rac_uplaceniiznos" id="rac_uplaceniiznosU" onkeyup="checkBruttoInput('U')" value="<?=str_replace(".",",",$arrs[0]["rac_uplaceniiznos"])?>" class="input style customW" value="0"></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>Datum uplate<br><span style="font-size: 8px;">(ex. 15.10.2013 14:05:15)</span></td>
					<td><input type="text" name="datum_uplate" value="<?=$datumUplate?> <?=$vrijemeUplate?>" class="input style customW"></td>
					<td>Broj izvoda</td>
					<td><input type="text" name="broj_izvoda" value="<?=$arrs[0]["broj_izvoda"]?>" class="input style customW"></td>
				</tr>
				<tr>
					<td>Opis računa:</td>
					<td colspan="5"><input type="text" name="rac_opis" class="input style customW" style="width: 865px;" value="<?=$arrs[0]["opis"]?>"></td>
				</tr>
				<tr>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
				</tr>
				</tr>
					<td align="right" colspan="6">
						<input type='button' class='button green right' id='submit' value='Odustani' onClick='javascript: closePopup();'>
						<button type="submit" class='button green right' id="submitU">Promijeni</button>
						<button type="submit" onClick="return check_form(editProjectFormU);" class='button green right' id="copyU">Copy</button>
					</td>
				</tr>
			</table>
		</form>
<? } else { ?>
	<script>
		$( "#copyI" ).click(function() {
			$("#copyHidden").val("copy");
		});
	</script>
		<form name="editProjectFormI" id="editProjectFormI" action="<?=$PHP_SELF?>" method="POST" autocomplete="off">
			<input type="hidden" name="editR" value="1">
			<input type="hidden" name="rac_brizlaznogID" id="rac_brizlaznogID" value="0">
			<input type="hidden" name="copyHidden" id="copyHidden" value="">
			<input type="hidden" name="id" value="<?=$arrs[0]["id"]?>">
			<input type="hidden" name="vrsta" value="<?=$arrs[0]["vrsta"]?>">
			<input type="hidden" name="page" value="<?=$_POST["page"]?>">
			<? if ($arrs[0]["vrsta_izlaznog"]!="RN") { ?>
				<input type="hidden" name="racun_history" value="<?=$arrs[0]["id"]?>">
				<input type="hidden" name="racun_history_vrsta" value="<?=$arrs[0]["vrsta_izlaznog"]?>">
			<? } ?>
			<? if ($arrs[0]["storno"]=="S") { ?><input type="hidden" name="storno" value="S"><? } ?>
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td>Broj izlaznog računa</td>
					<td><input type="text" name="rac_br" class="input style customW" value="<?=$arrs[0]["rac_br"]?>"></td>
					<td>Moja tvrtka</td>
					<td>
						<select name="rac_mojatvrtka" class="select style customW">
							<? 
								$mojetvrtke = new mojetvrtke;
								$mojetvrtke->getMojeTvrtkeList($arrs[0]["rac_tvrtka"]); 
							?>
						</select>
					</td>
					<td>Kupac</td>
					<td>
						<select name="rac_kupac" class="select style customW">
							<? 
								$kupac = new kupac;
								$kupac->getKupacList($arrs[0]["rac_kupac"]); 
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Vrsta izlaznog računa</td>
					<td>
						<select name="vrsta_izlaznog" class="select style customW">
							<option value="RN" <? if($arrs[0]["vrsta_izlaznog"] == "RN") echo "selected"; ?> <? if ($arrs[0]["storno"]=="S") { echo "disabled"; } ?>>Račun</option>
							<option value="RZPP" <? if($arrs[0]["vrsta_izlaznog"] == "RZPP") echo "selected"; ?>>Račun za primljeni predujam</option>
							<option value="PRN" <? if($arrs[0]["vrsta_izlaznog"] == "PRN") echo "selected"; ?> <? if ($arrs[0]["storno"]=="S") { echo "disabled"; } ?>>Predračun</option>
						</select>
					</td>
					<td>Datum računa<br><span style="font-size: 8px;">(ex. 15.10.2013.)</span></td>
					<td><input type="text" name="rac_datum" id="rac_datumI" onkeyup="checkDatumRacuna('I')" class="input style customW" value="<?=$datum?> <?=$vrijeme?>"></td>
					<td>Projekt</td>
					<td>
						<select name="rac_projekt" class="select style customW">
							<? 
								$project = new project;
								$project->getProjectTree(0,$arrs[0]["rac_projekt"]);
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						<table style="margin: 2px 0px 2px 0px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="120">Opis računa:</td>
								<td><input type="text" name="rac_opis" class="input style customW" style="width: 380px; margin: 0px 10px 0px 0px;" value="<?=$arrs[0]["opis"]?>"></td>
								<td>Kompenzacija:</td>
								<td><input type="text" name="kompenzacija" class="input style customW" style="width: 100px; margin: 0px 10px 0px 10px;" value="<?=$arrs[0]["kompenzacija"]?>"></td>
								<td width="50">Valuta:</td>
								<td>
									<select name="rac_valuta" class="select style customW valutaI" onchange="changeTecajValue()" style="width: 70px; margin: 0px 10px 0px 0px;">
										<?
											$query = "select * from valute order by id asc";
											$result = mysqli_query($_SESSION["con"],$query);
											while ($row = mysqli_fetch_array($result)){
												if($row["valuta"] == $arrs[0]["rac_valuta"]) $selected = "selected"; else $selected = "";
												echo '<option value="'.$row["valuta"].'" '.$selected.'>'.$row["valuta"].'</option>';
											}
										?>
									</select>
								</td>
								<td width="50">Tečaj:</td>
								<td><input type="text" name="rac_tecaj" class="input style customW tecajI" value="<?=$arrs[0]["rac_tecaj"]?>" style="width: 70px; margin: 0px 10px 0px 0px;"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">
<?
	$i=0; $j=0;
	$query = "select * from racuni_stavke where racun=".$arrs[0]["id"]." order by id";
	$result = mysqli_query($_SESSION["con"],$query);
	while ($row = mysqli_fetch_array($result)){
	$i++;
?>
						<table style="margin: 2px 0px 2px 0px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td nowrap style="width: 70px;">Stavka <?=$i?>:</td>
								<td><input type="text" name="rac_stavka_opis[<?=$i?>]" value='<?=$row["opis"]?>' class="input style customW" style="width: 326px; margin: 0px 10px 0px 0px;"></td>
								<td>PDV:</td>
								<td>
									<select name="rac_stavka_pdv[<?=$i?>]" id="rac_stavka_pdvI<?=$i?>" onkeyup="calculateBrutto('I')" onchange="calculateBrutto('I')" class="select style customW" style="width: 65px; margin: 0px 10px 0px 10px;">
										<option value="0" <? if($row["pdv"] == "0") echo "selected"; ?>>0%</option>
										<option value="10" <? if($row["pdv"] == "10") echo "selected"; ?>>10%</option>
										<option value="13" <? if($row["pdv"] == "13") echo "selected"; ?>>13%</option>
										<option value="15" <? if($row["pdv"] == "15") echo "selected"; ?>>15%</option>
										<option value="25" <? if($row["pdv"] == "25") echo "selected"; ?>>25%</option>
									</select>
								</td>
								<td>Kom.:</td>
								<td><input type="text" name="rac_stavka_kom[<?=$i?>]" id="rac_stavka_komI<?=$i?>" onkeyup="calculateBrutto('I')" class="input style customW" style="width: 23px; margin: 0px 10px 0px 10px; font-size: 11px;" value="<?=$row["kom"]?>"></td>
								<td>Iznos:</td>
								<td><input type="text" name="rac_stavka_iznos[<?=$i?>]" id="rac_stavka_iznosI<?=$i?>" onkeyup="calculateBrutto('I')" value="<?=str_replace(".",",",$row["iznos"])?>" class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0"></td>
								<td>Neoporezivo:</td>
								<td><input type="text" name="rac_stavka_neoporeziviiznos[<?=$i?>]" id="rac_stavka_neoporeziviiznosI<?=$i?>" onkeyup="calculateBrutto('I')" value="<?=str_replace(".",",",$row["neoporeziviiznos"])?>" class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0"></td>
								<td>Brutto:</td>
								<td><input type="text" name="rac_stavka_brutto[<?=$i?>]" id="rac_stavka_bruttoI<?=$i?>" readonly class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0,00"></td>
							</tr>
						</table>
<?
	}
	for ($k = $i+1; $k <= 8; $k++) { 
?>
						<table style="margin: 2px 0px 2px 0px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td nowrap style="width: 70px;">Stavka <?=$k?>:</td>
								<td><input type="text" name="rac_stavka_opis[<?=$k?>]" value='<?=$row["opis"]?>' class="input style customW" style="width: 326px; margin: 0px 10px 0px 0px;"></td>
								<td>PDV:</td>
								<td>
									<select name="rac_stavka_pdv[<?=$k?>]" id="rac_stavka_pdvI<?=$k?>" onkeyup="calculateBrutto('I')" onchange="calculateBrutto('I')"  class="select style customW" style="width: 65px; margin: 0px 10px 0px 10px;">
										<option value="0" <? if($row["pdv"] == "0") echo "selected"; ?>>0%</option>
										<option value="10" <? if($row["pdv"] == "10") echo "selected"; ?>>10%</option>
										<option value="13" <? if($row["pdv"] == "13") echo "selected"; ?>>13%</option>
										<option value="15" <? if($row["pdv"] == "15") echo "selected"; ?>>15%</option>
										<option value="25" <? if($row["pdv"] == "25") echo "selected"; ?>>25%</option>
									</select>
								</td>
								<td>Kom.:</td>
								<td><input type="text" name="rac_stavka_kom[<?=$k?>]" id="rac_stavka_komI<?=$k?>" onkeyup="calculateBrutto('I')" class="input style customW" style="width: 23px; margin: 0px 10px 0px 10px; font-size: 11px;" value="1"></td>
								<td>Iznos:</td>
								<td><input type="text" name="rac_stavka_iznos[<?=$k?>]" id="rac_stavka_iznosI<?=$k?>" onkeyup="calculateBrutto('I')" value="<?=str_replace(".",",",$row["iznos"])?>" class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0"></td>
								<td>Neoporezivo:</td>
								<td><input type="text" name="rac_stavka_neoporeziviiznos[<?=$k?>]" id="rac_stavka_neoporeziviiznosI<?=$k?>" onkeyup="calculateBrutto('I')" value="<?=str_replace(".",",",$row["neoporeziviiznos"])?>" class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0"></td>
								<td>Brutto:</td>
								<td><input type="text" name="rac_stavka_brutto[<?=$i?>]" id="rac_stavka_bruttoI<?=$k?>" readonly class="input style customW" style="width: 50px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0,00"></td>
							</tr>
						</table>
<? } ?>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						<hr>
						<table style="margin: 2px 0px 2px 0px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 368px;">&nbsp;</td>
								<td>Datum plaćanja</span></td>
								<td><input type="text" name="datum_placanja" class="input style customW" style="width: 100px; margin: 0px 10px 0px 10px; font-size: 11px;" value="<?=$datumPlacanja?> <?=$vrijemePlacanja?>"></td>
								<td style="width: 29px;">Iznos: </span></td>
								<td><input type="text" name="rac_iznos" id="rac_iznos_nettoI" class="input style customW" style="width: 65px; margin: 0px 10px 0px 10px; font-size: 11px;" value="<?=str_replace(".",",",$arrs[0]["rac_iznos"])?>" readonly></td>
								<td style="width: 64px;">Neoporezivo: </span></td>
								<td><input type="text" name="rac_neoporeziviiznos" id="rac_iznos_neoporezivoI" class="input style customW" style="width: 65px; margin: 0px 10px 0px 10px; font-size: 11px;" value="<?=str_replace(".",",",$arrs[0]["rac_neoporeziviiznos"])?>" readonly></td>
								<td style="width: 32px;">Brutto: </span></td>
								<td><input type="text" name="rac_iznos" id="rac_iznos_bruttoI" class="input style customW" style="width: 65px; margin: 0px 10px 0px 10px; font-size: 11px;" value="0,00" readonly></td>
							</tr>
						</table>
						<hr>
					</td>
				</tr>
				<tr>
					<td>Datum uplate<br><span style="font-size: 8px;">(ex. 15.10.2013.)</span></td>
					<td><input type="text" name="datum_uplate" class="input style customW" value="<?=$datumUplate?> <?=$vrijemeUplate?>"></td>
					<td>Uplaćeni iznos<br><span style="font-size: 8px;">(ex. 10000,00)</span></td>
					<td><input type="text" name="rac_uplaceniiznos" id="rac_uplaceniiznosI" onkeyup="checkBruttoInput('I')" class="input style customW" value="<?=str_replace(".",",",$arrs[0]["rac_uplaceniiznos"])?>"></td>
					<td>Broj izvoda</td>
					<td><input type="text" name="broj_izvoda" class="input style customW" value="<?=$arrs[0]["broj_izvoda"]?>"></td>
				</tr>
				<tr>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
					<td><img src="images/dummy.gif" width="120" height="1"></td>
					<td><img src="images/dummy.gif" width="216" height="1"></td>
				</tr>
				<tr>
					<td align="right" colspan="6">
						<input type='button' class='button green right' value='Odustani' onClick='javascript: closePopup();'>
						<button type="submit" class='button green right' id="submitI">Promijeni</button>
						<? if ($arrs[0]["vrsta_izlaznog"] != "RZPP") { ?>
						<button type="submit" onClick="return check_form(editProjectFormI);" class='button green right' id="copyI">Copy</button>
						<? } ?>
						<!--
						<? if ($arrs[0]["vrsta_izlaznog"] == "RZPP" && $arrs[0]["storno"]!="S") { ?>
						<button type="submit" onClick="return false;" id="storno" item-id="<?=$arrs[0]["id"]?>" class='button green right'>STORNO</button>
						<? } ?>
						-->
						<?
							if ($arrs[0]["vrsta_izlaznog"] == "PRN") {
								$query = "select * from racuni where racun_history = ".$arrs[0]["id"];
								$result = mysqli_query($_SESSION["con"],$query);
								if(!mysqli_num_rows($result)) {
						?>
									<button type="submit" onClick="return check_form(editProjectFormI);" class='button green right' id="rzppI">Pretvori u RAČUN ZA PRIMLJENI PREDUJAM</button>
						<?
								} else {
						?>
									<button type="submit" onClick="return false;" class='button blue right'>Ovaj je predračun već pretvoren u RZPP</button>
						<?
								}
							}

							if ($arrs[0]["vrsta_izlaznog"] == "RZPP") {
								$query = "select * from racuni where racun_history = ".$arrs[0]["id"];
								$result = mysqli_query($_SESSION["con"],$query);
								if(!mysqli_num_rows($result)) {
						?>
									<button type="submit" onClick="return check_form(editProjectFormI);" class='button green right' id="racunI">Pretvori u RAČUN</button>
						<?
								} else {
						?>
									<button type="submit" onClick="return false;" class='button blue right'>Ovaj je RZPP već pretvoren u RN</button>
						<?
								}
							}
						?>
						<input type='button' class='button green right' id="printI" value='PRINT' onClick='print(<?=$arrs[0]["id"]?>);'>
					</td>
				</tr>
			</table>
		</form>
<? } ?>