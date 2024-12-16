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
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span><?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <div class="alert alert-info fade in" style="margin-bottom:0" >
                <i class="fa-fw fa fa-info"></i>
                <strong>Information</strong> 
                ReCalculate CDR
            </div>

<pre>
<code class="javascript">
ReCalculate CDR 
</code>
</pre>
            <form action="{{route('recalculate.update_cdr', 'recalculate')}}" id="fRecalculate" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>

                    <div class="row form-group smart-form">
                        <label class="col col-3 control-label">Periode<sup>*</sup></label>
                        <section class="col col-3">
                            <label class="input"> 
                                <input class="form-control" id="from" type="text" placeholder="From">
                            </label>
                            <span id="error_name"></span>
                        </section>
                        <section class="col col-3">
                            <div class="input">
                                <input class="form-control" id="to" type="text" placeholder="Select a date">
                            </div>
                            <span id="error_name"></span>
                        </section>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Project Name</label>
                        <div class="col-md-8">
                            <select name="idxCoreProject[]" id="input_idxCoreProject" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                                @foreach ($projects as $keyp => $project)
                                    <option value="{{ $project->idxCore }}">{{ $project->projectAlias }}</option>
                                @endforeach
                            </select>
                            <span id="error_idxCustomer"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Prefix</label>
                        <div class="col-md-8">
                            <select name="idxCorePrefix[]" id="input_idxCorePrefix" class="select2 select2-offscreen" placeholder="-- Choose Prefix --" multiple="multiple" tabindex="-1" title="">
                                @foreach ($projectPrefixSrvs as $keyp => $projectPrefixSrv)
                                    <option value="{{ $projectPrefixSrv->idxCore }}">{{ $projectPrefixSrv->prefixNumber }}</option>
                                @endforeach
                            </select>
                            <span id="error_idxCustomer"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3" for="prepend"> &nbsp;<sup>*</sup></label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="isCompare" class="checkbox style-0" >
                                                    <span>&nbsp;&nbsp;ALL Project & Prefix</span>
                                                </label>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                        <div class="form-group">
                            <label class="col-md-3 control-label">Project Name</label>
                            <div class="col-md-5">
                                <select name="idxCore" id="input_idxCore" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                    <option value="" selected="selected">-- Choose Project Name --</option>
                                    @foreach ($projects as $keyp => $project)
                                        <option value="{{ $project->idxCore }}">{{ $project->projectName }}</option>
                                    @endforeach
                                </select>
                                <span id="error_idxCustomer"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="prepend"> &nbsp;<sup>*</sup></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="isCompare" class="checkbox style-0" >
                                                        <span>&nbsp;&nbsp;ALL Project</span>
                                                    </label>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Prefix <br />* Choose Project First</label>
                            <div class="col-md-5" id="reportGetPrefix"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3" for="prepend"> &nbsp;<sup>*</sup></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="isCompare" class="checkbox style-0" >
                                                        <span>&nbsp;&nbsp;ALL Prefix</span>
                                                    </label>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    -->
                    <input type="hidden" name="stopRecalculate" id="stopRecalculate" value="No" >
                    
                </fieldset>
            </form>
<pre id="reportParser" style="height: 150px;visibility:hidden" ></pre>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            
            <a href="javascript:void(0);" id="mybuttonStopRecalculate" class="btn btn-labeled bg-color-magenta" disabled="disabled" ><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Stop Recalculate</a>
            
            <a href="javascript:void(0);" id="mybuttonRunRecalculate" class="btn btn-labeled btn-success" onclick="my_form.submit('#fRecalculate')"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Recalculate</a>
            
        </div>
        
        <script type="text/javascript">
            

            var pagefunction = function() {

                // Date Range Picker
                $("#from").datepicker({
                    defaultDate: "now",
                    changeMonth: true,
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    onClose: function (selectedDate) {
                        $("#to").datepicker("option", "minDate", selectedDate);
                    }
                });

                $("#to").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    onClose: function (selectedDate) {
                        $("#from").datepicker("option", "maxDate", selectedDate);
                    }
                });

            }

            // looping dibuat agar tidak melakukan confirmasi
            function callLooping() {
                console.log('melakukan loop');
                var link = "{{route('recalculate.update_cdr', 'cecalculate')}}"; 
                var dataType = 'json'; 
                var dataSend = {};
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
                // first start
                // untuk manage information
                // var formattedDate = new Date().toISOString().slice(0, 19).replace('T', ' ');
                var currentDate = new Date();
                currentDate.setHours(currentDate.getHours() + 7);
                var formattedDate = currentDate.toISOString().slice(0, 19).replace('T', ' ');

                var checkFirstData = $('#reportParser').html();
                if(checkFirstData == ''){
                    $("#reportParser").css('visibility', 'visible');
                    $('#reportParser').append("Start Recalculate : "+formattedDate);
                }

                // untuk manage tombol
                $("#mybuttonRunRecalculate").attr('disabled', 'disabled');
                $('#mybuttonStopRecalculate').removeAttr('disabled');

                // in prosess
                // untuk manage looping
                stopRecalculate = $("#stopRecalculate").val();
                // alert(stopRecalculate);
                if (stopRecalculate == "No") { // kondisi masih mau menjalankan looping

                    if (call_back == "Yes") {
                        // lalkukan loop ke server
                        // my_form.submit('#fRecalculate');
                        console.log('melakukan loop mybuttonRunRecalculate');
                        callLooping();
                        // document.getElementById('mybuttonRunRecalculate').click();
                        $('#reportParser').append('<br/>Proses berlanjut...');
                    }else{
                        // in prosess
                        // stop looping and Proses selesai
                        $('#reportParser').append('<br/>Data Selesai di Recalculate.');
                        $('#mybuttonRunRecalculate').removeAttr('disabled');
                        $("#mybuttonStopRecalculate").attr('disabled', 'disabled');
                        $('#reportParser').append("<br/>End Recalculate : "+formattedDate);
                        $("#stopRecalculate").val("No");
                    }
                }else{
                    // in prosess
                    // stop looping and Proses selesai
                    $('#reportParser').append('<br/>Anda melakukan stop Recalculate.');
                    $('#mybuttonRunRecalculate').removeAttr('disabled');
                    $("#mybuttonStopRecalculate").attr('disabled', 'disabled');
                    $('#reportParser').append("<br/>End Recalculate : "+formattedDate);
                    $("#stopRecalculate").val("No");
                }
                
            }

            $( document ).ready(function() {
                
                $('#mybuttonStopRecalculate').on('click', function() {
                    // Menambahkan atribut 'disabled' ke elemen <a>
                    console.log('stopRecalculate')
                    $("#stopRecalculate").val("Yes");
                });

                // setup page
                pageSetUp();
                pagefunction();
                my_form.init();
                $.sound_path = "{{url('/sound')}}/";
            });
        </script>
        
    </body>
</html>