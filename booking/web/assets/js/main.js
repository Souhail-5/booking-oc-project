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

var guichetEventRegex = new RegExp(/\/billetterie\/guichet\/([\w-]{1,})\/?\??/, 'i');
var $collectionHolder;
$(document).ready(function() {
  $collectionHolder = $('#guichet-tickets');
  $collectionHolder.data('index', $collectionHolder.find('.ticket').length);
  $('#qs_bookingbundle_order_eventDate').datepicker({
    inline: true,
    container: $('[data-toggle="datepicker-container"]'),
    startDate: new Date()
  });
  // $.post( "/billetterie/ajax/event/visite-musee-louvre/unavailability", function( unavailableEventPeriods ) {
  //   $('#qs_bookingbundle_order_eventDate').datepicker({
  //     inline: true,
  //     container: $('[data-toggle="datepicker-container"]'),
  //     startDate: new Date(),
  //     filter: function(date) {
  //       var r = true;

  //       $( unavailableEventPeriods ).each(function (i, period) {
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
  $initialDate = $('#qs_bookingbundle_order_eventDate').datepicker('getDate');
  getTickets($initialDate);

  $(':submit.stripe-checkout').on('click', function(event) {
      event.preventDefault();
      var $button = $(this);
      var opts = $.extend({}, $button.data(), {
          token: function(result) {
              $('#checkout-wrap').append($('<input>').attr({ type: 'hidden', name: 'stripeToken', value: result.id })).submit();
          }
      });
      StripeCheckout.open(opts);
  });
});

$(document).on('pick.datepicker', function (e) {
  if (e.view != 'day') { return false; }
  getTickets(e.date);
});

function getTickets(date) {
  if (!window.location.href.match(guichetEventRegex)[1]) return false;
  $.post("/billetterie/ajax", {
      eventSlug: window.location.href.match(guichetEventRegex)[1],
      date: moment(date).format('YYYY-MM-DD'),
      action: 'getTickets'
    }, function( data ) {
      $collectionHolder.children('.ticket').remove();
      $collectionHolder.children('.alert').addClass('hide');
      if (!data.length) $collectionHolder.children('.alert').removeClass('hide');
      $(data).each(function (i, ticket) {
        addTicketForm($collectionHolder, ticket)
      });
    }
  );
}

function addTicketForm($collectionHolder, ticket) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;

    $newForm = $(newForm.replace(/__name__/g, index));
    $collectionHolder.data('index', index + 1);

    $newForm.addClass('ticket');
    $newForm.prepend('<p class="font-095 meta-color">'+ ticket.description +'.</p>');
    $newForm.prepend('<h4 class="font-700">Billet '+ ticket.name +'</h4>');
    $newForm.children('#qs_bookingbundle_order_tickets_'+ index +'_id').val(ticket.id)
    $newForm.appendTo($collectionHolder);
}
