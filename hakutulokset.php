<!DOCTYPE html>
<html lang="fi">
 <head>
  <meta charset="UTF-8">
  <title>Hakutulokset</title>
      <style>
         @import "tyylit.css";
      </style>
 </head>
 <body>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="hakutulokset.php" method="post">

   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
   <div class="box2"> 

      <p> <strong>Hakutulokset</strong> </p>
      
<?php
// Aloitetaan sessio.
session_start();
 
// Jos sessiomuuttujaa ei ole alustettu, siirretään käyttäjä takaisin etusivulle.
if(!isset($_SESSION['ktunnus'])) {
  header("Location: etusivu.php");
  exit;
}
else {
   // Otetaan yhteys tietokantaan.
   require_once 'config.php';
   
   // Otetaan käyttäjän käyttäjätunnus ylös.
   $ktunnus = $_SESSION['ktunnus'];
   
   // Otetaan sessiomuuttujat ylös, joihin haku voidaan kohdistaa.
   $tnimi = $_SESSION['tnimi'];
   $tnimi = strtolower($tnimi);
   $tekija = $_SESSION['tekija'];
   $tekija = strtolower($tekija);
   $tyyppi = $_SESSION['tyyppi'];
   $tyyppi = strtolower($tyyppi);
   $luokka = $_SESSION['luokka'];
   $luokka = strtolower($luokka);

   if (trim($tekija) == '' && trim($tyyppi) == '' && trim($luokka) == '') {
      // R4: Järjestetään teoksen nimeen kohdistuvan haun tulokset oletetun relevanssin perusteella.
      
      include 'r4.php';
      $palautus = r4($tnimi);
      if(count($palautus) > 0) {
         // Listan palautus väärinpäin, suurimmasta pienimpään.
         for($i = count($palautus)-1; $i >= 0; $i--) {
            $kysely = "SELECT ntunnus, keskusdivari.teos.isbn, tnimi, tekija, tyyppi, luokka, hinta, dnimi
                       FROM keskusdivari.teos INNER JOIN keskusdivari.nide
                            ON keskusdivari.teos.isbn = keskusdivari.nide.isbn, keskusdivari.divari
                       WHERE keskusdivari.nide.dtunnus = keskusdivari.divari.dtunnus 
                             AND keskusdivari.nide.tila = '0' AND tnimi = '";
            $kysely .= $palautus[$i];
            $kysely .= "'";
            
?>

      <form action= "http://www.sis.uta.fi/~ss424549/hakutulokset.php">
            
<?php

            $tulos = pg_query($kysely);
            
             // Tulostetaan mahdolliset hakutulokset käyttäjälle, joista tämä voi valita ostoskoriin siirrettävät teokset.
            if ($tulos && (pg_affected_rows($tulos) > 0)) { 
               
               while ($rivi = pg_fetch_row($tulos)) {
                  $str =  "$rivi[3], $rivi[2] | Hinta: $rivi[6] € | ISBN: $rivi[1] </br> Tyyppi: $rivi[4] | Luokka: $rivi[5] | Myyjä: $rivi[7] </br></br>";
?> 

                     <tr>
                        <td> 
                            <input type="checkbox" name="valinta[]" value= <?php echo $rivi[0]; ?>
                        </td>
                        <td><?php echo $str;?></td>
                    </tr>  

<?php
               }
            }     
         }
?>   
            
         <input type="submit" value="Lisää ostoskoriin">
      </form>
   <br> 
            
<?php
      }
      // Jos haulla ei ollut tuloksia, annetaan käyttäjälle ilmoitus tästä. 
      else {
         echo "Haullasi ei löytynyt yhtään teosta. Tarkista hakuehtosi.";
      }    
   }
   else {
      // R1: Annetaan haun tulokset, jotka toteuttavat annetut kriteerit.

?>

      <form action= "http://www.sis.uta.fi/~ss424549/hakutulokset.php">
      
<?php

      $tulos = pg_query("SELECT ntunnus, teos.isbn, tnimi, tekija, tyyppi, luokka, hinta, dnimi
                         FROM keskusdivari.teos AS teos, keskusdivari.nide AS nide, keskusdivari.divari AS divari
                         WHERE teos.isbn = nide.isbn AND nide.dtunnus = divari.dtunnus AND nide.tila = 0 AND
                               lower(tnimi) LIKE '%$tnimi%' AND lower(tekija) LIKE '%$tekija%' AND lower(tyyppi) LIKE '%$tyyppi%' AND lower(luokka) LIKE '%$luokka%'");               
   
      // Tulostetaan mahdolliset hakutulokset käyttäjälle, joista tämä voi valita ostoskoriin siirrettävät teokset.
      if ($tulos && (pg_affected_rows($tulos) > 0)) { 
         
         while ($rivi = pg_fetch_row($tulos)) {
            $str =  "$rivi[3], $rivi[2] | Hinta: $rivi[6] € | ISBN: $rivi[1] </br> Tyyppi: $rivi[4] | Luokka: $rivi[5] | Myyjä: $rivi[7] </br></br>";
?> 

          <tr>
                  <td> 
                      <input type="checkbox" name="valinta[]" value= <?php echo $rivi[0]; ?>
                  </td>
                  <td><?php echo $str;?></td>
              </tr>  

<?php
         }
         echo "<br/>";
?>   
      
         <input type="submit" value="Lisää ostoskoriin">
      </form>
      <br> 
   
<?php
      }
      // Jos haulla ei ollut tuloksia, annetaan käyttäjälle ilmoitus tästä. 
      else {
         echo "Haullasi ei löytynyt yhtään teosta. Tarkista hakuehtosi.";
      }         
   }          
   
   // Käydään läpi valitut teokset/niteet.
   $valitut = $_POST['valinta'];

   if(!empty($valitut)) {
      // Otetaan ylös valittujen teosten lukumäärä.
      $N = count($valitut);
   
      // Muokataan valittujen niteiden tilat varatuiksi ja lisätään kyseessä olevat rivit tilaus -tauluun.
      for($i = 0; $i < $N; $i++) {            
         // Tarkistetaan, onko teos ehditty varata käyttäjän tarkastellessa hakutuloksia. Jos on, niin alustetaan sessiomuuttuja, jotta ostoskorissa pystytään 
         // ilmoittamaan käyttäjälle, ettei kaikkia niteitä pystytty varaamaan. Muuten varataan teos.
         $tulos = pg_query("SELECT tila FROM keskusdivari.nide WHERE ntunnus = $valitut[$i]");
         while ($rivi = pg_fetch_row($tulos))
            $tila =  $rivi[0];
         
         if ($tila == 0) { 
            // Päivitetään keskusdivarin tietokantaan valitun niteen tila varatuksi.
            $tulos = pg_query("UPDATE keskusdivari.nide SET tila = 1 WHERE ntunnus = $valitut[$i]");
         
            // Otetaan ylös kyseisen niteen omistaja divarin tunnus.
            $tulos = pg_query("SELECT dtunnus FROM keskusdivari.nide WHERE ntunnus = $valitut[$i]");
            while ($rivi = pg_fetch_row($tulos))
               $tietokanta =  "$rivi[0]";
               
            // Jos tällä divarin tunnuksella löytyy oma tietokanta, päivitetään myös sinne valitun niteen tila varatuksi.
            $tulos = pg_query("SELECT tnimi FROM $tietokanta.teos");
            if ($tulos)
               pg_query("UPDATE $tietokanta.nide SET tila = 1 WHERE ntunnus = $valitut[$i]");
            
            // Lisätään rivi tilaus -tauluun.
            pg_query("INSERT INTO keskusdivari.tilaus (ktunnus, ntunnus, tila) VALUES ('$ktunnus', $valitut[$i], 0)");
         }
         else {
            $lippu = true;
            $_SESSION['lippu'] = $lippu;
         }
      }
      // Siirrytään sivulle ostoskori.php
      header('Location: ostoskori.php');
   }

   // Suljetaan tietokantayhteys.
   pg_close($yhteys);
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