function checkAll(obj){
    var check = $(obj).hasClass('check');
    if(check){
        $(obj).removeClass('check');
        $('.all-check .checkbox').removeClass('check');
        $('.list-seller .checkbox').removeClass('check');
        $('.list-goods .checkbox').removeClass('check');
    }else{
        $(obj).addClass('check');
        $('.all-check .checkbox').addClass('check');
        $('.list-seller .checkbox').addClass('check');
        $('.list-goods .checkbox').addClass('check');
    }
}

function checkSeller(obj){
    var check = $(obj).hasClass('check');
    if(check){
        $(obj).removeClass('check');
        $('.all-check .checkbox').removeClass('check');
        $(obj).parents('li').find('.list-goods .checkbox').removeClass('check');
    }else{
        $(obj).addClass('check');
        var sellerCheck = $('.list-seller .check');
        var seller = $('.list-seller');
        if(sellerCheck.size() == seller.size()){
            $('.all-check .checkbox').addClass('check');
        }
        $(obj).parents('li').find('.list-goods .checkbox').addClass('check');
    }
}

function checkGoods(obj) {
    var check = $(obj).hasClass('check');
    if(check){
        $(obj).removeClass('check');
        $(obj).parents('li').find('.list-seller .checkbox').removeClass('check');
        $('.all-check .checkbox').removeClass('check');
    }else{
        $(obj).addClass('check');
        var goodsCheck = $(obj).parents('.list-goods').find('.check');
        var goods = $(obj).parents('.list-goods').find('li');
        if(goodsCheck.size() == goods.size()){
            $(obj).parents('li').find('.list-seller .checkbox').addClass('check');
        }
        var allCheck = $('.cart-list .list-goods .check');
        var all = $('.cart-list .list-goods li');
        if(allCheck.size() == all.size()){
            $('.all-check .checkbox').addClass('check');
        }
    }
}

function checkDelete(){
    $('.cart-list .checkbox.check').parent('li').remove();
    $('.cart-list .checkbox.check').parent().parent('li').remove();
    $('.all-check .checkbox').removeClass('check')
}