$(function() {
  $(".register_trigger").click(function() {
    $("#register_modal").fadeIn("slow");
  });

  $("#register_modal").click(function() {
    $("#register_modal").fadeOut("slow");
  });
});
