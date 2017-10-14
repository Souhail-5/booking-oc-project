var moment = require('moment/moment.js');
require('@fengyuanchen/datepicker/dist/datepicker.css');
require('@fengyuanchen/datepicker/dist/datepicker.js');

$.fn.datepicker.languages['fr-FR'] = {
  format: 'yyyy-mm-dd',
  days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
  daysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
  daysMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
  weekStart: 1,
  months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
  monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec']
};
$.fn.datepicker.setDefaults({
  language: 'fr-FR'
});

var $collectionHolder;
$(document).ready(function() {
  $collectionHolder = $('#guichet-tickets');
  $collectionHolder.data('index', $collectionHolder.find('.ticket').length);
  $('[data-toggle="datepicker"]').datepicker({
    inline: true,
    container: $('[data-toggle="datepicker-container"]'),
    startDate: new Date()
  });
  // $.post( "/billetterie/ajax/event/visite-musee-louvre/unavailability", function( unavailableEventPeriods ) {
  //   $('[data-toggle="datepicker"]').datepicker({
  //     inline: true,
  //     container: $('[data-toggle="datepicker-container"]'),
  //     startDate: new Date(),
  //     filter: function(date) {
  //       var r = true;

  //       $( unavailableEventPeriods ).each(function (i, period) {
  //         if (
  //           period.fullDate
  //           && moment( period.fullDate, 'YYYY-MM-DD' ).isSame( moment( date ) )
  //         ) {
  //           r = false;
  //           return false;
  //         }
  //         if (
  //           period.p_type
  //           && period.p_type == 'month-day_nbr'
  //         ) {
  //           let dd = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
  //           let mm = date.getMonth() < 10 ? '0' + (date.getMonth()+1) : date.getMonth()+1;
  //           let mmdd = mm + '-' + dd;
  //           r = mmdd != period.p_start;
  //           return r;
  //         }
  //         if (
  //           period.p_type
  //           && period.p_type == 'day'
  //           && date.getDay() == period.p_start
  //         ) {
  //           r = false;
  //           return false;
  //         }
  //       });

  //       return r;
  //     }
  //   });
  // });
});

$(document).on('pick.datepicker', function (e) {
  if (e.view != 'day') { return false; }
  $collectionHolder.empty();
  $.post( "/billetterie/ajax/event/visite-musee-louvre/"+ moment(e.date).format('YYYY-MM-DD') +"/tickets", function( data ) {
    $(data).each(function (i, ticket) {
      addTicketForm($collectionHolder, ticket)
    });
  });
});

function addTicketForm($collectionHolder, ticket) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;

    $newForm = $(newForm.replace(/__name__/g, index));
    $collectionHolder.data('index', index + 1);

    $newForm.addClass('ticket');
    $newForm.prepend('<h3>Ticket '+ ticket.name +'</h3>');
    $newForm.children('#qs_bookingbundle_order_tickets_'+ index +'_id').val(ticket.id)
    $newForm.appendTo($collectionHolder);
}
