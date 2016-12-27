INSERT INTO Kayttaja (Nimi, Salasana, Yllapitaja) VALUES ('Janne', 'passu123', TRUE); 
INSERT INTO Kayttaja (Nimi, Salasana, Yllapitaja) VALUES ('Henkka', '12345678', FALSE);

INSERT INTO Ainesosa (Nimi) VALUES ('Rommi');
INSERT INTO Ainesosa (Nimi) VALUES ('Kola');

INSERT INTO Resepti (Nimi, Hyvaksytty) VALUES ('Rommikola', TRUE);

INSERT INTO Ainesosaliitos (Resepti_ID, Ainesosa_ID) VALUES (1,1);
INSERT INTO Ainesosaliitos (Resepti_ID, Ainesosa_ID) VALUES (1,2);