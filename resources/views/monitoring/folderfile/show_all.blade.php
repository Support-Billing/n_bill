<div class="row">

    <section class="col col-sm-6">  
        <table class="table table-bordered table-striped hidden-mobile" id="BusinessInfo" >
            <thead>
                <tr>
                    <th colspan="2"> Info Parser Server </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-5" ><strong>Type Server</strong></td>
                    <td>: {{ucwords($data_detil->TypeServer)}}</td>
                </tr>
                <tr>
                    <td><strong>Xample File</strong></td>
                    <td id="XampleFile_show" >: {{$data_detil->XampleFile}}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Column </strong></td>
                    <td id="JumlahColumn_show" >: {{$data_detil->jumlahcolumn}}</td>
                </tr>
                <tr>
                    <td><strong>Teknik Parser</strong></td>
                    <td>: 
                        <a 
                            href="javascript:void(0);" 
                            id="teknik_parser" 
                            data-type="select2" 
                            data-pk="{{$data_detil->teknik_parser}}" 
                            data-select-search="true" 
                            data-value="{{$data_detil->teknik_parser}}" 
                            data-original-title="Pilih Kolom">
                        </a>


                    </td>
                </tr>
                <tr>
                    <td><strong>Status Parser</strong></td>
                    <td>: {{$data_detil->status_parser}}</td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <a 
                            href='folderfile/{{$data_detil->idx}}/import' 
                            id='mybutton-export-{{$data_detil->idx}}'
                            class='btn btn-primary btn-xs margin-right-5' 
                            data-toggle='modal' 
                            data-target='#remoteModal'>
                            <i class='fa fa-upload'></i> Import Xample File CSV
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="col col-sm-6">
        
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >idx CDR Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>idxCustomer</td>
                    <td>xxxx</td>
                </tr>
                <tr>
                    <td>idxSupplier</td>
                    <td>xxxx</td>
                </tr>
                <tr>
                    <td>idxCustomerIP</td>
                    <td>xxxx</td>
                </tr>
                <tr>
                    <td>idxCustomerIPPrefix</td>
                    <td>xxxx</td>
                </tr>
                <tr>
                    <td>idxSupplierIP</td>
                    <td>xxxx</td>
                </tr>
                <tr>
                    <td>idxSupplierIPPrefix</td>
                    <td>xxxx</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="col col-sm-12" id="all_table" >
        
    </section>

</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-12 margin-right-5">
            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
        </div>
    </div>
</div>

<!-- Dynamic Modal -->  
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>  
<!-- /.modal -->

<script type="text/javascript">

    var pagefunction = function(data_run,paramsPK,paramsValue) {
        console.log(data_run);
        // if (data_run !== undefined && data_run !== null && data_run !== '') {
        //     console.log('qqqqq');
        // } else {
        //     console.log(data_run);
        // }
		/*
		 * X-Ediable
		 */
	

        function runXEdit() {
            /*
            * X-EDITABLES
            */
		    var teknik_parser = [];
		    $.each({
		        "row": "row",
		        "column": "column"
		    }, function (k, v) {
		        teknik_parser.push({
		            id: k,
		            text: v
		        });
		    });

            $('#teknik_parser').editable({
                url: "{{route('folderfile.update_parser', [$data_detil->idx])}}",
                params: function(params) {
                    var data = {};
                    var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    data['key'] = params.pk;
                    data['value'] = params.value;
                    data['_token'] = csrfToken;
                    data['_method'] = 'PUT';
                    return data;
                },
		        type: 'json',
		        method: 'post',
                source: teknik_parser,
                success: function(response, newValue) {
                    // var jsonObjectResponse = JSON.parse(response);
                    console.info('selesai');
                    pagefunction('reset_data');
                },
                select2: {
                    width: 200
                }
            });

            var JumlahKolom = 0;
            @if(!empty($data_detil->jumlahcolumn))
                JumlahKolom = {{$data_detil->jumlahcolumn}};
            @else
                JumlahKolom = $('#JumlahColumn_get').val();
            @endif
            

            var datakolom = [];
            
            for (var i = 1; i <= JumlahKolom; i++) {
                datakolom.push({
                    id: 'k' +i,
                    text: 'Kolom ke ' + i
                });
            }

            $('.setting_colom').editable({
                url: "{{route('folderfile.update', [$data_detil->idx])}}",
                params: function(params) {
                    var data = {};
                    var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    data['key'] = params.pk;
                    data['value'] = params.value;
                    data['_token'] = csrfToken;
                    data['_method'] = 'PUT';
                    return data;
                },
		        type: 'json',
		        method: 'post',
                source: datakolom,
                success: function(response, newValue) {
                    var jsonObjectResponse = JSON.parse(response);
                    var idchange = '#'+jsonObjectResponse['update_data_id'];
                    $(idchange).html(jsonObjectResponse['show_data']);
                    console.info('selesai');
                },
                select2: {
                    width: 200
                }
            });

		    $('.regex999').editable({
                url: "{{route('folderfile.update_regex', [$data_detil->idx])}}",
                params: function(params) {
                    console.info('semua params',params);
                    var data = {};
                    var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    data['key'] = params.pk;
                    data['value'] = params.value;
                    data['_token'] = csrfToken;
                    // data['_method'] = 'PUT';
                    return data;
                },
		        type: 'json',
                success: function(response, newValue) {
                    var jsonObjectResponse = JSON.parse(response);
                    var idchange = '#'+jsonObjectResponse['update_data_id'];
                    $(idchange).html(jsonObjectResponse['show_data']);
                    console.info('selesai');
                },
		    });
        }

        function reset_data() {
            var teknik_parser = $('#teknik_parser').text().trim();

            if (teknik_parser === '') {
                teknik_parser = "{{$data_detil->teknik_parser}}";
            }

            var link = (teknik_parser === 'row') ? '{{ route('folderfile.show_all_table_row') }}' : '{{ route('folderfile.show_all_table_column') }}';

            
            console.log(teknik_parser);
            var link;


            var dataType = 'html'; 
            $.ajax({
                "url": link,
                "type": 'GET',
                "dataType": dataType,
                "data": {idx:'{{$data_detil->idx}}'},
                beforeSend: function () {
                    // beforeSend
                },
                success: function (data) {
                    
                    $('#all_table').html(data);
                    var XampleFile_get = $('#XampleFile_get').val();
                    var JumlahColumn_get = $('#JumlahColumn_get').val();

                    $('#JumlahColumn_show').text(JumlahColumn_get);
                    $('#XampleFile_show').text(XampleFile_get);

                    runXEdit();
                }, complete: function () {
                    // complete
                }
            });
        }
        
        function delete_value_kolom(params_pk,params_value) {
            // console.info(dataGet);
            var link = "{{route('folderfile.update', [$data_detil->idx])}}"; 
            var dataType = 'json'; 
            var dataSend = {};
            var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
            dataSend['key'] = params_pk;
            dataSend['value'] = params_value;
            dataSend['_token'] = csrfToken;
            dataSend['_method'] = 'PUT';
            $.ajax({
                "url": link,
                "type": 'POST',
                "dataType": dataType,
                "data": dataSend,
                beforeSend: function () {
                    // beforeSend
                },
                success: function (response) {
                    reset_data();
                }, complete: function () {
                    // complete
                }
            });
        }

        if (data_run == 'reset_data'){
            reset_data();
        }

        if (data_run == 'delete_value_kolom'){
            delete_value_kolom(paramsPK,paramsValue);
        }
	};

    $( document ).ready(function() {
        pageSetUp();
        pagefunction('reset_data');
    });
</script>