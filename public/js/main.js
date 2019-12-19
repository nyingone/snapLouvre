window.onerror = function (msg, url, noLigne, noColonne, erreur) {
    var chaine = msg.toLowerCase();
    var souschaine = "script error";
    if (chaine.indexOf(souschaine) > -1) {
        alert('Script Error : voir la Console du Navigateur pour les Détails');
    } else {
        var message = [
            'Message : ' + msg,
            'URL : ' + url,
            'Ligne : ' + noLigne,
            'Colonne : ' + noColonne,
            'Objet Error : ' + JSON.stringify(erreur)
        ].join(' - ');

        alert(message);
    }
    return false;
};


jQuery(document).ready(function () {

   // var array = ["2019-10-01","2019-10-02","2019-10-03"];

        var array = $('.js-full-booked').data('fullBooked');

        console.log(array);

    $("[id$= 'expectedDate']").datepicker();
    //$( "#datepicker" ).datepicker();

    $.datepicker.setDefaults({
        showOn: "both",

        buttonImageOnly: true,
        buttonImage: "calendar.gif",
        firstDay: 1,
        disabledDates: array,
        minDate: new Date(),
        maxDate: "+3m",
        dateFormat: 'dd/mm/yy',

        beforeShowDay: function (date) {
            var day = date.getDay();

            var year = date.getYear();
            var month = date.getMonth();
            var curDay = date.getDate();

            if (day == 2 || day == 0) {
                return [false];
            } else if ((month == 0 && curDay == 1) || (month == 11 && curDay == 25) || (month == 10 && curDay == 1)) {
                return [false];
            } else if ($.inArray($.datepicker.formatDate('yy-mm-dd', date), array) > -1) {
                return [false, "", "Booked out"];
            } else {
                return [true, '', "available"];
            }

        },
        buttonText: "Select date"

    });


});


