

var sid = $('#sid').val();
var rid = $('#rid').val();

$('.chat-header .menu .menu-ico').click(function () {
    $('.chat-header .menu ul.list').slideToggle('fast');
});
$(document).click(function () {
    $(".chat-header .menu ul.list").slideUp('fast');
});
$(".chat-header .menu ul.list,.chat-header .menu .menu-ico").click(function (e) {
    e.stopPropagation();
});
$('.chat-inp .emoji').click(function () {
    $('.emoji-dashboard').slideToggle('fast');

});
$(document).click(function () {
    $(".emoji-dashboard").slideUp('fast');
});
$(".chat-header .menu ul.list,.chat-inp .emoji").click(function (e) {
    e.stopPropagation();
});
$('.emoji-dashboard li .em').click(function () {
    var emo = $(this).css('background-image').split('"')[1];
    $('.chat-inp .input').find('div').remove();
    $('.chat-inp .input').append('<img style="width:30px;height:30px;" src="' + emo + '">');
    //$(".emoji-dashboard").slideUp('fast');

});
$('.chat-inp .opts .send').click(function () {
    var val = $('.chat-inp .input').html();
    val = val.trim();
    if (val != null) {
        $('.conversation-bs .conversation-container').append(`<div id="sending" class="message sent" style="border-color:green"><span>...</span></div>`);
        scrollToTop();

        var msg = val;
        var data = { sid: sid, rid: rid, msg: msg };
        // Variable to hold request
        var request;
        // Fire off the request to /form.php
        request = $.ajax({
            url: "send.php",
            type: "post",
            data: data
        });
        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR) {
            $('#sending').remove();
            var res = JSON.parse(response);
            res = res[0];

            buildSentMSG(res);


        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown) {
            // Log the error to the console
            $('#sending').remove();
            console.error("The following error occurred: " + textStatus, errorThrown);
            $('.conversation-bs .conversation-container').append(`<div class="message sent alert alert-error" style="border-color:red">${val}<span class="metadata"><span class="time">${moment().format('h:mm A')}</span><span class="fa fa-remove" style="color:brown"></span></span></div>`);
            scrollToTop();
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
        });
    }
    $('.chat-inp .input').html('');
    $('.chats-text-cont div').remove();
});
$('input,.input').each(function () {
    tmpval = $(this).text().length;
    if (tmpval != '') {
        $(this).prev().addClass('trans');
        $(this).parent().addClass('lined');
    }
});
$('input,.input').focus(function () {
    $(this).prev().addClass('trans');
    $(this).parent().addClass('lined');
    $(this).keypress(function (e) {
        if (e.which == 13) {
            //$('#sending').remove();
            //$('.chat-inp .opts .send').click();
        }
    });
}).blur(function () {
    if ($(this).text().length == '') {
        $(this).prev().removeClass('trans');
        $(this).parent().removeClass('lined');
    }
});



$(function () {
    $("#sidebarCollapse").on("click", function () {
        $("#sidebar").toggleClass("active");
        $(this).toggleClass("active");
    });
    buildMSG();
});

function buildMSG() {
    if (rid == null) {

        $('.loading').remove();
        $('.conversation-bs .conversation-container').append('<div class="alert text-center"><em><i class="em em-point_left"></i> Click on a chat to begin, or start one from users profile..</em></div>');
        $('.chat-inp').remove();
        return;
    }

    $('.conversation-bs .conversation-container').empty();

    $.post('retrieve.php', { sid: sid, rid: rid }, function (data, status) {
        // Log the response to the console

        $('.loading').remove();
        data = JSON.parse(data);
        if (data.length > 0) {
            let index = 0;

            let previousDate = "";
            let currentDate = "";
            let today = new Date()
            $('.conversation-bs .conversation-container').append(`<div class="text-center message date">today</div>`);

            data.forEach(function (res) {

                if (index > 0) {
                    previousDate = moment(data[index - 1].time).format(
                        "L"
                    );

                    currentDate = moment(res.time).format("L");
                }


                if (previousDate && !moment(currentDate).isSame(previousDate, "day")) {

                    $('.date:last').html(moment(previousDate).format("Do MMM YY"));
                    $('.conversation-container').append(`<div class="text-center message date">today</div>`);

                }
                else if (previousDate && moment(currentDate).isSame(previousDate, "day")) {

                    if (moment(currentDate).isSame(new Date, "day")) {

                        $('.date:last').html('today');
                    } else {
                        $('.date:last').html(moment(currentDate).format("Do MMM YY"));
                    }
                }


                if (res.sender == sid) {
                    if (res.seen == 1)
                        var fill_color = '#4fc3f7';
                    else
                        var fill_color = 'grey';

                    $('.conversation-bs .conversation-container').append(`<div class="message sent" id="${res.id}" seen="${res.seen}" >${res.msg}<span class="metadata">
                <span class="time">${moment(res.time).format('h:mm A')}</span><span class="tick">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack"  x="2063" y="2076"><path class="msg-dblcheck-ack" d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="${fill_color}"/></svg></span></span></div>`);

                } else {

                    $('.conversation-bs .conversation-container').append(`<div class="message recieved" id="${res.id}" seen="${res.seen}" style="background: #fff;
  border-radius: 0px 5px 5px 5px;float: left;  border-width: 0px 10px 10px 0;
  border-color: transparent #fff transparent transparent;
  top: 0;
  left: -10px;" >${res.msg}<span style="padding: 0 0 0 16px;" class="metadata"><span class="time">${moment(res.time).format('h:mm A')}</span></span></div>`);

                }

                index = index + 1;
            });
            scrollToTop();
        } else {

            $('.conversation-bs .conversation-container').html('<div class="text-center"><i>Be the first to say hi..</i></div>');
        }
    });

}


function checkNewMessage() {

    checkNewMessages();
    checkSeen();
    checkTyping();
    var lastId = parseInt($('.recieved:last').attr('id'));
    $.post('retrieve-new.php', { sid: rid, rid: sid }, function (data, status) {
        data = JSON.parse(data);
        if (data.length > 0) {
            // Log the response to the console
            var lastItem = data[data.length - 1];
            var lastIndex = parseInt(lastItem.id);
            if (lastIndex > lastId) {
                if (lastItem.reciever == sid) {
                    setSeen(lastIndex);

                }
            }
        }
    });
}



function checkNewMessages() {

    var sess_user = $('#sess_user').val();
    var lastId = $('.msg-text:first').attr('id');
    //console.log('checked');
    $.post('retrieve-new-msgs.php', { user_id: sess_user }, function (data, status) {
        console.log(data);
        data = JSON.parse(data);
        if (data.length > 0) {

            //console.log(data);
            var ids = new Array();
            var user_id;
            var msg_text;
            var msg;
            var msg_time;
            var user_id;
            // Log the response to the console
            var lastItem = data[0];
            var lastIndex = parseInt(lastItem.id);

            //console.log('lastItem', lastItem.id);

            //console.log('lastId', lastId);
            if (lastIndex > lastId) {

                $('.msg-list').empty();
                data.forEach(function (res) {

                    if (res.sender == sess_user) {
                        user_id = res.reciever;
                    } else {
                        user_id = res.sender;
                    }
                    if (!(ids.includes(user_id))) {
                        $.get('get_user.php', { user_id: user_id }, function (data, status) {
                            data = JSON.parse(data);
                            var user = data[0];
                            if (res.type == 'image') {
                                msg_text = $('<a href="chat?uname=${user.username}&dp=${user.dp}&uid=${user.id}&last_active=${user.last_active}"><i class="fa fa-file"></i>');
                            } else {

                                $.get('count-new-chats.php', { sid: user_id, rid: sess_user }, function (data, status) {

                                    data = JSON.parse(data);
                                    var count = data;
                                    var counter;
                                    if (count > 0) {
                                        counter = $(`<sup class=""><span class="badge"><span class="msg-count" style="color:brown">${count}</span></span></sup></a>`);
                                    } else {
                                        counter = $('</a>');
                                    }

                                    msg_text = $(`<a href="chats-list.php?uname=${user.username}&dp=${user.dp}&uid=${user.id}&last_active=${user.last_active}"><strong><span id="${res.id}" class="msg-text" style="color:black; font-weight:bold" font-size="10px">${res.msg}</span></strong>`);
                                    msg_text.append(counter);

                                    msg_time = $(`<em class="msg-time">${moment(res.time).startOf('minute').fromNow()}</em></li>`);

                                    msg = $(`<li style="padding:3px; color:grey; !important" class="list-group-item"><a href="user?user=${user.username}" > ${user.username} 
                                    <br><img style="width:30px;height:30px" src="${user.dp}" /> 
                                      </a>`);
                                    msg.append(msg_text);
                                    msg.append(msg_time);
                                    $('.msg-list').append(msg);

                                });

                            }


                        });

                    }
                    ids.push(user_id);

                });


                lastId = lastIndex;
            }

        }

    });
}




function checkSeen() {
    var lastId = parseInt($('.sent:last').attr('id'));
    var seenLast = $('.sent:last').attr('seen');
    if (seenLast == '0') {
        $.post('retrieve-seen.php', { cid: lastId }, function (data, status) {

            data = JSON.parse(data);
            var res = data[0];
            if (res.seen == '1' && res.sender == sid) {
                //console.log(data);
                $('.msg-dblcheck-ack').attr('fill', '#4fc3f7');
                $('.sent:last').attr('seen', '1');
                //buildMSG();
            }
        });
    }
}

function setSeen(cid) {
    $.post('set-seen.php', { sid: rid, rid: sid, cid: cid }, function (data, status) {
        data = JSON.parse(data);
        var res = data[0];
        buildRecievedMSG(res);
    });
};

function scrollToTop() {

    var $container = $('.conversation-bs .conversation-container');
    var $scrollTo = $('.message:last');
    $container.scrollTop(
        $scrollTo.offset().top - $container.offset().top + $container.scrollTop());
}



setInterval(checkNewMessage, 500);


// check if a user is typing and send it to server
var messageTextField = $('#text-input');
var canPublish = true;
var throttleTime = 200; //0.2 seconds

messageTextField.on('keyup', function (event) {

    if (canPublish) {
        $.post('user-typing.php', { sid: sid, rid: rid });
        //console.log('publishing');
        canPublish = false;

        setTimeout(function () {
            $.post('not-typing.php', { sid: sid, rid: rid });
            //console.log('notpublishing');
            canPublish = true;
        }, throttleTime);
    }
});



var clearInterval = 2000; //0.9 seconds
var clearTimerId;
function checkTyping() {
    // console.log('checking typing');
    $.post('check-typing.php', { sid: sid, rid: rid }, function (data, status) {
        data = JSON.parse(data);
        if (data.length > 0) {
            var res = data[0];
            if (res.typing == 1 && res.reciever == sid && $("#typing").length == 0) {

                console.log('checking typing res', res.typing);
                $('.conversation-bs .conversation-container').append(`<div class="message recieved"  id="typing" style="background:#fff;
                                        border-radius: 0px 5px 5px 5px; float:left; border-width: 0px 10px 10px 0;
                                        border-color: transparent #fff transparent transparent;
                                        top: 0;
                                        left:-10px; ">typing...</div>`);
                scrollToTop();
                //restart timeout timer
                clearTimeout(clearTimerId);
                clearTimerId = setTimeout(function () {
                    //clear user is typing message
                    $('#typing').remove();
                }, clearInterval);
            }
        }
    });
}


document.getElementById('file').onchange = function () {
    $('.chat-container').append('<div class="loading">Loading &#8230;</div>');
    var sid = $('#sid').val();
    var rid = $('#rid').val();
    formdata = false;

    if (window.FormData) {
        formdata = new FormData();
    }

    file = $('input[type=file]')[0].files[0];

    if (formdata) {
        formdata.append("file", file);
        formdata.append("sid", sid);
        formdata.append("rid", rid);
    }

    if (formdata) {
        $.ajax({
            url: "chat-upload.php",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (res) {
                $('.loading').remove();
                res = JSON.parse(res);
                res = res[0];
                if (res.uploadOk == 0) {
                    $('.conversation-bs .conversation-container').append(`<div class="message sent alert alert-warning" ><i>${res[0].error}</i></i><span class="metadata"><span style="color:brown" class="fa fa-warning"></span></span></div>`);
                    scrollToTop();

                } else {
                    buildRecievedMSG(res);
                }
            }
        });
    }

};

function buildSentMSG(res) {
    if (res.seen == 1)
        var fill_color = '#4fc3f7';
    else
        var fill_color = 'grey';
    $('.conversation-bs .conversation-container').append(`<div class="message sent" id="${res.id}" seen="${res.seen}" >${res.msg}<span class="metadata">
    <span class="time">${moment(res.time).format('h:mm A')}</span><span class="tick">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path  class="msg-dblcheck-ack" d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="${fill_color}"/></svg></span></span></div>`);
    scrollToTop();

}

function buildRecievedMSG(res) {
    $('.conversation-bs .conversation-container').append(`<div class="message recieved" id="${res.id}" seen="${res.seen}" style="background: #fff;
  border-radius: 0px 5px 5px 5px;float: left;  border-width: 0px 10px 10px 0;
  border-color: transparent #fff transparent transparent;
  top: 0;
  left: -10px;" >${res.msg}<span style="padding: 0 0 0 16px;" class="metadata"><span class="time">${moment(res.time).format('h:mm A')}</span></span></div>`);
    scrollToTop();


}

function goBack() {
    window.history.back();
}








