'use strict'

$(function()
{

    //alert("to load my page on localhost with Macbook");
  //All pages
  $('#login').on('click', function(){
    $('.login-page').toggleClass("hide");
    $('main').addClass("blur");
    $('.signup-page').addClass("hide");
  });

  $('.login-page .button-cancel').on('click', function(){
    $('.login-page').addClass("hide");
    $('main').removeClass("blur");
    $('.contact').show();
  });

  $('#signup').on('click', function(){
    $('.login-page').addClass("hide");
    $('.signup-page').removeClass("hide");
  });

  $('.signup-page .button-cancel').on('click', function(){
    $('.signup-page').addClass("hide");
    $('main').removeClass("blur");
    $('.contact').show();
  });

  $('main').on('click', function(){
    $('.login-page').addClass("hide");
    $('.signup-page').addClass("hide");
    $('main').removeClass("blur");
  });

  //Contact Page
  $('#particular-no').on('click', function(){
    $('#society').removeClass("hide");
  });

  $('#particular-yes').on('click', function(){
    $('#hunt-management').addClass("hide");
  });

  //Challenge page
  $('#select-qcm').on('click', function(){
    $('#type-qcm').toggleClass("hide");
  });

  $('#select-qru').on('click', function(){
    $('#type-qru').toggleClass("hide");
  });

  //Spots List for hunt by AJAX JSON
  $('#hunt-table td').on('click', function(){
    console.log(event.target.parentElement.id);
    var tourId = event.target.parentElement.id;

    $.post('createSpot/'+event.target.parentElement.id,function(spots){
      console.log(spots);
      console.log(spots.spots[0]);
      // La réponse HTTP contient du JSON qui a été automatiquement désérialisé par jQuery.

      // Création d'une liste HTML dans la page.
      $('#spots').empty();
      var base_url = window.location.origin + window.location.pathname + '/';
      console.log(base_url);
      var action = base_url + tourId;
      var deleteTour = window.location.origin + '/nigmahunt/web/index.php/admin/deleteHunt/' + tourId;
      console.log(deleteTour);
      $('#form-spot').attr('action', action);
      $('#delete-hunt').attr('href', deleteTour);
      for(var index = 0; index < spots.spots.length; index++)
      {
      console.log(spots.spots[index]);
      console.log(index);
          // Insertion d'un spots dans la liste HTML.
          $('#spots').append('<tr id = "'+spots.spots[index].id+'")>');
          $('<td>').append(spots.spots[index].name).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].id).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].latitude).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].longitude).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].altitude).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].radius).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].picture).appendTo('#spots #'+spots.spots[index].id);
          $('<td>').append(spots.spots[index].description.substring(0,35)+'...').appendTo('#spots #'+spots.spots[index].id);
          /*if (index != spots.spots.length - 1){
      $('#spots').append('<tr>');
    }*/
      }

    });
    $('#hunt-id').html(event.target.parentElement.id);
    $('.hunt-management').removeClass("hide");
    //$('.hunt-management').show();
    $('html, body').animate({scrollTop:850}, 'slow');

  });

  $('.hunt-management .button-cancel').on('click', function(){
    $('.hunt-management').addClass("hide");
    //$('.hunt-management').hide();
    $('html, body').animate({scrollTop:850}, 'slow');
  });
});


String.prototype.nl2br = function()
{
    return this.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
}
