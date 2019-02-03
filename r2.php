<!DOCTYPE html>
<html lang="fi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>R2</title>
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
   <p><strong>Myynnissä olevat teokset luokan mukaan</strong></p>

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
   
   $tulos = pg_query("SELECT * FROM myynnissa_olevat_teokset");
   
   if ($tulos && (pg_affected_rows($tulos) > 0)) { 
      $luokka = ' ';
      
      // R2: Myynnissä olevat teokset niiden luokan mukaan.
      while ($rivi = pg_fetch_row($tulos)) {
         if ($luokka != $rivi[1])
            echo "</br> Luokka: $rivi[1] </br>";
         echo "Teos: $rivi[0] | Kokonaismyyntihinta: $rivi[2] € | Keskihinta: $rivi[3] € </br>";
         $luokka = $rivi[1];
      }
      echo "</br>";
   }
   else {
      echo "Tällä hetkellä ei ole teoksia myynnissä.</br>";
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