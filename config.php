<?php
// host-osoite, portti, tietokannan nimi, käyttäjä ja salasana
$y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=ss424549 user=ss424549 password=salasana";
 
// Yritetään yhteyden luontia ja kerrotaan käyttäjälle, mikäli se ei onnistunut.
if (!$yhteys = pg_connect($y_tiedot))
   die("Tietokantayhteyden luominen epäonnistui.");
?>
