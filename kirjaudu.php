<?php
// Otetaan yhteys tietokantaan.
require_once 'config.php';

// Aloitetaan sessio.
session_start();
 
if (isset($_POST['tallenna']))
{
   // Otetaan käyttäjän antamat tiedot ylös muuttujiin.
   $ktunnus = pg_escape_string($_POST['ktunnus']);
   $salasana = pg_escape_string($_POST['salasana']);

   // Tarkastetaan, että käyttäjän antamat tiedot eivät ole tyhjiä.
   $tiedot_ok = trim($ktunnus) != '' && trim($salasana) != '';

   // Jos käyttäjän antamat tiedot olivat ok, siirrytään tarkastelemaan näitä tietoja.
   if ($tiedot_ok)
   {
      // Katsotaan, löytyykö kayttaja -taulusta kyseisiä käyttäjän antamia tietoja.
      $tulos = pg_query("SELECT ktunnus, salasana FROM keskusdivari.kayttaja WHERE ktunnus = '$ktunnus' AND salasana = '$salasana'");
      
      $kirjautuminen_ok = false;
      if ($rivi = pg_fetch_row($tulos))
         $kirjautuminen_ok = true;
 
      // Jos löytyy niin kirjautuminen voidaan suorittaa.
      if ($kirjautuminen_ok) {      
         // Luetaan kayttaja -taulusta kyseisen käyttäjätunnuksen omaavan käyttäjän rooli.
         $tulos = pg_query("SELECT rooli FROM keskusdivari.kayttaja WHERE ktunnus = '$ktunnus'");
         while ($rivi = pg_fetch_row($tulos))
            $rooli = $rivi[0];
         
         $_SESSION['ktunnus'] = $ktunnus;
         
         // Jos rooli on 'asiakas' niin siirrytään asiakkaan etusivulle, 
         // muuten jaetaan rooli osiin ja annetaan eteenpäin tieto, että minkä divarin ylläpitäjä käyttäjä on.
         if ($rooli == 'asiakas')
            header("Location: asiakasetusivu.php");
         else {
            list($divari, $rooli) = split('[_]', $rooli);
            $_SESSION['divari'] = $divari;
            header("Location: adminetusivu.php");
         }
      }
      // Jos käyttäjätunnus tai salasana oli virheellinen, ilmoitetaan tästä käyttäjälle.
      else 
         $viesti = 'Käyttäjätunnus tai salasana väärin.';
   }
   // Jos jompikumpi käyttäjän antamista tiedoista oli tyhjä, ilmoitetaan tästä käyttäjälle.
   else
      $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
}

// Suljetaan tietokantayhteys.
pg_close($yhteys);

?>

<html lang="fi">
<head>
   <meta charset="UTF-8">
   <title>Kirjaudu</title>
   <style>
      @import "tyylit.css";
   </style>
</head>
<body>

    <!-- Lomake lähetetään samalle sivulle -->
   <form action="kirjaudu.php" method="post">

   <div class="box1">
      <h1 class = "otsikko">Keskusdivari</h1>
      <p class = "alku"><a href="adminetusivu.php" style="text-decoration: none"><small>Etusivu</small></a></p>
   </div>

   <div class="box3">
   <?php if (isset($viesti)) echo '<small><small><p style="color:red">'.$viesti.'</p></small></small>'; ?>
         <p><strong>Kirjaudu sisään</strong></p>
         <table border="0" cellspacing="0" cellpadding="3">
            <tr>
               <td>Käyttäjätunnus</td>
               <td><input type="text" name="ktunnus" value="" /></td>
            </tr>
            <tr>
               <td>Salasana</td>
               <td><input type="password" name="salasana" value="" /></td>
            </tr>
         </table>
      </br>
      <input type="hidden" name="tallenna" value="jep" />
      <input type="submit" value="Kirjaudu sisään" />
      <p><small><small>Eikö sinulla ole käyttäjää? </small></small><a href="rekisteroidy.php"><small><small>Rekisteröidy</small></small></a></p>
      </form>
   </div>

   <hr>
   <p>Tiko2018 | LUO | UTA</p>
</body>

</html>
