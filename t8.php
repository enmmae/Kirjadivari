<?php
	// data.xml tiedoston nimen paikalle halutun niminen xml tiedosto, tässä luetaan siis tiedosto välittömästä hakemistosta.
   $xml=simplexml_load_file("data.xml") or die("Error: Cannot create object"); 

   // Vaihe 1. Otetaan yhteys tietokantaan.
   require_once 'config.php';
	
	// Vaihe 2. Luodaan D4 SCHEMA.
	$tulos = pg_query("CREATE SCHEMA D4");
	if (!$tulos) {
		echo "<br> SCHEMA on jo olemassa";
	}
	else{
		echo "<br> SCHEMA D4 luotu";
	}
	echo "<br>";
	// Vaihe 3. Siirrytään D4 SCHEMAAN.
	$tulos = pg_query("SET SEARCH_PATH TO D4");
	if (!$tulos) {
		echo "<br> Virhe kyselyssä.\n";
	}
	else{
		echo "<br> Siirrytään D4 SCHEMAAN";
	}
	echo "<br>";
	// Vaihe 4. Luodaan teos taulu.
	$tulos = pg_query("CREATE TABLE teos (
                      isbn VARCHAR(20),
                      tnimi VARCHAR(100) NOT NULL,
                      tekija VARCHAR(50) NOT NULL,
                      tyyppi VARCHAR(20) DEFAULT '',
                      luokka VARCHAR(20) DEFAULT '',
                      PRIMARY KEY (isbn))");
	if (!$tulos) {
		echo "<br>Teos -taulu jo olemassa.";
	}
	else{
		echo "<br> Teos -taulu luotu.";
	}
	echo "<br>";
	// Vaihe 5. Lisätään xml-tiedoston teoksen tiedot xpath kyselyn avulla uuteen D4.teos tauluun ja keskusdivari.teos tauluun
	foreach($xml->xpath('//ttiedot') as $ttiedot){
		$kysely = "INSERT INTO teos VALUES ('";
		$kysely .= $ttiedot->isbn;
		$kysely .= "', '";
		$kysely .= $ttiedot->nimi;
		$kysely .= "', '";
		$kysely .= $ttiedot->tekija;
		$kysely .= "', '', '')";
		
		$tulos = pg_query($kysely);
		if (!$tulos) {
			echo "<br> Virhe kyselyssä kun lisättiin D4.teos -tauluun.\n";
		}
		else{
			echo "<br> D4 teos -tauluun lisätty arvoja";
		}
		// Listään samaiset arvot keskusdivarin teos tauluun;
		$kysely = "INSERT INTO keskusdivari.teos VALUES ('";
		$kysely .= $ttiedot->isbn;
		$kysely .= "', '";
		$kysely .= $ttiedot->nimi;
		$kysely .= "', '";
		$kysely .= $ttiedot->tekija;
		$kysely .= "', '', '')";
		$tulos = pg_query($kysely);
		if (!$tulos) {
			echo "<br> Virhe kyselyssä, kun lisättiin keksusdivari.teos -tauluun.\n";
		}
		else{
			echo "<br> Keskusdivari teos -tauluun lisatty arvoja";
		}
      echo "<br>";
	}
	
	//Vaihe 6. Luodaan nide taulu D4 SCHEMAAN.
	$tulos = pg_query("CREATE TABLE nide (
                      isbn VARCHAR(20),
                      ntunnus INT,
                      hinta NUMERIC(5,2) NOT NULL CHECK (hinta > 0),
                      sis_osto_hinta NUMERIC(5,2) NOT NULL CHECK (sis_osto_hinta > 0),
                      myyntipvm DATE,
                      paino INT NOT NULL,
                      tila INT DEFAULT 0,
                      PRIMARY KEY (ntunnus),
                      FOREIGN KEY (isbn) REFERENCES teos)");
	if (!$tulos) {
		echo "<br>Nide -taulu jo olemassa";
	}
	else{
		echo "<br> Nide -taulu luotu";
	}
   echo "<br>";
	
	// Vaihe 7. Selvitetän mikä on suurin ntunnus keskusdivarissa, jotta saadaan kaikille uniikit tunnukset
	$tulos = pg_query("(SELECT ntunnus FROM keskusdivari.nide) UNION (SELECT ntunnus FROM D1.nide) ORDER BY ntunnus DESC");
	if (!$tulos) {
		echo "<br> Virhe kyselyssä, ei saada selville suurinta ntunnusta.\n";
		exit;
	}
	$suurinTunnus = pg_fetch_row($tulos);
	$suurin = $suurinTunnus[0] + 1;
	
	// Vaihe 8. Lisätään keskusdivarin divari-tauluun uuden divarin tiedot.
	$tulos = pg_query("INSERT INTO keskusdivari.divari VALUES ('D4', 'Book fiesta', 'Mannerheiminkatu 13, 06100 Porvoo')");
	if (!$tulos) {
		echo "<br>Divarin D4 tiedot jo lisätty.";
	}
	else{
		echo "<br> Divari luotu";
	}
	
	// Vaihe 9. Lisataan uudet niteet tauluihin.
	foreach($xml->xpath('//teos') as $teos){
		$i = 0;
		$isbn = $teos->ttiedot->isbn;
		$kysely = "INSERT INTO nide VALUES ('";
		
		while($teos->nide[$i] != null){
			$kysely = "INSERT INTO nide VALUES ('";
			$kysely .= $isbn;
			$kysely .= "', '";
			$kysely .= $suurin;
			$kysely .= "', '";
			$kysely .= $teos->nide[$i]->hinta;
			$kysely .= "', '";
			$kysely .= "1";
			$kysely .= "', NULL, '";
			$kysely .= $teos->nide[$i]->paino;
			$kysely .= "', '";
			$kysely .= "0')";
			$tulos = pg_query($kysely);
			if (!$tulos) {
				echo "<br> Virhe tai arvot ovat jo taulussa.";
			}
			else{
				echo "<br> D4 nide -tauluun lisatty arvoja.";
			}
			$kysely = "INSERT INTO keskusdivari.nide VALUES ('";
			$kysely .= $isbn;
			$kysely .= "', '";
			$kysely .= $suurin;
			$kysely .= "', '";
			$kysely .= $teos->nide[$i]->hinta;
			$kysely .= "', '";
			$kysely .= "1";
			$kysely .= "', NULL, '";
			$kysely .= $teos->nide[$i]->paino;
			$kysely .= "', '";
			$kysely .= "0', 'D4')";
			$i++;
			$suurin++;
			$tulos = pg_query($kysely);
			if (!$tulos) {
				echo "<br> Virhe tai arvot ovat jo taulussa kun keskusdivariin lisättiin.";
			}
			else{
				echo "<br> Keskusdivarin nide -tauluun lisatty arvoja.";
			}
			
		}		
	}
	pg_close($yhteys);
?>