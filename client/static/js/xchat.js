



var userID = $('#sid').val();
var request;
setInterval(function () {
    request = $.ajax({
        url: "set_active.php",
        type: "get",
        data: 'user_id=' + userID
    });

    request.done(function (response, textStatus, jqXHR) {

    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown) {
        console.log('active set error ' + errorThrown);
    });
}, 10000)

//check online status of user on intervals
setInterval(function () {
    var userID = $('#rid').val();
    request = $.ajax({
        url: "last_active.php",
        type: "get",
        data: 'user_id=' + userID
    });

    request.done(function (response, textStatus, jqXHR) {
        // Log a message to the console
        var res = JSON.parse(response);
        if (res.status == 'online') {
            $('.online-status').html('<span class="fa fa-circle" style="color:green"></span><span> online</span>')
        }
        else {
            $('.online-status').html(`<span class="fa fa-circle" style="color:grey"></span><span>offline</span>`)
        }
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown) {
        console.log('active check set error ' + errorThrown);
    });
}, 1000)


$(document).ready(function () {
 
    var visitortimezone = moment.tz.guess();
    $.ajax({
        type: "GET",
        url: "timezone.php",
        data: 'time=' + visitortimezone,
        success: function () {
            //location.reload();
        }
    });
});
