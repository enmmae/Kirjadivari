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

   if (isset($_POST['tallenna'])) 
   {
      // Otetaan käyttäjän antamat tiedot ylös muuttujiin.
      $isbn = pg_escape_string($_POST['isbn']); // not null
      $tnimi = pg_escape_string($_POST['tnimi']);  // not null
      $tekija = pg_escape_string($_POST['tekija']);  // not null
      $tyyppi = pg_escape_string($_POST['tyyppi']);
      $luokka = pg_escape_string($_POST['luokka']);
      $hinta = doubleval($_POST['hinta']);  // not null
      $sis_osto_hinta = doubleval($_POST['sis_osto_hinta']);  // not null
      $paino = intval($_POST['paino']);  // not null

      // Tarkastetaan, että isbn, nimi tai teos eivät ole tyhjiä, ja että hinta, sisäänostohinta ja paino ovat suurempia kuin nolla.
      $tiedot_ok = (trim($isbn) != '' && trim($tnimi) != '' && trim($tekija) != '' && $hinta > 0 && $sis_osto_hinta > 0 && $paino > 0);

      if ($tiedot_ok) {
         // Otetaan ylös käyttäjän ylläpitämän divarin tunnus.
         $divari = $_SESSION['divari'];
         
         // Tarkastetaan, käyttääkö kyseinen yksittäinen divari omaa vai keskusdivarin tietokantaa.
         $tulos = pg_query("SELECT tnimi FROM $divari.teos");
         if (!$tulos)
            $tietokanta = 'keskusdivari';
         else
            $tietokanta = $divari;
         
         // Koitetaan lisätä teoksen tiedot kyseiseen tietokantaan.
         $kysely = "INSERT INTO $tietokanta.teos (isbn, tnimi, tekija, tyyppi, luokka) VALUES ('$isbn', '$tnimi', '$tekija', '$tyyppi', '$luokka')";
         $paivitys = pg_query($kysely);
         
         // Yksilöidään $ntunnus. Pitää huomioida kaikki erilliset tietokannat, jotta kun yksittäiset divarit siirtävät omia tietojaan keskusdivarin tietokantaan, 
         // ei tule vastaan samoja niteiden tunnuksia. Huomioidaan varuiksi divari D3 myös, jos sinne olisikin jäänyt jotain teoksia ennen triggerin luontia.
         $ntunnus = 1;
         $tulos = pg_query("(SELECT ntunnus FROM keskusdivari.nide) UNION (SELECT ntunnus FROM D1.nide) UNION (SELECT ntunnus FROM D3.nide) ORDER BY ntunnus");
         while ($rivi = pg_fetch_row($tulos))
            $ntunnus = $rivi[0] + 1;
         
         
         // Lisätään niteen tiedot tietokantaan.
         if ($tietokanta == 'keskusdivari') {
            $kysely1 = "INSERT INTO $tietokanta.nide (isbn, ntunnus, hinta, sis_osto_hinta, paino, dtunnus) 
                       VALUES ('$isbn', $ntunnus, $hinta, $sis_osto_hinta, $paino, '$divari')";
         }
         else {
            $kysely1 = "INSERT INTO $tietokanta.nide (isbn, ntunnus, hinta, sis_osto_hinta, paino) 
                       VALUES ('$isbn', $ntunnus, $hinta, $sis_osto_hinta, $paino)";
         }
         $paivitys1 = pg_query($kysely1);
         
         
         // Annetaan käyttäjälle tieto, miten lisäys sujui.
         if (($paivitys && (pg_affected_rows($paivitys) > 0)) && ($paivitys1 && (pg_affected_rows($paivitys1) > 0)))
            $viesti = 'Teoksen ja niteen tiedot lisätty tietokantaan.';
         else if ($paivitys1 && (pg_affected_rows($paivitys1) > 0)) {
            $lippu1 = false;
            $lippu2 = false;
            
            // Jos käyttäjän nyt antama tyyppi ei ollut tyhjä, ja tietokannan kyseisen teoksen tyyppi oli tyhjä,
            // päivitetään kyseisen teoksen tyyppi.
            if (trim($tyyppi) != '') {
               $tulos = pg_query("SELECT tyyppi FROM $tietokanta.teos WHERE isbn = '$isbn'");
               
               while ($rivi = pg_fetch_row($tulos))
                  $vanha_tyyppi =  "$rivi[0]";
               
               if (trim($vanha_tyyppi) == '') {
                  pg_query("UPDATE $tietokanta.teos SET tyyppi = '$tyyppi' WHERE isbn = '$isbn'");
                  $lippu1 = true;
               }
            }
            
            // Jos käyttäjän nyt antama luokka ei ollut tyhjä, ja tietokannan kyseisen teoksen luokka oli tyhjä,
            // päivitetään kyseisen teoksen luokka.
            if (trim($luokka) != '') {
               $tulos = pg_query("SELECT luokka FROM $tietokanta.teos WHERE isbn = '$isbn'");
               
               while ($rivi = pg_fetch_row($tulos))
                  $vanha_luokka =  "$rivi[0]";
               
               if (trim($vanha_luokka) == '') {
                  pg_query("UPDATE $tietokanta.teos SET luokka = '$luokka' WHERE isbn = '$isbn'");
                  $lippu2 = true;
               }
            }
            
            if ($lippu1 && $lippu2)
               $viesti = 'Teoksen tiedot löytyivät jo tietokannasta. Kyseinen nide lisätty tietokantaan. </br> Lisäksi teoksen tietoihin päivitetty tyyppi ja luokka, joita ei oltu aiemmin annettu.';
            else if ($lippu1 && !$lippu2)
               $viesti = 'Teoksen tiedot löytyivät jo tietokannasta. Kyseinen nide lisätty tietokantaan. </br> Lisäksi teoksen tietoihin päivitetty tyyppi, jota ei ollut aiemmin annettu.';
            else if (!$lippu1 && $lippu2)
               $viesti = 'Teoksen tiedot löytyivät jo tietokannasta. Kyseinen nide lisätty tietokantaan. </br> Lisäksi teoksen tietoihin päivitetty luokka, joita ei ollut aiemmin annettu.';
            else
               $viesti = 'Teoksen tiedot löytyivät jo tietokannasta. Kyseinen nide lisätty tietokantaan.';
         }
         else if ($paivitys && (pg_affected_rows($paivitys) > 0))
            $viesti = 'Teoksen yleiset tiedot lisätty tietokantaan. Niteen lisäys ei onnistunut.'; 
         else 
            $viesti = 'Jokin meni vikaan. Yritä uudestaan myöhemmin.';      
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
   <title>Lisää teos</title>
   <style>
      @import "tyylit.css";
   </style>
</head>
<body>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="lisaateos.php" method="post">

   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>
    
   <div class="box3">
   <?php if (isset($viesti)) echo '<small><small><p style="color:red">'.$viesti.'</p></small></small>'; ?>
      <table border="0" cellspacing="0" cellpadding="3">
         <p><strong>Lisää teos divarin tietokantaan</strong></p>
         <tr>
            <td>Isbn</td>
            <td><input type="text" name="isbn" value="" /></td>
         </tr>
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
         <tr>
             <td>Hinta</td>
             <td><input type="text" name="hinta" value="" /></td>
         </tr>
         <tr>
             <td>Sisäänostohinta</td>
             <td><input type="text" name="sis_osto_hinta" value="" /></td>
         </tr>
         <tr>
             <td>Paino</td>
             <td><input type="text" name="paino" value="" /></td>
         </tr>
      </table>
   
      <br/>

      <input type="hidden" name="tallenna" value="jep" />
      <input type="submit" value="Lisää" />
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