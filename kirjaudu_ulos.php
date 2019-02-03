<?php
// Otetaan yhteys tietokantaan.
session_start();
 
// Asetataan tyhjiksi kaikki sessiomuuttujat.
$_SESSION = array();
 
// Tuhotaan sessio.
session_destroy();
 
// Siirretään käyttäjä sivulle etusivu.php
header("location: etusivu.php");
exit;
?>