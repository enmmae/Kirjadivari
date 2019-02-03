<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8">
  <title>Ostoskori</title>
      <style>
         @import "tyylit.css";
      </style>
</head>
<body>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="ostoskori.php" method="post">
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
  
   <div class = "box2">
      <p> <strong>Ostoskorisi</strong> </p>
   

<?php
// Aloitetaan sessio.
session_start();
 
// Jos sessiomuuttujaa ei ole alustettu, siirretään käyttäjä takaisin etusivulle.
if(!isset($_SESSION['ktunnus'])){
  header("Location: etusivu.php");
  exit;
}
else {
   // Otetaan yhteys tietokantaan.
   require_once 'config.php';
   
   // Ilmoitetaan käyttäjälle, jos joidenkin teosten varaaminen ei onnistunut - eli jos joku toinen käyttäjä oli ehtinyt varata teoksen käyttäjän ollessa tarkastelemassa hakutuloksia.
   if (isset($_SESSION['lippu'])) {
      echo "Joidenkin teosten varaaminen ei onnistunut.<br/><br/>";
      unset($_SESSION['lippu']);
   }
   
   // Otetaan käyttäjän käyttäjätunnus ylös.
   $ktunnus = $_SESSION['ktunnus'];
   
?>
     
      <form action= "http://www.sis.uta.fi/~ss424549/ostoskori.php">
      
<?php

   // Tulostetaan näytölle kyseisen käyttäjän varaamat teokset.
   $tulos = pg_query("SELECT tnimi, tekija, hinta, dnimi, paino, nide.ntunnus 
                      FROM keskusdivari.nide AS nide, keskusdivari.tilaus AS tilaus, keskusdivari.divari AS divari, keskusdivari.teos AS teos
                      WHERE nide.ntunnus = tilaus.ntunnus AND nide.dtunnus = divari.dtunnus AND nide.isbn = teos.isbn AND tilaus.ktunnus = '$ktunnus' AND tilaus.tila = 0");
   
   // Jos kyselyllä on tulos, niin käyttäjällä on ostoskorissaan jotain. Tulostetaan tällöin tilauksen tiedot käyttäjälle.
   if ($tulos && (pg_affected_rows($tulos) > 0)) {
      
      // Lasketaan tilauksen teosten kokonaispaino ja kokonaishinta.
      $tiedot = pg_query("SELECT SUM(paino), SUM(hinta) FROM keskusdivari.tilaus AS t, keskusdivari.nide AS n 
                          WHERE t.ntunnus = n.ntunnus AND t.ktunnus = '$ktunnus' AND t.tila = 0");
                          
      while ($rivi = pg_fetch_row($tiedot)) {
         $kokonaispaino = $rivi[0];
         $kokonaishinta = $rivi[1];
      }
      
      // Ilmoitetaan käyttäjälle, jos tilaus joudutaan jakamaan osiin. Tällöin tulostetaan tilauksen jokainen osa ja tämän postikulut erikseen käyttäjälle.
      // Muuten tulostetaan kaikkien tuotteiden tiedot kerralla, joiden jälkeen postikulut, kokonaishinta ja nämä yhteensä.
      if ($kokonaispaino > 2000)
         echo "<br />\nTilauksen kokonaispaino ylittää 2000g rajan, joten se joudutaan jakamaan osiin seuraavasti:<br/><br/>";
      
      $paino = 0;
      $postimaksu = 0;
      $postimaksut = 0;

      while ($rivi = pg_fetch_row($tulos)) {
         if ($paino + $rivi[4] > 2000) {
            $postimaksu = lahetyskulut($paino);
            $postimaksut += $postimaksu;
            echo "Lähetyskulut: $postimaksu € <br/><br/>";
            $paino = 0;
         }
         if ($paino + $rivi[4] <= 2000) {
            $paino += $rivi[4];
         }
         
         $str = "$rivi[1], $rivi[0] | Hinta: $rivi[2] € | Myyjä: $rivi[3] <br/>";

?> 
            <tr>
               <td> 
                   <input type="checkbox" name="valinta[]" value= <?php echo $rivi[5]; ?>
               </td>
               <td><?php echo $str;?></td>
           </tr>  

<?php
         
      }
      
      $postimaksu = lahetyskulut($paino);
      $postimaksut += $postimaksu;
      echo "Lähetyskulut: $postimaksu € <br/><br/>";
      
      // Tulostetaan tuotteiden kokonaishinta, mahdollinen eri postimaksujen kokonaishinta ja tilauksen kokonaishinta.
      echo "Tuotteet yhteensä: $kokonaishinta € <br/>";
      if ($kokonaispaino > 2000)
         echo "Postimaksut yhteensä: $postimaksut € <br/>";
      $yhteensa = $kokonaishinta + $postimaksut;
      echo "Tilauksen kokonaishinta: $yhteensa € <br/>";
      
      
?>   
   
      <br>      
      <input type="hidden" name= "poista" value="jep" />
      <input type="submit" name = "poista" value="Poista valitut">
      
      
      <input type="hidden" name = "hylkaa" value="jep" />
      <input type="submit" name = "hylkaa" value="Hylkää tilaus" />
      
      <input type="hidden" name= "maksa" value="jep" />
      <input type="submit" name = "maksa" value="Maksa tilaus" />
      
      </form>
   
<?php
      
      // Jos "Poista valitut" niin muutetaan valittujen niteiden tila vapaaksi ja poistetaan kyseiset rivit tilaus -taulusta.
      if ($_POST['poista'] == 'Poista valitut' ) {
         $valitut = $_POST['valinta'];
         
         if(!empty($valitut)) {
            // Otetaan ylös valittujen teosten lukumäärä.
            $N = count($valitut);
            
            for($i = 0; $i < $N; $i++) {
               // Päivitetään keskusdivarin tietokantaan valitun niteen tila vapaaksi.
               $tulos = pg_query("UPDATE keskusdivari.nide SET tila = 0 WHERE ntunnus = $valitut[$i]");
               
               // Otetaan ylös kyseisen niteen omistaja divarin tunnus.
               $tulos = pg_query("SELECT dtunnus FROM keskusdivari.nide WHERE ntunnus = $valitut[$i]");
               while ($rivi = pg_fetch_row($tulos))
                  $tietokanta =  "$rivi[0]";
               
               // Jos tällä divarin tunnuksella löytyy oma tietokanta, niin päivitetään myös sinne valitun niteen tila vapaaksi.
               $tulos = pg_query("SELECT tnimi FROM $tietokanta.teos");
               if ($tulos)
                  pg_query("UPDATE $tietokanta.nide SET tila = 0 WHERE ntunnus = $valitut[$i]");
               
               // Poistetaan rivi tilaus -taulusta.
               pg_query("DELETE FROM keskusdivari.tilaus WHERE ntunnus = $valitut[$i]");            
            }
         }
         Header('Location: '.$_SERVER['PHP_SELF']);
      }
      
      // Jos "Hylkää tilaus" niin muutetaan kaikkien niteiden tila vapaaksi ja poistetaan kaikki kyseiset rivit tilaus -taulusta.
      else if ($_POST['hylkaa'] == 'Hylkää tilaus' ) {
         
         // Otetaan kyselyllä ylös käyttäjän tilaamien niteiden tunnukset.
         $tulos = pg_query("SELECT ntunnus FROM keskusdivari.tilaus WHERE ktunnus = '$ktunnus' AND tila = 0");
         
         while ($rivi = pg_fetch_row($tulos)) {
            // Päivitetään keskusdivarin tietokantaan niteen tila vapaaksi.
            pg_query("UPDATE keskusdivari.nide SET tila = 0 WHERE ntunnus = $rivi[0]");
            
            // Otetaan ylös kyseisen niteen omistaja divarin tunnus.
            $t1 = pg_query("SELECT dtunnus FROM keskusdivari.nide WHERE ntunnus = $rivi[0]");
            while ($r = pg_fetch_row($t1))
               $tietokanta =  "$r[0]";
            
            // Jos tällä divarin tunnuksella löytyy oma tietokanta, niin päivitetään myös sinne niteen tila vapaaksi.
            $t2 = pg_query("SELECT tnimi FROM $tietokanta.teos");
            if ($t2)
               pg_query("UPDATE $tietokanta.nide SET tila = 0 WHERE ntunnus = $rivi[0]");
         }
         
         // Poistetaan käyttäjän tilaukset.
         pg_query("DELETE FROM keskusdivari.tilaus WHERE ktunnus = '$ktunnus' AND tila = 0"); 
         
         Header('Location: '.$_SERVER['PHP_SELF']);
      }
      
      // Jos "Maksa tilaus" niin muutetaan kaikkien niteiden tila myydyksi, lisätään myyntipäivämäärät niteisiin ja muutetaan tilauksen tila lähetetyksi.
      else if ($_POST['maksa'] == 'Maksa tilaus') {
         
         // Otetaan kyselyllä ylös käyttäjän tilaamien niteiden tunnukset.
         $tulos = pg_query("SELECT ntunnus FROM keskusdivari.tilaus WHERE ktunnus = '$ktunnus' AND tila = 0");
         
         while ($rivi = pg_fetch_row($tulos)) {
            // Päivitetään keskusdivarin tietokantaan niteen tila myydyksi ja lisätään tälle myyntipäivämäärät.
            pg_query("UPDATE keskusdivari.nide SET tila = 2 WHERE ntunnus = $rivi[0]");
            pg_query("UPDATE keskusdivari.nide SET myyntipvm = CURRENT_DATE WHERE ntunnus = $rivi[0]");
            
            // Otetaan ylös kyseisen niteen omistaja divarin tunnus.
            $t1 = pg_query("SELECT dtunnus FROM keskusdivari.nide WHERE ntunnus = $rivi[0]");
            while ($r = pg_fetch_row($t1))
               $tietokanta =  "$r[0]";
            
            // Jos tällä divarin tunnuksella löytyy oma tietokanta, niin päivitetään myös sinne niteen tila myydyksi ja lisätään tälle myyntipäivämäärä.
            $t2 = pg_query("SELECT tnimi FROM $tietokanta.teos");
            if ($t2) {
               pg_query("UPDATE $tietokanta.nide SET tila = 2 WHERE ntunnus = $rivi[0]");
               pg_query("UPDATE $tietokanta.nide SET myyntipvm = CURRENT_DATE WHERE ntunnus = $rivi[0]");
            }
         }
         
         // Päivitetään tilaus -tauluun käyttäjän tilaukset maksetuiksi.
         pg_query("UPDATE keskusdivari.tilaus SET tila = 1 WHERE ktunnus = '$ktunnus' AND tila = 0");
         
         header('Location: maksettu.php');
      }
      
      // Ostoskori ei ikinä tyhjennä tuotteita automaattisesti. Tehtävänannon yksinkertaistamisen vuoksi oletetaan, 
      // että käyttäjä aina tyhjentää itse ostoskorin.
   }
   else {
      echo "Ostoskorisi on tyhjä.";
   }         

   // Suljetaan tietokantayhteys.
   pg_close($yhteys);
}
?>


<?php

// Palautetaan tilauksen - tai tietyn osan - kokonaispainon mukaiset postikulut.
function lahetyskulut ($kokonaispaino) {
   switch($kokonaispaino) {
      case ($kokonaispaino <= 50): 
         $postimaksu = 1.4;
         return $postimaksu;
      case ($kokonaispaino <= 100): 
         $postimaksu = 2.1;
         return $postimaksu;
      case ($kokonaispaino <= 250): 
         $postimaksu = 2.8;
         return $postimaksu;
      case ($kokonaispaino <= 500): 
         $postimaksu = 5.6;
         return $postimaksu;
      case ($kokonaispaino <= 1000): 
         $postimaksu = 8.4;
         return $postimaksu;
      case ($kokonaispaino <= 2000): 
         $postimaksu = 14.0;
         return $postimaksu;
   }
}

?>

      <p><a href="hae.php" class="btn btn-danger">Takaisin teosten hakuun</a></p>
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