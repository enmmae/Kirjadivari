-- R2: Ryhmittele myynnissä olevat teokset niiden luokan mukaan. Anna luokkien teosten kokonaismyyntihinta
-- sekä keskihinta.

CREATE VIEW myynnissa_olevat_teokset
AS SELECT keskusdivari.teos.tnimi, keskusdivari.teos.luokka,
          SUM(keskusdivari.nide.hinta) AS kokonaismyyntihinta,
          ROUND(AVG(keskusdivari.nide.hinta),2) AS keskihinta
   FROM keskusdivari.teos INNER JOIN keskusdivari.nide
        ON keskusdivari.teos.isbn = keskusdivari.nide.isbn
   WHERE keskusdivari.nide.tila = 0
   GROUP BY keskusdivari.teos.tnimi, keskusdivari.teos.luokka
   ORDER BY keskusdivari.teos.luokka;



-- R3: Tee keskustietokannasta raportti, johon on listattu kaikki asiakkaat, sekä näiden viime vuonna ostamien
-- teosten lukumäärä. (Älä kiinnitä vuosilukua vaan laske se.) 

CREATE VIEW viime_vuoden_tilaukset(nimi, tilaus_lkm)
AS SELECT keskusdivari.kayttaja.knimi, 0 as tilaus_maara
   FROM keskusdivari.kayttaja
   WHERE NOT EXISTS
         (SELECT NULL 
         FROM keskusdivari.tilaus, keskusdivari.nide
         WHERE EXTRACT(YEAR FROM keskusdivari.nide.myyntipvm) = EXTRACT(YEAR FROM now())-1
               AND keskusdivari.tilaus.ktunnus = keskusdivari.kayttaja.ktunnus
               AND keskusdivari.nide.ntunnus = keskusdivari.tilaus.ntunnus)
   UNION
   SELECT DISTINCT keskusdivari.kayttaja.knimi, COUNT(keskusdivari.tilaus.ktunnus) as tilaus_maara
   FROM keskusdivari.kayttaja LEFT OUTER JOIN keskusdivari.tilaus
        ON keskusdivari.tilaus.ktunnus = keskusdivari.kayttaja.ktunnus, keskusdivari.nide
   WHERE EXTRACT(YEAR FROM keskusdivari.nide.myyntipvm) = EXTRACT(YEAR FROM now())-1
         AND keskusdivari.nide.ntunnus = keskusdivari.tilaus.ntunnus
   GROUP BY keskusdivari.kayttaja.knimi
   ORDER BY tilaus_maara DESC;

