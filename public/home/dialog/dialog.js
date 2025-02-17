function dialogMsg(msg){
    var delay = arguments[1] ? arguments[1] : 2000;
    var width = arguments[2] ? arguments[2] : 240;

    setTimeout(function(){
        $('#dialog').dialogBox({
            content: msg,
            width: width,
            autoHide: true,
            time: delay
        });
    }, 400);
}

function dialogMsgOkno(msg, fn){
    $('#dialog').dialogBox({
        hasClose: true,
        hasBtn: true,
        width: 240,
        confirmValue: 'Yes',
        confirm: function(){
            fn();
        },
        cancelValue: 'No',
        content: msg
    });
}