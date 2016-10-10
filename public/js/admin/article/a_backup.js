$(function(){

    function insertImage(){

        $('.listImage li').click(function(){
            $(this).toggleClass('active');
        });

        function updateImage(d){
            if(d.data.length > 0){
                var p = $.post('/admin/media/index',{template: '/admin/media/media1', terminal: true, data: d.data, order: d.order, itemId: d.itemId ? d.itemId:'', loadAll: d.loadAll, type: d.type},function(rs){
                    $('#imagesLibrary').empty().prepend(rs);
                });
                p.done(function(){
                    $('#images').val(d.data);
                    $.getScript(change());
                });
                $( "#imagesLibrary" ).sortable({
                    beforeStop: function( event, ui ) {
                        var a = [];
                        if($('#imagesLibrary li').length > 0){
                            $('#imagesLibrary li').each(function(){
                                if($(this).attr('data-id') != 'undefined'){
                                    a.push($(this).attr('data-id'));
                                }
                            });
                        }
                        $('#images').val('').val(a);
                    }
                });
            }else if(d.data.length == 0){
                $('#images').val(d.data);
            }
        }
        if($('#images').length){
            var values = $('#images').val().split(',');
            var itemId = $('#images').attr('data-item');
            var type = $('#images').attr('data-type');

            updateImage({data: values, order: 'ASC', itemId: itemId, loadAll: false, type: type});
        }
        $('#insertMulti').unbind().bind('click', function(){
            if($('#listImage li.active').length > 0){
                var a = [], li = $('.listImage li.active');
                li.each(function(){
                    a.push($(this).attr('data-id'));
                });
                updateImage({data: a, loadAll: true});
                $('#listImage li').removeClass('active');
            }
        });

        function change(){
            $('#imagesLibrary li').unbind().bind('click', function(){
                var a = [], t = $(this);
                t.remove();
                $('#imagesLibrary li').each(function(){
                    a.push($(this).attr('data-id'));
                });
                updateImage({data: a});
            });
        }

    }

    function loadImage(){
        var p = $.post('/admin/media/index',{template: '/admin/media/media', terminal: true, order: 'DESC', loadAll: true},function(rs){
            $('#dataImage ul').empty().append(rs);
        });
        p.done(function(){
            $.getScript(insertImage());
        });
    }
//    if($('#imagesLibrary').length){
        loadImage();
//    }

    $("#imagemulti").unbind().change(function(){
        var ins = document.getElementById('imagemulti').files.length;
        var form_data = new FormData();
        for(x = 0; x < ins; x++){
            var file_data = $("#imagemulti").prop("files")[x];
            form_data.append("imagemulti[]", file_data);
        }
        $.ajax({
            data: form_data,
            type: "POST",
            url: "/admin/media/upload",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                if(url.code){
                    alert(url.message);
                }
                $.getScript(loadImage());
            }
        });
    });

});