$(function() {
  var currentIngID = 1;
  var currentStepID = 1;

  $('#addIngredientButton').click(function(event) {
    $('#ingname_' + currentIngID).after(
      "<input id=\"ingamt_" + (currentIngID + 1) + "\" type=\"text\" name=\"Ingredient Amount " + (currentIngID + 1) + "\"><input id=\"ingname_" + (currentIngID + 1) + "\" type=\"text\" name=\"Ingredient Name " + (currentIngID + 1) + "\">"
    );
    currentIngID += 1;
  });

  $('#addStepButton').click(function(event) {
    $('#step_' + currentStepID).after(
      "<input id=\"step_" + (currentStepID + 1) + "\" type=\"text\" name=\"Step " + (currentStepID + 1) + "\">"
    );
    currentStepID += 1;
  });
});
