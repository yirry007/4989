var M = {

};

function dialogMsg(msg){
    var fn = arguments[1] ? arguments[1] : function(){};
    var title = arguments[2] ? arguments[2] : '4989 Market Notice';
    var btn = arguments[3] ? arguments[3] : {'confirm':'确定'};

    M.dialog2 = jqueryAlert({
        'title': title,
        'content' : msg,
        'modal'   : true,
        'buttons' :{
            [btn.confirm] : function(){
                M.dialog2.close();
                fn();
            }
        }
    })
}

function dialogMsgOkno(msg, fn){
    var title = arguments[2] ? arguments[2] : '4989 West Market Notice';
    var btn = arguments[3] ? arguments[3] : {'confirm':'Yes', 'cancel':'No'};
    
    M.dialog3 = jqueryAlert({
        'title'   : title,
        'content' : msg,
        'modal'   : true,
        'buttons' :{
            [btn.cancel] : function(){
                M.dialog3.close();
            },
            [btn.confirm] : function(){
                M.dialog3.close();
                fn();
            }
        }
    })
}