define [
  'bootstrapValidator'
], (bootstrapValidator) ->
    $('#userregistration').bootstrapValidator
      feedbackIcons :
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      fields :
        'userregistration[email]':
          validators:
            notEmpty:
              message: 'Email is required'
            emailAddress :
              message: 'Invalid email address'
         'userregistration[fullname]' :
           validators:
             notEmpty:
               message: 'Name is required'
         'userregistration[password]':
           validators:
             notEmpty:
               message: 'Password is required'
             stringLength:
               min: 6
               message: 'Minimum password must be 6 characters'
    return
