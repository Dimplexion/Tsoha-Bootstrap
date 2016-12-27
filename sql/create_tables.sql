CREATE TABLE Kayttaja(
  ID SERIAL PRIMARY KEY, 
  Nimi varchar(120) NOT NULL,
  Salasana varchar(120) NOT NULL,
  Yllapitaja boolean DEFAULT FALSE
);

CREATE TABLE Resepti(
  ID SERIAL PRIMARY KEY,
  Nimi varchar(120) NOT NULL,
  Kayttaja_ID INTEGER REFERENCES Kayttaja(ID),
  Hyvaksytty boolean DEFAULT FALSE
);

CREATE TABLE Ainesosa(
  ID SERIAL PRIMARY KEY,
  Nimi varchar(120) NOT NULL,
);

CREATE TABLE Ainesosaliitos(
  Resepti_ID INTEGER REFERENCES Resepti(ID),
  Ainesosa_ID INTEGER REFERENCES Ainesosa(ID),
);