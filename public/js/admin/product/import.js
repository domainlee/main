$(function(){


    $("#imagemulti1").change(function(){
        var ins = document.getElementById('imagemulti1').files.length;
        var form_data = new FormData();
        for(x = 0; x < ins; x++){
            var file_data = $("#imagemulti1").prop("files")[x];
            form_data.append("imagemulti[]", file_data);
        }
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        var p = $('.process').text(' - '+percentComplete+'%')
                        if (percentComplete === 100) {
                            p.remove();
                        }
                    }
                }, false);
                return xhr;
            },
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
            }
        });
    });


    var X = XLSX;
    var xlf = document.getElementById('fileName');
    if($('#fileName').length){
    function handleFile(e) {
        var files = e.target.files;
        var f = files[0];
        var reader = new FileReader();
        var name = f.name;
        reader.onload = function(e) {
            if (typeof console !== 'undefined')
                console.log("onload", new Date());
            var data = e.target.result;
            var arr = fixdata(data);
            var wb = X.read(btoa(arr), {
                type : 'base64'
            });
            var jsonStr = process_wb(wb);
            $('#data').val(jsonStr);
        };
        reader.readAsArrayBuffer(f);
    }
    if (xlf.addEventListener)
        xlf.addEventListener('change', handleFile, false);
    function fixdata(data) {
        var o = "", l = 0, w = 10240;
        for (; l < data.byteLength / w; ++l)
            o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w, l
                * w + w)));
        o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w)));
        return o;
    }
    function process_wb(wb) {
        var output = "";
        output = JSON.stringify(to_json(wb), 2, 2);
        return output;
    }
    function to_json(workbook) {
        var result = {};
        workbook.SheetNames
            .forEach(function(sheetName) {
                var roa = X.utils
                    .sheet_to_row_object_array(workbook.Sheets[sheetName]);
                if (roa.length > 0) {
                    result[sheetName] = roa;
                }
            });
        return result;
    }

    $('#submitImport').click(function(){
        $.post('/admin/product/importexcel',{data: $('#data').val()},function(r){
            console.log(r);
            if(r.code == 1){
                alert(r.messenger);
            }
        });
    });

    }

});