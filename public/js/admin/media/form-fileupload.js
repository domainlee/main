$(function () {
    $('.delete-file').click(function(){
        $.get(
            '/company/announcement/deleteannouncementfile',
            {
                'id': $(this).attr('data-id')
            },
            function(rs){
                if(rs.code){
                    window.location.reload();
                }
            }
        );
    });

    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone(document.body, { // Make the whole body a
        // dropzone
        url : "/food/admin/media/upload",
        thumbnailWidth : 50,
        thumbnailHeight : 50,
        parallelUploads : 20,
        previewTemplate : previewTemplate,
        autoQueue : false, // Make sure the files aren't queued until manually
        // added
        previewsContainer : "#previews", // Define the container to display
        // the previews
        clickable : ".fileinput-button", // Define the element that should be
        // used as click trigger to select
        // files.
        paramName: 'fileName'
    });

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() {
            myDropzone.enqueueFile(file);
        };
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress",function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress	+ "%";
    });

    myDropzone.on("sending", function(file) {
        document.querySelector("#total-progress").style.opacity = "1";
        file.previewElement.querySelector(".start").setAttribute("disabled",
            "disabled");
    });

    myDropzone.on('success', function(file, response){
        alert('Thành công');
//        if(response.code){
//            file.previewElement.setAttribute('idref', response.data.id);
//            file.previewElement.querySelector(".download").setAttribute("href", '/home/media/download?type=' + type + '&f=' + response.data.id);
//        } else {
//            $('#errorDialog').html(response);
//            $('#errorDialog').dialog({
//                buttons: [{text: lbClose, click: function(){
//                    $('#errorDialog').dialog('close');
//                }}]
//            });
//        }

    });

    myDropzone.on('removedfile', function(file){
        var idref = file.previewElement.getAttribute('idref');
        if(idref){
            $.post(
                '/company/announcement/deletecontractfile',
                {
                    'id': idref
                },
                function(rs){
                    if(!rs.code){
                        $('#errorDialog').html(response.messages);
                        $('#errorDialog').dialog({
                            buttons: [{text: lbClose, click: function(){
                                $('#errorDialog').dialog('close');
                            }}]
                        });
                    }
                }
            );
        }
    });




    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0";
    });

    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true);
    };

});