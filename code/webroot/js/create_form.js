$(function() {
  var currentIngID = $('.ing_amt_template').length;
  var currentStepID = $('.ing_step_template').length;

  $('#addIngredientButton').click(function(event) {
    $('#ingname_' + currentIngID).parent().after(
      `<div class='create_recipe_input_wrapper'>
      <input id="ingamt_` + (currentIngID + 1) + `" class="ing_amt_template" type="text" placeholder="Ingredient Amount" name="Ingredient Amount ` + (currentIngID + 1) + `">
      </div>
      <div class='create_recipe_input_wrapper'>
      <input id="ingname_` + (currentIngID + 1) + `" type="text" placeholder="Ingredient Name" name="Ingredient Name ` + (currentIngID + 1) + `">
      </div>`
    );
    currentIngID += 1;
  });

  $('#addStepButton').click(function(event) {
    $('#step_' + currentStepID).parent().after(
      `<div class='create_recipe_input_wrapper'>
      <input id="step_` + (currentStepID + 1) + `" class="ing_step_template" type="text" placeholder="Recipe Step" name="Step ` + (currentStepID + 1) + `">
      </div>`
    );
    currentStepID += 1;
  });
});
