$(function(){

    $('.changeQuantity').change(function(){
        $.post('/cart/change',{
            dataId: $(this).attr('data-id'),
            dataColor: $(this).attr('data-color') ? $(this).attr('data-color'):null,
            dataSize: $(this).attr('data-size') ? $(this).attr('data-size'):null,
            dataQuantity: $(this).find(':selected').text()
        },function(r){
            if(r.code == 0){
                alert('Chúng tôi không tìm thấy sản phẩm này');
            }else{
                location.reload();
            }
        });
    });

    var hide = true;
    var t = $('.deleteCart');
    var clicks = true;

    t.click(function() {
        if (clicks) {
            $(this).text('OK');
            $(this).removeClass('fa fa-trash-o');
            clicks = false;
        } else {
            $.post('/cart/remove',{
                dataId: $(this).attr('data-id'),
                dataColor: $(this).attr('data-color') ? $(this).attr('data-color'):null,
                dataSize: $(this).attr('data-size') ? $(this).attr('data-size'):null
            },function(r){
                if(r.code == 0){
                    alert('We are can not find product on system');
                }else if(r.code == 1){
                    location.reload();
                }
            });
            clicks = true;
        }
    });

    $('html').click(function(e){
        if ($(e.target).hasClass('deleteCart')) {
            return false;
        }
        if(hide){
            t.addClass('fa fa-trash-o').text('');
        }
        clicks = true;
        hide = true;
    });


});