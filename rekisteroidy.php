<?php
// Otetaan yhteys tietokantaan.
require_once 'config.php';

if (isset($_POST['tallenna']))
{
   // Otetaan käyttäjän antamat tiedot ylös muuttujiin.
   $nimi = pg_escape_string($_POST['nimi']);
   $osoite = pg_escape_string($_POST['osoite']);
   $sposti = pg_escape_string($_POST['sposti']);
   $puh = pg_escape_string($_POST['puh']);
   $ktunnus = pg_escape_string($_POST['ktunnus']);
   $salasana1 = pg_escape_string($_POST['salasana1']);
   $salasana2 = pg_escape_string($_POST['salasana2']);

   // Tarkastetaan, että käyttäjän antamat tiedot eivät ole tyhjiä.
   $tiedot_ok = trim($nimi) != '' && trim($osoite) != '' && trim($sposti) != '' && trim($puh) != '' && trim($ktunnus) != '' && trim($salasana1) != '' && trim($salasana2) != '';

   // Jos käyttäjän antamat tiedot olivat ok, siirrytään tarkastelemaan käyttäjätunnusta ja salasanaa.
   if ($tiedot_ok)
   {
      // Tarkastetaan, onko kyseinen käyttäjätunnus jo käytössä.
      $ktunnus_ok = true;
      $tulos = pg_query("SELECT ktunnus FROM keskusdivari.kayttaja WHERE ktunnus = '$ktunnus'");
      if ($rivi = pg_fetch_row($tulos))
         $ktunnus_ok = false;
      
      // Tarkastetaan, täsmäävätkö salasanat.
      $salasanat_ok = trim($salasana1) == trim($salasana2);
      
      // Jos käyttäjätunnus ja salasana ovat ok, niin tallennetaan käyttäjän tiedot kayttaja -tauluun keskusdivarin tietokantaan, joka pitää yllä asiakasrekisteriä.
      // Sivustolle rekisteröitynyt käyttäjä on automaattisesti rooliltaan asiakas, divarien ylläpitäjät ovat valmiina tietokannassa.
      if ($ktunnus_ok && $salasanat_ok) {
         $kysely = "INSERT INTO keskusdivari.kayttaja (ktunnus, salasana, knimi, kosoite, sposti, puh) 
                    VALUES ('$ktunnus', '$salasana1', '$nimi', '$osoite', '$sposti', '$puh')";
         $paivitys = pg_query($kysely);
         
         if ($paivitys && (pg_affected_rows($paivitys) > 0)) {
            // Kun käyttäjä on lisätty tietokantaan, siirrytään sivulle kirjaudu.php
            header('Location: kirjaudu.php');
         }
         else
            $viesti = 'Jokin meni vikaan. Yritä uudestaan myöhemmin.';
      }
      // Jos käyttäjätunnuksessa tai salasanassa oli jotain vikaa, ilmoitetaan tästä käyttäjälle.
      else if (!$ktunnus_ok)
         $viesti = 'Kyseinen käyttäjätunnus on jo käytössä.';
      else if (!$salasanat_ok)
         $viesti = 'Salasanat eivät täsmänneet.';
      
   }
   // Jos jokin käyttäjän antama tieto oli tyhjä, ilmoitetaan tästä käyttäjälle.
   else {
      $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
   }
}

// Suljetaan tietokantayhteys.
pg_close($yhteys);

?>

<html lang="fi">
<head>
   <meta charset="UTF-8">
<title>Rekisteröidy</title>
<style>

@import "tyylit.css";

</style>
</head>
<body>

   
    <!-- Lomake lähetetään samalle sivulle -->
   <form action="rekisteroidy.php" method="post">
   
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
   
   <div class="box3">
   <?php if (isset($viesti)) echo '<small><small><p style="color:red">'.$viesti.'</p></small></small>'; ?>
      <p><strong>Rekisteröidy</strong></p>
	<table border="0" cellspacing="0" cellpadding="3">
	    <tr>
    	    <td>Nimi</td>
    	    <td><input type="text" name="nimi" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Osoite</td>
    	    <td><input type="text" name="osoite" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Sähköposti</td>
    	    <td><input type="text" name="sposti" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Puhelinnumero</td>
    	    <td><input type="text" name="puh" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Käyttäjätunnus</td>
    	    <td><input type="text" name="ktunnus" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Salasana</td>
    	    <td><input type="password" name="salasana1" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Vahvista salasana</td>
    	    <td><input type="password" name="salasana2" value="" /></td>
	    </tr>
	</table>
   </br>
	<input type="hidden" name="tallenna" value="jep" />
	<input type="submit" value="Luo tili" />
   <p><small><small>Onko sinulla jo tunnukset? </small></small><a href="kirjaudu.php"><small><small>Kirjaudu sisään tästä</small></small></a></p>
	</form>
   </div>
   <hr>
<p>Tiko2018 | LUO | UTA</p>
   
</body>
</html>