/*
CREATE TABLE Ainesosaliitos(
  Resepti_ID INTEGER REFERENCES Resepti(ID),
  Ainesosa_ID INTEGER REFERENCES Ainesosa(ID)
);
*/

-- English table names

CREATE TABLE UserAccount(
  ID SERIAL PRIMARY KEY,
  Name varchar(120) NOT NULL,
  Password varchar(120) NOT NULL,
  Admin boolean DEFAULT FALSE
);

CREATE TABLE DrinkRecipe(
  ID SERIAL PRIMARY KEY,
  Name varchar(120) NOT NULL,
  Owner_ID INTEGER REFERENCES UserAccount(ID),
  Approved boolean DEFAULT FALSE
);

CREATE TABLE Ingredient(
  ID SERIAL PRIMARY KEY,
  Name varchar(120) NOT NULL
);
