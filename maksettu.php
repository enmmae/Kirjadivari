<!DOCTYPE html>
<html lang="fi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Maksettu</title>
   <style>

      @import "tyylit.css";

   </style>
</head>
<body>
   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
   <div class="box3">

<?php
// Aloitetaan sessio.
session_start();
 
// Jos sessiomuuttujaa ei ole alustettu, siirretään käyttäjä takaisin etusivulle.
if(!isset($_SESSION['ktunnus'])) {
  header("Location: etusivu.php");
  exit;
}
else {
   
   // Ilmoitetaan käyttäjälle, että ostotapahtuma onnistui.
   echo "<small><small> Maksusi on hyväksytty ja tilauksesi on lähetetty. </small></small>";
   
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