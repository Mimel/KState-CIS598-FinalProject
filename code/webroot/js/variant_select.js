$(function() {
  var current_recipe_id = 'recipe_prep_block';

  $('.recipe_variant_link').click(function(event) {
    $('#' + current_recipe_id).hide();
    if(event.target.id == 'recipe_base_recipe') {
      $('#recipe_prep_block').show();
      current_recipe_id = 'recipe_prep_block';
    } else {
      $('#variant_' + event.target.id).css('display', 'flex');
      current_recipe_id = 'variant_' + event.target.id;
    }

    console.log(event.target.id)
  });
});
