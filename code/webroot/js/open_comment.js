$(function() {
  $('.comment_trigger').click(function(event) {
    console.log('#comment_box_' + $(event.target).parent().attr('id'));
    $('#comment_box_' + $(event.target).parent().attr('id')).parent().slideToggle(600);
  });

  $('#post_comment_trigger').click(function(event) {
    $('#post_comment_container').slideToggle(600);
  });
});
