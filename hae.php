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

   if (isset($_POST['tallenna']))
   {
      // Otetaan käyttäjän antamat tiedot ylös muuttujiin.
      $tnimi = pg_escape_string($_POST['tnimi']);
      $tekija = pg_escape_string($_POST['tekija']);
      $tyyppi = pg_escape_string($_POST['tyyppi']);
      $luokka = pg_escape_string($_POST['luokka']);
      
      // Tarkastetaan, että ainakin yksi käyttäjän antamista tiedoista ei ole tyhjä, jotta haku voitaisiin suorittaa.
      $tiedot_ok = (trim($tnimi) != '' OR trim($tekija) != '' OR trim($tyyppi) != '' OR trim($luokka) != '');

      if ($tiedot_ok) {
         // Otetaan käyttäjän antamat tiedot ylös sessiomuuttujiin.
         $_SESSION['tnimi'] = $tnimi;
         $_SESSION['tekija'] = $tekija;
         $_SESSION['tyyppi'] = $tyyppi;
         $_SESSION['luokka'] = $luokka;

         // Siirrytään sivulle hakutulokset.php
         header('Location: hakutulokset.php');         
      }
      else {
         $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
      }
   }

   // Suljetaan tietokantayhteys.
   pg_close($yhteys);
}
?>

<html lang="fi">
<head>
   <meta charset="UTF-8">
   <title>Hae</title>
      <style>
         @import "tyylit.css";
      </style>
</head>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="hae.php" method="post">
   
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
    
   <div class="box3">
   <?php if (isset($viesti)) echo '<small><small><p style="color:red">'.$viesti.'</p></small></small>'; ?>
      <table border="0" cellspacing="0" cellpadding="3">
         <p><strong>Hae teosta</strong></p>
          <tr>
             <td>Nimi</td>
             <td><input type="text" name="tnimi" value="" /></td>
          </tr>
          <tr>
             <td>Tekijä</td>
             <td><input type="text" name="tekija" value="" /></td>
          </tr>
          <tr>
             <td>Tyyppi</td>
             <td><input type="text" name="tyyppi" value="" /></td>
          </tr>
          <tr>
             <td>Luokka</td>
             <td><input type="text" name="luokka" value="" /></td>
          </tr>
      </table>

      <br />

      <input type="hidden" name="tallenna" value="jep" />
      <input type="submit" value="Hae" />
      </form>
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