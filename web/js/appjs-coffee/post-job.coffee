define [
  'bootstrapDatepicker',
  'bootstrapValidator'
], (bootstrapDatepicker, bootstrapValidator)->
  $('#datetimepicker1').datetimepicker
    format: 'YYYY-MM-DD'

  $('#postjob_valid_till').datetimepicker
    format: 'YYYY-MM-DD'

  $("#postjob").bootstrapValidator
    feedbackIcons :
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    fields:
      'postjob[title]':
        validators:
          notEmpty:
            message: "Job Title cannot be empty."
      'postjob[description]':
        validators:
          notEmpty:
            message: "Job Description cannot be empty/"
      'postjob[valid_till]':
        validators:
          notEmpty:
            message: "Job validity date cannot be empty/"
          date:
            format: 'YYYY-MM-DD'
            message: 'Invalid date format, plz use YYYY-MM-DD format.'
      'postjob[location]':
        validators:
          notEmpty:
            message: "Job Location cannot be empty."
      'postjob[register][fullname]':
        validators:
          callback:
            message: "Fullname cannot be empty for registration."
            callback:
              (value, validator, field) ->
                sign_in_opition = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
                return if (sign_in_opition != 'register') then true else ( value != '')
      'postjob[register][email]':
        validators:
          callback:
            message: "Email cannot be empty for registration."
            callback:
              (value, validator, field) ->
                sign_in_opition = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
                return if (sign_in_opition != 'register') then true else ( value != '')
      'postjob[register][password]':
        validators:
          callback:
            message: "Password cannot be empty for registration."
            callback:
              (value, validator, field) ->
                sign_in_opition = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
                return if (sign_in_opition != 'register') then true else ( value != '')
      'postjob[sign_in_email]':
        validators:
          callback:
            message: "Email cannot be empty for sign in."
            callback:
              (value, validator, field) ->
                sign_in_opition = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
                return if (sign_in_opition != 'sign-in') then true else ( value != '')
      'postjob[sign_in_password]':
        validators:
          callback:
            message: "Password cannot be empty for sign in."
            callback:
              (value, validator, field) ->
                sign_in_opition = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
                return if (sign_in_opition != 'sign-in') then true else ( value != '')
  .on 'change', '[name="postjob[sign_in]"]',(e) ->
    sign_in_option = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
    if sign_in_option == 'register'
      $("#sign-in-controls").hide()
      $("#register-controls").show()
    if sign_in_option == 'sign-in'
      $("#sign-in-controls").show()
      $("#register-controls").hide()
    $('#postjob').bootstrapValidator('revalidateField', 'postjob[sign_in_password]')
    $('#postjob').bootstrapValidator('revalidateField', 'postjob[sign_in_email]')
    $('#postjob').bootstrapValidator('revalidateField', 'postjob[register][fullname]')
    $('#postjob').bootstrapValidator('revalidateField', 'postjob[register][email]')
    $('#postjob').bootstrapValidator('revalidateField', 'postjob[register][password]')
    return
  .on 'success.field.bv', (e, data) ->
    sign_in_option = $("#postjob").find('[name="postjob[sign_in]"]:checked').val()
    if sign_in_option is 'register'
      if data.field is 'postjob[sign_in_password]' || data.field is 'postjob[sign_in_email]'
        data.element.closest('.form-group').removeClass('has-success')
        data.element.data('bv.icon').hide()
    if sign_in_option is 'sign-in'
      if data.field is 'postjob[register][fullname]' || data.field is 'postjob[register][email]' || data.field is 'postjob[register][password]'
        data.element.closest('.form-group').removeClass('has-success')
        data.element.data('bv.icon').hide()
    return
  return



