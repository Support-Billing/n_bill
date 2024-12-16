<html>
    <head>
        <script id="tinyhippos-injected">
            if (window.top.ripple) {
                window.top.ripple("bootstrap").inject(window, document);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    </head>
    <body>
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-times"></i>
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span> <?php echo $page_title; ?>
            </h6>
        </div>

        <div class="modal-body">

            <form action="{{route('reportcdr.run_generator_folder', $urlData)}}" id="fGenerator" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            
                @csrf
                <fieldset style="display: none" >
                    
                    <div class="form-group" >
                        <select name="idxCoreProject[]" id="input_idxCoreProjectG" class="select2 select2-offscreen " placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                            @foreach ($projects as $keyp => $project)
                                <option value="{{ $project->idxCoreProject }}" selected> {{ $project->idxCoreProject }} </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- /// stopGenerator untuk menstop generator -->
                    <input type="text" name="stopGenerator" id="stopGenerator" value="No" >
                    <!-- /// mulai menjalankan generator -->
                    <input type="text" name="startLoop" id="startLoop" value="0" >
                    <!-- /// informasi selesai generator -->
                    <input type="text" name="isAllProjectFinish" id="isAllProjectFinish" value="No" >
                    
                </fieldset>
            </form>
        
<pre id="reportParser" style="height: 150px;" >Ready to Generate Data</pre>

        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybuttonStopGenerator" class="btn btn-labeled bg-color-magenta" disabled="disabled" ><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Stop Generator</a>
            <a href="javascript:void(0);" id="mybuttonRunGenerator" class="btn btn-labeled btn-success" onclick="my_form.submit('#fGenerator')"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Generator</a>
        </div>
        <script type="text/javascript">
            
            // looping dibuat agar tidak melakukan confirmasi
            function callLooping() {
                console.log('melakukan loop');
                // var link = "{{route('reportcdr.run_generator_folder', 'all')}}"; 
                var link = $('#fGenerator').attr('action');
                var dataType = 'json'; 
                var dataSend = {};
                var dataSend = $('#fGenerator').serialize();
                var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                dataSend['_token'] = csrfToken;
                dataSend['_method'] = 'POST';
                $.ajax({
                    "url": link,
                    "type": 'POST',
                    "dataType": dataType,
                    "data": dataSend,
                    beforeSend: function () {
                        // beforeSend
                    },
                    success: function (response) {
                        var content_id = response[3];
                        if (typeof content_id !== 'undefined' && content_id !== '') {
                            var patt = /^#/;
                            if (!patt.test(content_id)) {
                                eval('(' + content_id + ')');
                            }
                        }
                    }, complete: function () {
                        // complete
                    }
                });
                console.log('melakukan loop');
            }

            function nextload(call_back, startLoop) {
                
                $("#startLoop").val(startLoop);
                // var childrenGet = startLoop - 1;
                // var keyStartLoop = $('#input_idxCoreProjectG').children('option').eq(childrenGet).val();
                // $("#keyStartLoop").val(keyStartLoop);

                // first start
                // untuk manage information
                // var formattedDate = new Date().toISOString().slice(0, 19).replace('T', ' ');
                var currentDate = new Date();
                currentDate.setHours(currentDate.getHours() + 7);
                var formattedDate = currentDate.toISOString().slice(0, 19).replace('T', ' ');

                var checkFirstData = $('#reportParser').html();
                if(checkFirstData == ''){
                    $("#reportParser").css('visibility', 'visible');
                    $('#reportParser').append("Start Generator : "+formattedDate);
                }
                
                // untuk manage tombol
                $("#mybuttonRunGenerator").attr('disabled', 'disabled');
                $('#mybuttonStopGenerator').removeAttr('disabled');

                // in prosess
                // untuk manage looping
                stopGenerator = $("#stopGenerator").val();
                // alert(stopGenerator);
                if (stopGenerator == "No") {

                    if (call_back == "Yes") {
                        console.log('melakukan loop mybuttonRunGenerator');
                        callLooping();
                        // document.getElementById('mybuttonRunGenerator').click();
                        $('#reportParser').append('<br/>Proses berlanjut...');
                    }else{
                        // in prosess
                        // stop looping and Proses selesai
                        $('#reportParser').append('<br/>Data Selesai di Generator.');
                        $('#mybuttonRunGenerator').removeAttr('disabled');
                        $("#mybuttonStopGenerator").attr('disabled', 'disabled');
                        $('#reportParser').append("<br/>End Generator : "+formattedDate);
                        $("#stopGenerator").val("No");
                    }
                }else{
                    // in prosess
                    // stop looping and Proses selesai
                    $('#reportParser').append('<br/>Anda melakukan stop Generator.');
                    $('#mybuttonRunGenerator').removeAttr('disabled');
                    $("#mybuttonStopGenerator").attr('disabled', 'disabled');
                    $('#reportParser').append("<br/>End Generator : "+formattedDate);
                    $("#stopGenerator").val("No");
                }
            }

            $( document ).ready(function() {

                // var keyStartLoop = $('#input_idxCoreProjectG').children('option').eq(0).val();
                // $("#keyStartLoop").val(keyStartLoop);
                
                $('#mybuttonStopGenerator').on('click', function() {
                    // Menambahkan atribut 'disabled' ke elemen <a>
                    console.log('stopGenerator');
                    $("#stopGenerator").val("Yes");
                });

                pageSetUp();
                my_form.init();
            });
        </script>
    </body>
</html>