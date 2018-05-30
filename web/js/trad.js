'use strict'

/*======== Constructor ES6 ========*/
class Trad{
  Constructor(){

  }

  init(){
    /*new google.translate.TranslateElement(
        {pageLanguage: 'en'},
        'page-lang'
    );*/
  }

  page(language){
    /*new google.translate.TranslateElement(
        {pageLanguage: language},
        'page-lang'
    );*/
  }

  listener(){
    $('#select-lang').on('change', function(){
      console.log($('#select-lang').val());
      /*new google.translate.TranslateElement(
          {pageLanguage: language},
          'page-lang'
      );*/
    });
    }

}
