// Generated by CoffeeScript 1.10.0
define(['jquery'], function(jquery) {
  $("#userresume_upload").click(function() {
    $("#userresume_uploadedFile").trigger('click');
  });
  $("#userresume_uploadedFile").change(function() {
    $("#userresume").submit();
  });
});
