KESKUSDIVARI:

SELECT * FROM keskusdivari.kayttaja;

 ktunnus | salasana  |       knimi        |             kosoite             |      sposti       |    puh     |     rooli
---------+-----------+--------------------+---------------------------------+-------------------+------------+---------------
 admin1  | salasana1 | Mikko Matemaatikko | Bulevardi 1, 04200 Kerava       | mikko@gmail.fi    | 0409897637 | D1_yllapitaja
 admin2  | salasana2 | Essi Esimerkki     | Katutie 3, 05800 Hyvinkää       | essi@gmail.fi     | 0406765483 | D2_yllapitaja
 admin3  | salasana3 | Marjatta Marja     | Katutie 3, 15110 Lahti          | marjatta@gmail.fi | 0407382967 | D3_yllapitaja
 admin4  | salasana4 | Juuso Juhlava      | Katutie 3, 06100 Porvoo         | juuso@gmail.fi    | 0405928594 | D4_yllapitaja
 em123   | salasana  | Emilia Entinen     | Koirapolku 7 A 3, 33101 Tampere | em@hotmail.com    | 0407572893 | asiakas
 ss123   | salasana  | Sakari Sorkka      | Laamatie 5 B, 06100 Porvoo      | ss@hotmail.com    | 0400537284 | asiakas
(6 rows)


SELECT * FROM keskusdivari.divari;

 dtunnus |      dnimi       |              dosoite              |                   websivu
---------+------------------+-----------------------------------+---------------------------------------------
 D1      | Lassen lehti     | Kauppakaari 15, 04200 Kerava      |
 D2      | Galleinn Galle   | Hämeenkatu 9, 05800 Hyvinkää      |
 D3      | Jonen kirjabaari | Aleksanterinkatu 9, 15110 Lahti   |
 D4      | Book fiesta      | Mannerheiminkatu 13, 06100 Porvoo |
 KD      | Keskusdivari     | Hämeenkatu 2, 33100 Tampere       | http://www.sis.uta.fi/~ss424549/etusivu.php
(5 rows)


SELECT * FROM keskusdivari.teos;

     isbn      |                     tnimi                      |      tekija      |       tyyppi        |   luokka
---------------+------------------------------------------------+------------------+---------------------+------------
 9789518515541 | Ylpeys ja Ennakkoluulo                         | Jane Austen      | romantiikka         | romaani
 9780458938704 | Lord of the Rings -trilogia                    | J. R. R. Tolkien | fantasia            | romaani
 9780545425117 | Nälkäpeli                                      | Suzanne Collins  | tieteiskirjallisuus | romaani
 9789510358313 | Vihan liekit                                   | Suzanne Collins  | tieteiskirjallisuus | romaani
 9789510358306 | Matkijanärhi                                   | Suzanne Collins  | tieteiskirjallisuus | romaani
 9155430674    | Elektran tytär                                 | Madeleine Brent  | romantiikka         | romaani
 9156381451    | Tuulentavoittelijan morsian                    | Madeleine Brent  | romantiikka         | romaani
 9789510431122 | Turms kuolematon                               | Mika Waltari     | Historia            | romaani
 9789523041455 | Komisario Palmun erehdys                       | Mika Waltari     | dekkari             | romaani
 9789530257491 | Friikkilän pojat Mexicossa                     | Shelton Gilbert  | huumori             | sarjakuva
 9789510396230 | Miten saan ystäviä, menestystä, vaikutusvaltaa | Dale Carnegien   | opas                | tietokirja
 9789518576213 | Cujo                                           | Stephen King     |                     |
 9789518986417 | Carrie                                         | Stephen King     |                     |
(13 rows)



SELECT * FROM keskusdivari.nide;

     isbn      | ntunnus | hinta | sis_osto_hinta | myyntipvm  | paino | tila | dtunnus
---------------+---------+-------+----------------+------------+-------+------+---------
 9789518515541 |       1 |  9.00 |           5.40 |            |   200 |    0 | D2
 9780458938704 |       2 |  8.00 |           2.90 | 2017-04-05 |   800 |    2 | D2
 9780545425117 |       3 |  6.50 |           2.50 | 2017-04-05 |   350 |    2 | D2
 9789510358313 |       4 |  8.50 |           2.30 |            |   370 |    0 | D2
 9789510358306 |       5 |  5.90 |           4.30 |            |   330 |    0 | D2
 9155430674    |       6 |  7.90 |           4.30 |            |   310 |    0 | D2
 9156381451    |       7 |  5.40 |           4.50 | 2017-04-05 |   400 |    2 | D2
 9789510431122 |       8 |  9.50 |           2.50 |            |   200 |    0 | D2
 9789523041455 |       9 |  5.90 |           3.90 | 2017-04-05 |   170 |    2 | D2
 9789530257491 |      10 |  7.00 |           4.50 |            |   380 |    0 | D2
 9789510396230 |      11 |  8.00 |           4.30 |            |   290 |    0 | D2
 9780545425117 |      12 |  6.50 |           2.50 |            |   350 |    0 | D3
 9789510358313 |      13 |  8.50 |           2.30 |            |   370 |    0 | D3
 9789510358306 |      14 |  5.90 |           4.30 |            |   330 |    0 | D3
 9155430674    |      15 |  5.90 |           4.30 | 2017-04-05 |   310 |    2 | D3
 9156381451    |      16 |  5.90 |           4.30 |            |   400 |    0 | D3
 9155430674    |      22 |  6.90 |           4.30 | 2017-04-05 |   310 |    2 | D1
 9789510396230 |      27 |  4.80 |           4.30 |            |   290 |    0 | D1
 9789518576213 |      28 | 12.00 |           1.00 |            |   330 |    0 | D4
 9789518576213 |      29 | 10.00 |           1.00 |            |   330 |    0 | D4
 9789518986417 |      30 | 15.00 |           1.00 |            |   170 |    0 | D4
 9789518986417 |      31 | 12.00 |           1.00 |            |   160 |    0 | D4
(22 rows)


SELECT * FROM keskusdivari.tilaus;

 ktunnus | ntunnus | tila
---------+---------+------
 ss123   |       9 |    1
 ss123   |       2 |    1
 admin2  |      15 |    1
 em123   |       7 |    1
 em123   |      22 |    1
 em123   |       3 |    1
(6 rows)



D1:

SELECT * FROM d1.teos;

     isbn      |                     tnimi                      |      tekija      |      tyyppi      |   luokka
---------------+------------------------------------------------+------------------+------------------+------------
 9789510098752 | Sinuhe Egyptiläinen                            | Mika Waltari     | historia         | romaani
 9789510425459 | Tuntematon Sotilas                             | Väinö Linna      | sotakirjallisuus | romaani
 9789513169121 | Piin Elämä                                     | Yann Martel      | seikkailu        | romaani
 9789510392317 | Hobitti eli sinne ja takaisin                  | J. R. R. Tolkien | fantasia         | romaani
 9781533493460 | Emma                                           | Jane Austen      | romantiikka      | romaani
 9155430674    | Elektran tytär                                 | Madeleine Brent  | romantiikka      | romaani
 9156381451    | Tuulentavoittelijan morsian                    | Madeleine Brent  | romantiikka      | romaani
 9789510431122 | Turms kuolematon                               | Mika Waltari     | Historia         | romaani
 9789523041455 | Komisario Palmun erehdys                       | Mika Waltari     | dekkari          | romaani
 9789530257491 | Friikkilän pojat Mexicossa                     | Shelton Gilbert  | huumori          | sarjakuva
 9789510396230 | Miten saan ystäviä, menestystä, vaikutusvaltaa | Dale Carnegien   | opas             | tietokirja
(11 rows)


SELECT * FROM d1.nide;

     isbn      | ntunnus | hinta | sis_osto_hinta | myyntipvm  | paino | tila | dtunnus
---------------+---------+-------+----------------+------------+-------+------+---------
 9789510098752 |      17 |  7.50 |           3.30 |            |   250 |    0 |
 9789510425459 |      18 |  8.50 |           4.00 |            |   500 |    0 |
 9789513169121 |      19 |  6.50 |           4.30 |            |   150 |    0 |
 9789510392317 |      20 |  7.50 |           3.20 |            |   750 |    0 |
 9781533493460 |      21 |  5.90 |           3.50 |            |   150 |    0 |
 9156381451    |      23 |  7.60 |           4.50 |            |   400 |    0 |
 9789510431122 |      24 |  5.50 |           2.50 |            |   200 |    0 |
 9789523041455 |      25 |  3.50 |           3.90 |            |   170 |    0 |
 9789530257491 |      26 |  9.60 |           4.50 |            |   380 |    0 |
 9789510396230 |      27 |  4.80 |           4.30 |            |   290 |    0 |
 9155430674    |      22 |  6.90 |           4.30 | 2017-04-05 |   310 |    2 |
(11 rows)



D3:

SELECT * FROM d3.teos;

     isbn      |            tnimi            |     tekija      |       tyyppi        | luokka
---------------+-----------------------------+-----------------+---------------------+---------
 9780545425117 | Nälkäpeli                   | Suzanne Collins | tieteiskirjallisuus | romaani
 9789510358313 | Vihan liekit                | Suzanne Collins | tieteiskirjallisuus | romaani
 9789510358306 | Matkijanärhi                | Suzanne Collins | tieteiskirjallisuus | romaani
 9155430674    | Elektran tytär              | Madeleine Brent | romantiikka         | romaani
 9156381451    | Tuulentavoittelijan morsian | Madeleine Brent | romantiikka         | romaani
(5 rows)


SELECT * FROM d3.nide;

     isbn      | ntunnus | hinta | sis_osto_hinta | myyntipvm  | paino | tila | dtunnus
---------------+---------+-------+----------------+------------+-------+------+---------
 9780545425117 |      12 |  6.50 |           2.50 |            |   350 |    0 |
 9789510358313 |      13 |  8.50 |           2.30 |            |   370 |    0 |
 9789510358306 |      14 |  5.90 |           4.30 |            |   330 |    0 |
 9156381451    |      16 |  5.90 |           4.30 |            |   400 |    0 |
 9155430674    |      15 |  5.90 |           4.30 | 2017-04-05 |   310 |    2 |
(5 rows)



D4:

SELECT * FROM D4.teos;

     isbn      | tnimi  |    tekija    | tyyppi | luokka
---------------+--------+--------------+--------+--------
 9789518576213 | Cujo   | Stephen King |        |
 9789518986417 | Carrie | Stephen King |        |
(2 rows)

SELECT * FROM D4.nide;

     isbn      | ntunnus | hinta | sis_osto_hinta | myyntipvm | paino | tila
---------------+---------+-------+----------------+-----------+-------+------
 9789518576213 |      28 | 12.00 |           1.00 |           |   330 |    0
 9789518576213 |      29 | 10.00 |           1.00 |           |   330 |    0
 9789518986417 |      30 | 15.00 |           1.00 |           |   170 |    0
 9789518986417 |      31 | 12.00 |           1.00 |           |   160 |    0
(4 rows)



