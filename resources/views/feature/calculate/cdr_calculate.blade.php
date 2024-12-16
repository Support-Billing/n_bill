<html>
    <head>
        <script id="tinyhippos-injected">
            if (window.top.ripple) {
                window.top.ripple("bootstrap").inject(window, document);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <style>
            .smart-style-5 .progress {
                background: rgba(0, 0, 0, 0.13);
                box-shadow: 0 1px 0 transparent, 0 0 0 1px rgba(255, 255, 255, 0.15) inset;
            }
            .progress-sm {
                height: 1.07692rem;
                line-height: 1.07692rem;
            }
            .progress {
                margin-bottom: 1.53846rem;
            }
            .progress {
                display: flex;
                overflow: hidden;
                font-size: 0.75rem;
            }
            .smart-style-5 .jarviswidget-color-darken > header, .smart-style-5 .bg-darken {
                background-color: rgba(0, 0, 0, 0.23) !important;
                border-color: rgba(0, 0, 0, 0.23) !important;
            }
            .progress-bar-striped.progress-bar {
                background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
                background-size: 3.46154rem 3.46154rem;
            }
            .progress-bar-animated {
                animation: progress-bar-stripes 2s linear infinite;

            }
            .progress-bar {
                font-weight: 700;
                font-size: 0.84615rem;
            }
        </style>
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
                Tahapan proses pada Calculate CDR.
            </div>
            
<pre>
<code class="javascript">
Calculate CDR adalah tahapan proses setelah melakukan proses parser (memindahkan data csv kedalam database).
Adapun proses Calculate CDR yaitu :
<!-- 1. Pengumpulan data row dari csv kedalam database, dengan management 1 row data csv samadengan 1 row pada database  -->
<!-- 1. Melakukan Penghitungan price call yang sudah dilakukan Customer -->
<!-- 2. Proses Parser data yaitu pengambilan data perKolom pada csv untuk di ambil key data sebagai data key operasional -->
<!-- 3. Proses Akumulasi data pengumpulan dan penjumlahan data dari beberapa sumber table database sesuai  -->
<!-- 4. atau periode waktu yang tertentu untuk tujuan mendapatkan harga yang harus dibayar customer  -->
<!-- sebisa munkin diinformasikan tujuan dari calculate dan menginfomasikan field/header dari proses calculate itu sendiri  -->
</code>
</pre>

<pre id="reportParser" style="height: 150px;visibility:hidden" ></pre>

        <form action="{{route('calculate.update_cdr', 'calculate')}}" id="fCalculate" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="stopCalculate" id="stopCalculate" value="No" >
        </form>
            
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            
            <a href="javascript:void(0);" id="mybuttonStopCalculate" class="btn btn-labeled bg-color-magenta " disabled="disabled" ><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Stop Calculate</a>
            
            <a href="javascript:void(0);" id="mybuttonRunCalculate" class="btn btn-labeled btn-success" onclick="my_form.submit('#fCalculate')"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Calculate</a>
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
                var link = "{{route('calculate.update_cdr', 'calculate')}}"; 
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
                        // console.log('response response response response response ');
                        // console.log(response);
                        // var fist_index = response[0];
                        // var alert_title = response[1];
                        // var alert_content = response[2];
                        // var content_id = response[3];
                        // var style_message = response[4];
                        // console.log('fist_index');
                        // console.log(fist_index);
                        // console.log('alert_title');
                        // console.log(alert_title);
                        // console.log('alert_content');
                        // console.log(alert_content);
                        // console.log('content_id');
                        // console.log(content_id);
                        // console.log('style_message');
                        // console.log(style_message);
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
                console.log('melakukan loop aaaa');
            }

            function nextload(call_back) {
                // first start
                // untuk manage information
                var formattedDate = new Date().toISOString().slice(0, 19).replace('T', ' ');
                var checkFirstData = $('#reportParser').html();
                if(checkFirstData == ''){
                    $("#reportParser").css('visibility', 'visible');
                    $('#reportParser').append("Start Calculate : "+formattedDate);
                }
                // untuk manage tombol
                $("#mybuttonRunCalculate").attr('disabled', 'disabled');
                $('#mybuttonStopCalculate').removeAttr('disabled');

                // in prosess
                // untuk manage looping
                stopCalculate = $("#stopCalculate").val();
                if (stopCalculate == "No") { // kondisi masih mau menjalankan looping

                    if (call_back == "Yes") {
                        // lalkukan loop ke server
                        // my_form.submit('#fCalculate');
                        console.log('melakukan loop mybuttonRunCalculate');
                        callLooping();
                        // document.getElementById('mybuttonRunCalculate').click();
                        $('#reportParser').append('<br/>Proses berlanjut...');
                    }else{
                        // in prosess
                        // stop looping and Proses selesai
                        $('#reportParser').append('<br/>Data Selesai di Calculate.');
                        $('#mybuttonRunCalculate').removeAttr('disabled');
                        $("#mybuttonStopCalculate").attr('disabled', 'disabled');
                        $('#reportParser').append("<br/>End Calculate : "+formattedDate);
                        $("#stopCalculate").val("No");
                    }
                }else{
                    // in prosess
                    // stop looping and Proses selesai
                    $('#reportParser').append('<br/>Anda melakukan stop Calculate.');
                    $('#mybuttonRunCalculate').removeAttr('disabled');
                    $("#mybuttonStopCalculate").attr('disabled', 'disabled');
                    $('#reportParser').append("<br/>End Calculate : "+formattedDate);
                    $("#stopCalculate").val("No");
                }
                
            }

            $( document ).ready(function() {
                
                $('#mybuttonStopCalculate').on('click', function() {
                    // Menambahkan atribut 'disabled' ke elemen <a>
                    console.log('stopCalculate')
                    $("#stopCalculate").val("Yes");
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