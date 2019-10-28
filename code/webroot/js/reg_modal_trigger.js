$(function() {
  $(".register_trigger").click(function(event) {
    $("#register_modal").fadeIn("slow");
  });

  $("#register_modal").click(function(event) {
    if(event.target.id == "register_modal" || event.target.id == "register_flex_container") {
      $("#register_modal").fadeOut("slow");
    }
  });
});
