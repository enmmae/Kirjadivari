<?php
function r4 ($tnimi) {
   
	$hakuOsat = explode(" ", $tnimi); // Hakusanat pilkottu vertailua varten.
	
   // Otetaan yhteys tietokantaan.
   require_once 'config.php';
	
	$tulos = pg_query("SELECT tnimi FROM keskusdivari.teos");
	if (!$tulos) {
		echo "Virhe kyselyssä.\n";
		exit;
	}
   
	$tnimet = array();
   
	while ($rivi = pg_fetch_row($tulos)) {
		array_push ($tnimet,$rivi[0]);
	}
   
	$palautus = array(); // Täällä säilytetään oikeat hakutulokset.
	$palautusRank = array(); // Täällä säilytetään edellisten hakutulosten "arvot", mitä suurempi sitä parempi. Täydellinen haku on hakusanojen määrä + 1.
	
	// Uusi lista, jotta saadaan palautuksessa pidettyä nimet samassa muodossa kuin tietokannsasakin on, isot alkukirjaimet tms.
	$tnimetLow = array();
   
	// Kaikki tnimet pieniksi, jotta helpompi vertailla.
	for($i = 0; $i < count($tnimet); $i++){
		$low = strtolower($tnimet[$i]);
		array_push ($tnimetLow, $low);
	}

	// Tarkastetaan onko täydellisiä hakuja.
	for($i = 0; $i < count($tnimet); $i++){	
		if($tnimetLow[$i] == $haku ){
			array_push($palautus,$tnimet[$i]);
			array_push($palautusRank,count($hakuOsat)+1);
		}
	}
	
	// Tarkastetaan, onko osittaisia sanoja jotka osuvat täydellisesti kohdalle.
	for($i = 0; $i < count($tnimet); $i++){
		$rank = 0;
		
		for($j = 0; $j < count($hakuOsat); $j++){
			$tnimetOsat = explode(" ",$tnimetLow[$i]);
			
			for($y = 0; $y < count($tnimetOsat); $y++){
				if($hakuOsat[$j] == $tnimetOsat[$y]){
					$rank++;
				}
			}
		}
		
		if($rank > 0){
			$onOlemassa = false;
			for($j = 0; $j < count($palautus); $j++){
				if($palautus[$j] == $tnimet[$i]){
					$onOlemassa = true;
					break;
				}
			}
			if($onOlemassa == false){
				array_push($palautus,$tnimet[$i]);
				array_push($palautusRank,$rank);
			}
		}
	}
	
	// Jos edellisistä ei löydetty mitään, tarkastetaan osuuko hakusanat jonkin teoksen nimen osaan.
	if(count($palautus) == 0){
		for($i = 0; $i < count($tnimet); $i++){
			$rank = 0;
			
			for($j = 0; $j < count($hakuOsat); $j++){
				if(strpos($tnimetLow[$i],$hakuOsat[$j]) !== false){
					$rank++;
				}
			}
			
			if($rank > 0){
				array_push($palautus,$tnimet[$i]);
				array_push($palautusRank,$rank);
			}
		}
	}
	
	// Perus bubble sort jotta saadaan listat järjestykseen.
	$size = count($palautusRank)-1;
	for($i = 0; $i < $size; $i++){
		for($j = 0; $j < $size-$i; $j++){
			$k = $j+1;
			if($palautusRank[$k] < $palautusRank[$j]){
				list($palautusRank[$j], $palautusRank[$k]) = array($palautusRank[$k], $palautusRank[$j]);
				list($palautus[$j], $palautus[$k]) = array($palautus[$k], $palautus[$j]);
			}
		}
	}
   
	pg_close($yhteys);
   
   return $palautus;
}
?>