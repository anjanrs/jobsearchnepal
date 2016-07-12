define [
  'jquery'
], (jquery) ->
  $("#userresume_upload").click ->
    $("#userresume_uploadedFile").trigger 'click'
    return
  $("#userresume_uploadedFile").change ->
    $("#userresume").submit()
    return
  return
