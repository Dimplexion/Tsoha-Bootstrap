
CREATE TABLE UserAccount(
  ID SERIAL PRIMARY KEY,
  Username varchar(120) NOT NULL,
  Password varchar(120) NOT NULL,
  Admin boolean DEFAULT FALSE
);

CREATE TABLE DrinkRecipe(
  ID SERIAL PRIMARY KEY,
  Name varchar(120) NOT NULL,
  Owner_ID INTEGER REFERENCES UserAccount(ID),
  Approved boolean DEFAULT FALSE,
  Description TEXT
);

CREATE TABLE Ingredient(
  ID SERIAL PRIMARY KEY,
  Name varchar(120) NOT NULL UNIQUE
);

CREATE TABLE DrinkRecipeIngredient(
  ID SERIAL PRIMARY KEY,
  Recipe INTEGER REFERENCES DrinkRecipe(ID) ON DELETE CASCADE,
  Ingredient INTEGER REFERENCES Ingredient(ID) ON DELETE CASCADE,
  Amount REAL NOT NULL
);
