var FormFileUpload = function() {
    var csrfVal = $("#csrf").val();
    var csrfName = $("#csrf").attr("name");

    return {
        //main function to initiate the module
        init: function(idAcq) {
            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                disableImageResize: false,
                autoUpload: false,
                sequentialUploads: true,
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},                
                url: 'http://localhost:8080/LOS/BiCheckingRequest/B0912140000000000001/handlingFile/R091214012?=',//http://localhost:8080/LOS/BiCheckingRequest/'+idAcq+'?' + csrfName + "=" + csrfVal,
                destroy: function(e, data) {
                    console.log(data);
                    if (typeof e.originalEvent.originalEvent === "undefined") {
                        var s = $('.toggle:checkbox:checked');
                        var arrayLength = s.length;

                        for (var i = 0; i < arrayLength; i++) {
                            console.log(s.prev()[i]);
                            //Do something
                        }
                    } else {
                        var that = $(this).data('fileupload');
                        bootbox.confirm("Apakah anda yakin ingin menghapus?", function(result) {
                            if (result) {
                                if (data.url) {
                                    $.ajax(data)
                                            .success(function() {
                                                that._adjustMaxNumberOfFiles(1);
                                                $(this).fadeOut(function() {
                                                    $(this).remove();
                                                });
                                            });
                                } else {
                                    data.context.fadeOut(function() {
                                        $(this).remove();
                                    });
                                }
                            }
                        });
                    }


                }
            });

            // Enable iframe cross-domain access via redirect option:
            $('#fileupload').fileupload(
                    'option',
                    'redirect',
                    window.location.href.replace(
                            /\/[^\/]*$/,
                            '/cors/result.html?%s'
                            )
                    );

            // Demo settings:
            $('#fileupload').fileupload('option', {
                url: $('#fileupload').fileupload('option', 'url'),
                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                maxFileSize: 100000,
                sequentialUploads: true,
                acceptFileTypes: /(\.|\/)(idi|txt)$/i
            });

            // Upload server status check for browsers with CORS support:
            if ($.support.cors) {
                var csrf = new Array();

                //csrf[csrfName] = csrfVal;
                //console.log(csrf);
                $.ajax({
                    url: 'http://localhost:8080/LOS/BiCheckingRequest/B0912140000000000001/handlingFile/R091214012?=',//'http://localhost:8080/LOS/BiCheckingRequest/'+idAcq+'?' + csrfName + "=" + csrfVal,
                    type: 'HEAD'
                }).fail(function() {
                    $('<div class="alert alert-danger"/>')
                            .text('Upload server currently unavailable - ' +
                                    new Date())
                            .appendTo('#fileupload');
                });
            }

            // Load & display existing files:
            $('#fileupload').addClass('fileupload-processing');
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').fileupload('option', 'url'),
                dataType: 'json',
                context: $('#fileupload')[0]
            }).always(function() {
                $(this).removeClass('fileupload-processing');
            }).done(function(result) {

                $(this).fileupload('option', 'done')
                        .call(this, $.Event('done'), {result: result});
                console.log("result");
            });
        }

    };

}();