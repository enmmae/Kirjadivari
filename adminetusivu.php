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

?>


<!DOCTYPE html>
<html lang="fi">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Etusivu</title>
      <style>

         @import "tyylit.css";

      </style>
   </head>
<body>

   <div>

   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku">Etusivu</p>
   </div>

   <div class="box2">
      <p class = "teksti1"> <a href="hae.php"> Hae </a></p>
      <p class = "teksti1"><a href="ostoskori.php">Ostoskori</a></p>
      <p class = "teksti1"> <a href="lisaateos.php"> Lisää teos </a></p>
      <p class = "teksti1"> <a href="paivitatiedot.php"> Päivitä tiedot </a></p>
      <p class = "teksti1"><a href="r2.php">Myynnissä olevat teokset</a></p>
      <p class = "teksti1"><a href="r3.php">Asiakkaat ja näiden tilaukset</a></p>
      
   </div>

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

