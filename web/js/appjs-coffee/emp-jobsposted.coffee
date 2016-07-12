define [
  'jtable','moment'
], (jtable, moment) ->
  $(document).ready ->
    $('#tbljobposts').jtable
      title:  'Job Posts'
      paging: true
      pageSize: 10
      sorting: true
      defaultSorting: 'postDate DESC'
      actions:
        listAction: '/api/job/posts'
      fields:
        id:
          key:  true
          create: false
          edit: false
          list: false
        title:
          title:  'Job Title'
        postDate:
          title: 'Post Date'
          display: (data) ->
            moment.unix(data.record.post_date).format "YYYY-MM-DD"
        view_detail:
          title: ''
          dependsOn: 'id'
          display: (data) ->
            '<a href="/emp/job/detail/' + data.record.id + '">View Detail</a>'
        view_applications:
          title: ''
          display: (data) ->
            '<a href="' + data.record.id + '">View Applicaitons</a>'
    $('#tbljobposts').jtable 'load'
    return
  return


