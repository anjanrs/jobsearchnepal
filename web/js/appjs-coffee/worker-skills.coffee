define [
  'selectize'
], (selectize) ->
    $("#form_skills").selectize
      plugins: ['remove_button']
      valueField: 'skill'
      labelField: 'skill'
      searchField: 'skill'
      create: true
      load: (query, callback) ->
        if !query.length
          return callback()
        $.ajax
          url: '/api/worker/skills/' + encodeURIComponent(query)
          type: 'GET'
          error: () ->
            callback()
          success: (res) ->
            callback res.slice(0,10)
        return
    return