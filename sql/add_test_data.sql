INSERT INTO Kayttaja (Nimi, Salasana, Yllapitaja) VALUES ('Janne', 'passu123', TRUE); 
INSERT INTO Kayttaja (name, password) VALUES ('Henkka', '12345678', FALSE);

INSERT INTO Ainesosa (Nimi) VALUES ('Rommi');
INSERT INTO Ainesosa (Nimi) VALUES ('Kola');

INSERT INTO Resepti (Nimi, Kayttaja_ID, Hyvaksytty) VALUES ('Rommikola', 0, TRUE);

INSERT INTO Ainesosaliitos (Resepti_ID, Ainesosa_ID) VALUES (0,0);
INSERT INTO Ainesosaliitos (Resepti_ID, Ainesosa_ID) VALUES (0,1);