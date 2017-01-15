INSERT INTO UserAccount (Username, Password, Admin) VALUES ('Janne', 'passu123', true);
INSERT INTO UserAccount (Username, Password, Admin) VALUES ('Henkka', '12345678', false);
INSERT INTO UserAccount (Username, Password, Admin) VALUES ('Jasmina', 'jasmina', false);

INSERT INTO Ingredient (Name) VALUES ('Rommi');
INSERT INTO Ingredient (Name) VALUES ('Kola');

INSERT INTO DrinkRecipe (Name, Approved, Owner_ID, Description) VALUES ('Rommikola', TRUE, 2, 'Sekoita rommi ja kola');

INSERT INTO DrinkRecipeIngredientComb (Recipe, Ingredient, Amount) VALUES (1,1,4);
INSERT INTO DrinkRecipeIngredientComb (Recipe, Ingredient, Amount ) VALUES (1,2,12);