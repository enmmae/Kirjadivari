-- Tiko2018 | Harjoitustyö

-- Lisäyslauseet


-- KESKUSDIVARI:

-- Käyttäjät
INSERT INTO keskusdivari.kayttaja VALUES ('admin1', 'salasana1', 'Mikko Matemaatikko', 'Bulevardi 1, 04200 Kerava', 'mikko@gmail.fi', '0409897637', 'D1_yllapitaja');
INSERT INTO keskusdivari.kayttaja VALUES ('admin2', 'salasana2', 'Essi Esimerkki', 'Katutie 3, 05800 Hyvinkää', 'essi@gmail.fi', '0406765483', 'D2_yllapitaja');
INSERT INTO keskusdivari.kayttaja VALUES ('admin3', 'salasana3', 'Marjatta Marja', 'Katutie 3, 15110 Lahti', 'marjatta@gmail.fi', '0407382967', 'D3_yllapitaja');
INSERT INTO keskusdivari.kayttaja VALUES ('admin4', 'salasana4', 'Juuso Juhlava', 'Katutie 3, 06100 Porvoo', 'juuso@gmail.fi', '0405928594', 'D4_yllapitaja');
INSERT INTO keskusdivari.kayttaja VALUES ('em123', 'salasana', 'Emilia Entinen', 'Koirapolku 7 A 3, 33101 Tampere', 'em@hotmail.com', '0407572893');
INSERT INTO keskusdivari.kayttaja VALUES ('ss123', 'salasana', 'Sakari Sorkka', 'Laamatie 5 B, 06100 Porvoo', 'ss@hotmail.com', '0400537284');

-- Divarit
INSERT INTO keskusdivari.divari VALUES ('D1', 'Lassen lehti', 'Kauppakaari 15, 04200 Kerava');
INSERT INTO keskusdivari.divari VALUES ('D2', 'Galleinn Galle', 'Hämeenkatu 9, 05800 Hyvinkää');
INSERT INTO keskusdivari.divari VALUES ('D3', 'Jonen kirjabaari', 'Aleksanterinkatu 9, 15110 Lahti');
INSERT INTO keskusdivari.divari VALUES ('D4', 'Book fiesta', 'Mannerheiminkatu 13, 06100 Porvoo');
INSERT INTO keskusdivari.divari VALUES ('KD', 'Keskusdivari', 'Hämeenkatu 2, 33100 Tampere', 'http://www.sis.uta.fi/~eu421126/etusivu.php');


-- INSERT INTO keskusdivari.teos VALUES ('isbn', 'tnimi', 'tekija', 'tyyppi', 'luokka');
INSERT INTO keskusdivari.teos VALUES ('9789518515541', 'Ylpeys ja Ennakkoluulo', 'Jane Austen', 'romantiikka', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9780458938704', 'Lord of the Rings -trilogia', 'J. R. R. Tolkien', 'fantasia', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9780545425117', 'Nälkäpeli', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9789510358313', 'Vihan liekit', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9789510358306', 'Matkijanärhi', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani'); 
-- teht.annossa olleet:
INSERT INTO keskusdivari.teos VALUES ('9155430674', 'Elektran tytär', 'Madeleine Brent', 'romantiikka', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9156381451', 'Tuulentavoittelijan morsian', 'Madeleine Brent', 'romantiikka', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9789510431122', 'Turms kuolematon', 'Mika Waltari', 'Historia', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9789523041455', 'Komisario Palmun erehdys', 'Mika Waltari', 'dekkari', 'romaani');
INSERT INTO keskusdivari.teos VALUES ('9789530257491', 'Friikkilän pojat Mexicossa', 'Shelton Gilbert', 'huumori', 'sarjakuva');
INSERT INTO keskusdivari.teos VALUES ('9789510396230', 'Miten saan ystäviä, menestystä, vaikutusvaltaa', 'Dale Carnegien', 'opas', 'tietokirja');


-- INSERT INTO keskusdivari.nide VALUES ('isbn', ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, tila, 'dtunnus');
INSERT INTO keskusdivari.nide VALUES ('9789518515541', 1, 9, 5.4, NULL, 200, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9780458938704', 2, 8, 2.9, NULL, 800, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9780545425117', 3, 6.5, 2.5, NULL, 350, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789510358313', 4, 8.5, 2.3, NULL, 370, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789510358306', 5, 5.9, 4.3, NULL, 330, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9155430674', 6, 7.9, 4.3, NULL, 310, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9156381451', 7, 5.4, 4.5, NULL, 400, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789510431122', 8, 9.5, 2.5, NULL, 200, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789523041455', 9, 5.9, 3.9, NULL, 170, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789530257491', 10, 7.0, 4.5, NULL, 380, 0, 'D2');
INSERT INTO keskusdivari.nide VALUES ('9789510396230', 11, 8.0, 4.3, NULL, 290, 0, 'D2');



-- D1:

INSERT INTO D1.teos VALUES ('9789510098752', 'Sinuhe Egyptiläinen', 'Mika Waltari', 'historia', 'romaani');
INSERT INTO D1.teos VALUES ('9789510425459', 'Tuntematon Sotilas', 'Väinö Linna', 'sotakirjallisuus', 'romaani');
INSERT INTO D1.teos VALUES ('9789513169121', 'Piin Elämä', 'Yann Martel', 'seikkailu', 'romaani');
INSERT INTO D1.teos VALUES ('9789510392317', 'Hobitti eli sinne ja takaisin', 'J. R. R. Tolkien', 'fantasia', 'romaani');
INSERT INTO D1.teos VALUES ('9781533493460', 'Emma', 'Jane Austen', 'romantiikka', 'romaani');
INSERT INTO D1.teos VALUES ('9155430674', 'Elektran tytär', 'Madeleine Brent', 'romantiikka', 'romaani');
INSERT INTO D1.teos VALUES ('9156381451', 'Tuulentavoittelijan morsian', 'Madeleine Brent', 'romantiikka', 'romaani');
INSERT INTO D1.teos VALUES ('9789510431122', 'Turms kuolematon', 'Mika Waltari', 'Historia', 'romaani');
INSERT INTO D1.teos VALUES ('9789523041455', 'Komisario Palmun erehdys', 'Mika Waltari', 'dekkari', 'romaani');
INSERT INTO D1.teos VALUES ('9789530257491', 'Friikkilän pojat Mexicossa', 'Shelton Gilbert', 'huumori', 'sarjakuva');
INSERT INTO D1.teos VALUES ('9789510396230', 'Miten saan ystäviä, menestystä, vaikutusvaltaa', 'Dale Carnegien', 'opas', 'tietokirja');

INSERT INTO D1.nide VALUES ('9789510098752', 17, 7.5, 3.3, NULL, 250, 0);
INSERT INTO D1.nide VALUES ('9789510425459', 18, 8.5, 4, NULL, 500, 0);
INSERT INTO D1.nide VALUES ('9789513169121', 19, 6.5, 4.3, NULL, 150, 0);
INSERT INTO D1.nide VALUES ('9789510392317', 20, 7.5, 3.2, NULL, 750, 0);
INSERT INTO D1.nide VALUES ('9781533493460', 21, 5.9, 3.5, NULL, 150, 0);
INSERT INTO D1.nide VALUES ('9155430674', 22, 6.9, 4.3, NULL, 310, 0);
INSERT INTO D1.nide VALUES ('9156381451', 23, 7.6, 4.5, NULL, 400, 0);
INSERT INTO D1.nide VALUES ('9789510431122', 24, 5.5, 2.5, NULL, 200, 0);
INSERT INTO D1.nide VALUES ('9789523041455', 25, 3.5, 3.9, NULL, 170, 0);
INSERT INTO D1.nide VALUES ('9789530257491', 26, 9.6, 4.5, NULL, 380, 0);
INSERT INTO D1.nide VALUES ('9789510396230', 27, 4.8, 4.3, NULL, 290, 0);


-- D3:

INSERT INTO D3.teos VALUES ('9780545425117', 'Nälkäpeli', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani');
INSERT INTO D3.teos VALUES ('9789510358313', 'Vihan liekit', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani');
INSERT INTO D3.teos VALUES ('9789510358306', 'Matkijanärhi', 'Suzanne Collins', 'tieteiskirjallisuus', 'romaani');
INSERT INTO D3.teos VALUES ('9155430674', 'Elektran tytär', 'Madeleine Brent', 'romantiikka', 'romaani');
INSERT INTO D3.teos VALUES ('9156381451', 'Tuulentavoittelijan morsian', 'Madeleine Brent', 'romantiikka', 'romaani');

INSERT INTO D3.nide VALUES ('9780545425117', 12, 6.5, 2.5, NULL, 350, 0);
INSERT INTO D3.nide VALUES ('9789510358313', 13, 8.5, 2.3, NULL, 370, 0);
INSERT INTO D3.nide VALUES ('9789510358306', 14, 5.9, 4.3, NULL, 330, 0);
INSERT INTO D3.nide VALUES ('9155430674', 15, 5.9, 4.3, NULL, 310, 0);
INSERT INTO D3.nide VALUES ('9156381451', 16, 5.9, 4.3, NULL, 400, 0);


