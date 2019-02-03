-- Tiko2018 | Harjoitustyö

-- Tietokantojen luontilauseet


CREATE SCHEMA keskusdivari;
SET SEARCH_PATH TO keskusdivari;

CREATE TABLE kayttaja (
ktunnus VARCHAR(20),
salasana VARCHAR(20) NOT NULL,
knimi VARCHAR(30) NOT NULL,
kosoite VARCHAR(50) NOT NULL,
sposti VARCHAR(50) NOT NULL,
puh VARCHAR(13) NOT NULL,
rooli VARCHAR(20) DEFAULT 'asiakas',      -- asiakas/divarintunnus_yllapitaja, rekisteröityessä rooliksi tulee automaattisesti asiakas, ylläpitäjällä tunnukset muuta kautta
PRIMARY KEY (ktunnus));

CREATE TABLE divari (
dtunnus VARCHAR(5),
dnimi VARCHAR(30) NOT NULL,
dosoite VARCHAR(50) NOT NULL,
websivu VARCHAR(50),
PRIMARY KEY (dtunnus));

CREATE TABLE teos (
isbn VARCHAR(20),       -- ISBN-tunnus: jokainen painos ja muunnelma uusintapainoksia lukuun ottamatta saa omansa
tnimi VARCHAR(100) NOT NULL,
tekija VARCHAR(50) NOT NULL,
tyyppi VARCHAR(20) DEFAULT '',     -- romaani/kuvakirja/sarjakuva...
luokka VARCHAR(20) DEFAULT '',     -- romantiikka/seikkailu/sikailu...
PRIMARY KEY (isbn));

CREATE TABLE nide (
isbn VARCHAR(20),
ntunnus INT,
hinta NUMERIC(5,2) NOT NULL CHECK (hinta > 0),
sis_osto_hinta NUMERIC(5,2) NOT NULL CHECK (sis_osto_hinta > 0),
myyntipvm DATE,
paino INT NOT NULL,     -- grammoina
tila INT DEFAULT 0,     -- vapaa 0/varattu 1/myyty 2
dtunnus VARCHAR(5),
PRIMARY KEY (ntunnus),
FOREIGN KEY (isbn) REFERENCES teos,
FOREIGN KEY (dtunnus) REFERENCES divari);

CREATE TABLE tilaus (
ktunnus VARCHAR(20),
ntunnus INT,
tila INT DEFAULT 0,    -- prosessissa 0/maksettu ja lähetetty 1 (tehtävien yksinkertaistemisen vuoksi turha laittaa näille eri arvot)
PRIMARY KEY (ntunnus),
FOREIGN KEY (ntunnus) REFERENCES nide,
FOREIGN KEY (ktunnus) REFERENCES kayttaja);



CREATE SCHEMA D1;
SET SEARCH_PATH TO D1;

CREATE TABLE teos (
isbn VARCHAR(20),       -- ISBN-tunnus: jokainen painos ja muunnelma uusintapainoksia lukuun ottamatta saa omansa
tnimi VARCHAR(100) NOT NULL,
tekija VARCHAR(50) NOT NULL,
tyyppi VARCHAR(20) DEFAULT '',     -- romaani/kuvakirja/sarjakuva...
luokka VARCHAR(20) DEFAULT '',     -- romantiikka/seikkailu/sikailu...
PRIMARY KEY (isbn));

CREATE TABLE nide (
isbn VARCHAR(20),
ntunnus INT,
hinta NUMERIC(5,2) NOT NULL CHECK (hinta > 0),
sis_osto_hinta NUMERIC(5,2) NOT NULL CHECK (sis_osto_hinta > 0),
myyntipvm DATE,
paino INT NOT NULL,     -- grammoina
tila INT DEFAULT 0,     -- vapaa 0/varattu 1/myyty 2
PRIMARY KEY (ntunnus),
FOREIGN KEY (isbn) REFERENCES teos);




CREATE SCHEMA D3;
SET SEARCH_PATH TO D3;

CREATE TABLE teos (
isbn VARCHAR(20),       -- ISBN-tunnus: jokainen painos ja muunnelma uusintapainoksia lukuun ottamatta saa omansa
tnimi VARCHAR(100) NOT NULL,
tekija VARCHAR(50) NOT NULL,
tyyppi VARCHAR(20) DEFAULT '',     -- romaani/kuvakirja/sarjakuva...
luokka VARCHAR(20) DEFAULT '',     -- romantiikka/seikkailu/sikailu...
PRIMARY KEY (isbn));

CREATE TABLE nide (
isbn VARCHAR(20),
ntunnus INT,
hinta NUMERIC(5,2) NOT NULL CHECK (hinta > 0),
sis_osto_hinta NUMERIC(5,2) NOT NULL CHECK (sis_osto_hinta > 0),
myyntipvm DATE,
paino INT NOT NULL,     -- grammoina
tila INT DEFAULT 0,     -- vapaa 0/varattu 1/myyty 2
PRIMARY KEY (ntunnus),
FOREIGN KEY (isbn) REFERENCES teos);

-- triggeri
CREATE TRIGGER paivita_keskusdivarin_tiedot
AFTER INSERT ON D3.nide
FOR EACH ROW 
EXECUTE PROCEDURE paivita_keskusdivarin_tiedot();

CREATE OR REPLACE FUNCTION paivita_keskusdivarin_tiedot() 
RETURNS TRIGGER AS 
$BODY$
BEGIN
    INSERT INTO
        keskusdivari.nide(isbn, ntunnus, hinta, sis_osto_hinta, myyntipvm, paino, tila, dtunnus)
        VALUES(new.isbn, new.ntunnus, new.hinta, new.sis_osto_hinta, new.myyntipvm, new.paino, new.tila, 'D3');

        RETURN new;
END;
$BODY$ 
language plpgsql;


