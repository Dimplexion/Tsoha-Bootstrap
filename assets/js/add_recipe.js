var ingredientCount = 2;
function setIngredientFieldCount(count)
{
    ingredientCount = count;
}

function addIngredientField(divName)
{
    var newdiv = document.createElement('div');
    newdiv.className = "form-group";
    newdiv.innerHTML = "<div class='form-group'> <label>Ainesosa " + (ingredientCount + 1) + "</label>  <input type='text' name='ingredients[]' class='form-control' value=''> </div>\n";
    newdiv.innerHTML += "<div class='form-group'> <label>Ainesosa " + (ingredientCount + 1) + " määrä (cl)</label>  <input type='text' name='ingredient_amounts[]' class='form-control' value=''> </div>\n";
    newdiv.innerHTML += "</div>\n";

    document.getElementById(divName).appendChild(newdiv);
    ingredientCount++;
}