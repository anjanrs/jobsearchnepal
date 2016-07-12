define [
  'jquery'
], (jquery) ->
  $("#screenidentity_upload").click ->
    $("#screenidentity_uploadedFile").trigger 'click'
    return
  return