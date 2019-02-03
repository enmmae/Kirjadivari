<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8">
  <title>Päivitä tiedot</title>
      <style>
         @import "tyylit.css";
      </style>
 </head>
 
<body>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="paivitatiedot.php" method="post">
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
  
  <div class = "box2">
      <p> <strong>Päivitä tiedot</strong> </p>
   <?php if (isset($viesti)) echo '<small><small><p style="color:red">'.$viesti.'</p></small></small>'; ?>


<?php
// Aloitetaan sessio.
session_start();
 
// Jos sessiomuuttujaa ei ole alustettu, siirretään käyttäjä takaisin etusivulle.
if(!isset($_SESSION['ktunnus'])) {
  header("Location: etusivu.php");
  exit;
}
// Jos sessiomuuttujaa 'divari' ei ole asetettu, niin kyseessä on asiakas. Siirretään tällöin käyttäjä asiakkaan etusivulle.
else if (!isset($_SESSION['divari'])) {
  header("Location: asiakasetusivu.php");
  exit;
}
else {
   // Otetaan yhteys tietokantaan.
   require_once 'config.php';
   
   // Otetaan ylös käyttäjän ylläpitämän divarin tunnus.
   $divari = $_SESSION['divari'];
   
   // Alustetaan lippumuuttuja ja ilmoitetaan käyttäjälle sivun tarkoitus.
   $yksityinen_tietokanta = true;
   echo "Tällä sivulla voi tarkastella divarin teosten tietoja ja mahdollisesti siirtää näitä keskusdivarin tietokantaan. 
         Muistathan tarkistaa nidettä siirtäessä, että myös teoksen yleiset tiedot löytyvät keskusdivarin tietokannasta
         tai olet siirtämässä niitä samalla.</br>";

?>

      <form action= "http://www.sis.uta.fi/~ss424549/paivitatiedot.php">

<?php

   // Teokset.
   $tulos1 = pg_query("SELECT isbn, tnimi, tekija, tyyppi, luokka FROM $divari.teos WHERE isbn NOT IN (SELECT isbn FROM keskusdivari.teos) ORDER BY tekija");
  
   // Jos kyselyllä on tulos, niin kyseinen käyttäjä ylläpitää divaria, joka käyttää omaa tietokantaa. Tällöin voidaan tulostaa niiden teosten ja niteiden tiedot, 
   // joita on mahdollista siirtää keskusdivarin tietokantaan.
   // Muulloin voidaan tulostaa vain keskusdivarin tietokannasta teosten ja niteiden tiedot, jotka kuuluvat kyseisen käyttäjän ylläpitämälle divarille.
   if ($tulos1) {
      
      if (pg_affected_rows($tulos1) > 0)
         echo "</br><strong> Divarin $divari teosten, joita ei ole vielä siirretty keskusdivarin tietokantaan, tiedot omasta tietokannasta </strong><br/>\n";

      while ($rivi = pg_fetch_row($tulos1)) {
         $str =  "ISBN: $rivi[0] | $rivi[2], $rivi[1] | Tyyppi: $rivi[3] | Luokka: $rivi[4] </br>";
?> 

         <tr>
               <td> 
                   <input type="checkbox" name="valinta1[]" value= <?php echo $rivi[0]; ?>
               </td>
               <td><?php echo $str;?></td>
         </tr>  

<?php
      }
      
      // Niteet.
      $tulos2 = pg_query("SELECT isbn, ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, CASE WHEN tila = 0 THEN 'vapaa' WHEN tila = 1 THEN 'varattu' ELSE 'myyty' END AS tila 
                         FROM $divari.nide WHERE ntunnus NOT IN (SELECT ntunnus FROM keskusdivari.nide) ORDER BY ntunnus");
      
      if (pg_affected_rows($tulos2) > 0)
         echo "<br/><strong> Divarin $divari niteiden, joita ei vielä siirretty keskusdivarin tietokantaan, tiedot omasta tietokannasta </strong><br/>\n";

      while ($rivi = pg_fetch_row($tulos2)) {
         if (trim($rivi[4]) != '')
            $str =  "ISBN: $rivi[0] | Tunnus: $rivi[1] | Hinta: $rivi[2] € | Sisäänostohinta: $rivi[3] € | Myyntipäivämäärä: $rivi[4] | Paino: $rivi[5] g | Tila: $rivi[6] </br>";
         else 
            $str =  "ISBN: $rivi[0] | Tunnus: $rivi[1] | Hinta: $rivi[2] € | Sisäänostohinta: $rivi[3] € | Paino: $rivi[5] g | Tila: $rivi[6] </br>";
?> 

         <tr>
               <td> 
                   <input type="checkbox" name="valinta2[]" value= <?php echo $rivi[1]; ?>
               </td>
               <td><?php echo $str;?></td>
         </tr>  

<?php
      }
      echo "<br/>";
      
      if (!(pg_affected_rows($tulos1) > 0) && !(pg_affected_rows($tulos2) > 0))
         echo "Tietokannassa ei tällä hetkellä siirrettäviä tietoja.</br></br>";
      else {
?>

         <input type="submit" value="Siirrä">
         </form>
         <br>
         <br>
   
<?php
      }
      
      // Käydään läpi siirrettäväksi valittujen teosten ja niteiden tiedot.
      
      // Teokset.
      $valitut1 = $_POST['valinta1'];
      
      if(!empty($valitut1)) {
         // Otetaan ylös valittujen teosten lukumäärä.
         $N = count($valitut1);
         
         // Lisätään valittujen teosten tiedot keskusdivarin tietokantaan.
         for($i = 0; $i < $N; $i++) {
            $tulos = pg_query("SELECT * FROM $divari.teos WHERE isbn = '$valitut1[$i]'");
            while ($rivi = pg_fetch_row($tulos)) {
               // Lisätään haluttu teos keskusdivarin tietokantaan.
               $kysely = "INSERT INTO keskusdivari.teos (isbn, tnimi, tekija, tyyppi, luokka) VALUES ('$rivi[0]', '$rivi[1]', '$rivi[2]', '$rivi[3]', '$rivi[4]')";
               $paivitys = pg_query($kysely);
            }
         }
      }
      
      // Niteet.
      $valitut2 = $_POST['valinta2'];
      
      if(!empty($valitut2)) {
         // Otetaan ylös valittujen niteiden lukumäärä.
         $X = count($valitut2);
         
         // Lisätään valittujen niteiden tiedot keskusdivarin tietokantaan.
         for($i = 0; $i < $X; $i++) {
            $tulos = pg_query("SELECT * FROM $divari.nide WHERE ntunnus = $valitut2[$i]");
            while ($rivi = pg_fetch_row($tulos)) {
               // Lisätään haluttu nide keskusdivarin tietokantaan.   
               if (trim($rivi[4]) != '') {
                  $kysely = "INSERT INTO keskusdivari.nide (isbn, ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, tila, dtunnus) 
                             VALUES ('$rivi[0]', $rivi[1], $rivi[2], $rivi[3], '$rivi[4]', $rivi[5], $rivi[6], '$divari')";
               }
               else {
                  $kysely = "INSERT INTO keskusdivari.nide (isbn, ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, tila, dtunnus) 
                             VALUES ('$rivi[0]', $rivi[1], $rivi[2], $rivi[3], NULL, $rivi[5], $rivi[6], '$divari')";
               }
               $paivitys = pg_query($kysely);
            }
         }
      }
   
      // Päivitetään sivu, jos joitakin tietoja on siirretty.
      if (!empty($valitut1) || !empty($valitut2))
         Header('Location: '.$_SERVER['PHP_SELF']);
   }
   // Jos kyselylle ei ollut tulosta, on nykyinen käyttäjä sellaisen divarin ylläpitäjä, jonka divari käyttää keskusdivarin tietokantaa.
   // Muutetaan tällöin seuraavan lippumuuttujan arvo, jotta voidaan tulostaa teosten tiedot oikein myös näille käyttäjille.
   else {
      $yksityinen_tietokanta = false;
      echo "</br> Divarinne käyttää keskusdivarin tietokantaa, joten tietojen päivittäminen ei ole mahdollista - eikä tarpeellista. </br></br>";
   }
   
   // Tulostetaan divarin teosten ja niteiden tiedot keskusdivarin tietokannasta.
   
   // Teokset.
   if ($yksityinen_tietokanta) {
      $tulos = pg_query("SELECT teos.isbn, teos.tnimi, teos.tekija, teos.tyyppi, teos.luokka FROM keskusdivari.teos AS teos, $divari.teos AS teos1
                         WHERE teos.isbn = teos1.isbn ORDER BY teos.tekija");
   }
   else {
      $tulos = pg_query("SELECT DISTINCT teos.isbn, teos.tnimi, teos.tekija, tyyppi, luokka FROM keskusdivari.teos AS teos, keskusdivari.nide AS nide
                         WHERE teos.isbn = nide.isbn AND dtunnus = '$divari' ORDER BY teos.tekija");
   }
   
   echo "<strong> Divarin $divari teosten tiedot keskusdivarin tietokannasta </strong><br/>\n";
   while ($rivi = pg_fetch_row($tulos)) {
      echo  "ISBN: $rivi[0] | $rivi[2], $rivi[1] | Tyyppi: $rivi[3] | Luokka: $rivi[4] </br>";
   }
   
   echo "<br />";
   
   // Niteet.
   $tulos = pg_query("SELECT isbn, ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, CASE WHEN tila = 0 THEN 'vapaa' WHEN tila = 1 THEN 'varattu' ELSE 'myyty' END AS tila 
                      FROM keskusdivari.nide WHERE dtunnus = '$divari' ORDER BY ntunnus");
   
   echo "<strong> Divarin $divari niteiden tiedot keskusdivarin tietokannasta </strong><br/>\n";
   while ($rivi = pg_fetch_row($tulos)) {
      if (trim($rivi[4]) != '')
         echo "ISBN: $rivi[0] | Tunnus: $rivi[1] | Hinta: $rivi[2] € | Sisäänostohinta: $rivi[3] € | Myyntipäivämäärä: $rivi[4] | Paino: $rivi[5] g | Tila: $rivi[6] </br>";
      else 
         echo "ISBN: $rivi[0] | Tunnus: $rivi[1] | Hinta: $rivi[2] € | Sisäänostohinta: $rivi[3] € | Paino: $rivi[5] g | Tila: $rivi[6] </br>";
   }  
   echo "<br />\n";
   
   
   // Suljetaan tietokantayhteys.
   pg_close($yhteys);
}
?>

</div>

   <div class = "box3">
      <div class = "box4">
         <p><a href="kirjaudu_ulos.php">Kirjaudu ulos</a></p>
      </div>
   </div>
   <hr>
   <p>Tiko2018 | LUO | UTA</p>
</body>
</html>