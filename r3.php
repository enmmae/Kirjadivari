<!DOCTYPE html>
<html lang="fi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>R3</title>
   <style>

      @import "tyylit.css";

   </style>
</head>
<body>
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>

<div class = "box2">
   <p><strong>Asiakkaat ja näiden viime vuonna ostamien teosten lukumäärä</strong></p>
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
   
   $tulos = pg_query("SELECT * FROM viime_vuoden_tilaukset");
   
   if ($tulos && (pg_affected_rows($tulos) > 0)) { 
   
      // R3: Kaikki asiakkaat ja näiden viime vuonna ostamien teosten lukumäärä.
      while ($rivi = pg_fetch_row($tulos)) {
         echo "Käyttäjä: $rivi[0] | Ostettujen teosten lukumäärä: $rivi[1] </br>";
      }
   }
   else {
      echo "Tietokannasta ei löytynyt asiakkaita.";
   } 
   
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