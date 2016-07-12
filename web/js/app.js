requirejs.config({
    baseUrl: '/js/lib',
    paths: {
        appjs: '../appjs',
        jquery: '../../bower_components/jquery/dist/jquery.min',
        bootstrap: '../../bower_components/bootstrap-sass/assets/javascripts/bootstrap.min',
        bootstrapValidator: '../../bower_components/bootstrapvalidator/dist/js/bootstrapValidator.min',
        moment: '../../bower_components/moment/min/moment.min',
        bootstrapDatepicker: '../../bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
        jqueryui: '../../bower_components/jquery-ui/jquery-ui.min',
        jtable: '../../bower_components/jtable/lib/jquery.jtable.min',
        microplugin: '../../bower_components/microplugin/src/microplugin',
        sifter: '../../bower_components/sifter/sifter.min',
        selectize: '../../bower_components/selectize/dist/js/selectize.min'
    },
    shim: {
      bootstrap: {
        deps: ['jquery'],
        exports: 'bootstrap'
      },
      bootstrapValidator: {
        deps: ['bootstrap'],
        exports: 'bootstrapValidator'
      },
      bootstrapDatepicker: {
        deps: ['moment', 'bootstrap'],
        exports: 'bootstrapDatepicker'
      },
      jtable: {
        deps: ['jquery','jqueryui'],
        exports: 'jtable'
      },
      selectize:{
        deps: ['jquery','microplugin','sifter'],
        exports: 'selectize'
      }
    }
});
