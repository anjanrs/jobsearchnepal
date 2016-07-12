define [
  'jquery'
], (jquery) ->
  $('#jobapplication_uploadfile').click ->
    $('#jobapplication_uploadedFile').trigger 'click'
    return
  $('#jobapplication_uploadedFile').change ->
    filename = $('#jobapplication_uploadedFile').val().split('\\').pop()
    filename = 'Uploaded File (' + $('#jobapplication_uploadedFile').val().split('\\').pop() + ')'
    $('#new-resume label label').text filename
    $('#new-resume').show()
    $('#jobapplication_resumeselect_0').prop "checked",true
    return
  return