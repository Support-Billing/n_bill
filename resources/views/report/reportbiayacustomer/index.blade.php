
    
<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-white" 
                id="wid-id-0" 
                data-widget-sortable="false" 
                data-widget-deletebutton="false" 
                data-widget-editbutton="false" 
                data-widget-togglebutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2><?php echo $page_title; ?> </h2>
                    <div class="widget-toolbar hidden-phone">
                        <div class="smart-form">
                            <label class="toggle"><input type="checkbox" name="checkbox-toggle" value="#box_filter" checked="checked" id="demo-switch-to-pills" onclick="my_data_table.filter.toggle(this.id)">
                                <i data-swchon-text="Show" data-swchoff-text="Hide"></i>Filtering
                            </label>
                        </div>
                    </div>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        
                        <div id="box_filter" class="no-padding border-bottom-1">
                            <form action="" id="filter_table" class="smart-form">
                                @csrf
                                <fieldset>
                                    
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Date Period :</label>
                                        </section>
                                        <section class="col col-2">
                                            <label class="input">
                                                <?php
                                                    $backDate = '';
                                                    $nowkDate = '';
                                                    if (!empty($_GET['months'])){
                                                        $backDate = date('d/m/Y', strtotime("-2 months"));
                                                        $nowkDate = date('d/m/Y');
                                                    }
                                                ?>
                                                <input class="form-control" id="ftDateStart" type="text" placeholder="From" name="ftDateStart" value="{{$backDate}}">
                                            </label>
                                        </section>
                                        <section class="col col-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">s/d</span>
                                                <input class="form-control" id="ftDateEnd" type="text" placeholder="Select a date" name="ftDateEnd" value="{{$nowkDate}}">
                                            </div>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Folder Name :</label>
                                        </section>
                                        <section class="col col-10">
                                            <label class="input">
                                                <select name="folderName[]" id="input_folderName" class="select2 select2-offscreen " placeholder="-- Choose Folder Name --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($foldernames as $keyf => $foldername)
                                                        <option value="{{ $foldername->FolderName }}" selected>{{ $foldername->FolderName }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Customer :</label>
                                        </section>
                                        <section class="col col-8">
                                            <label class="input">
                                                <select name="idxCoreCustomer[]" id="input_idxCoreCustomer" class="select2 select2-offscreen" placeholder="-- Choose Customer --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($customers as $keyp => $customer)
                                                        <option value="{{ $customer->idxCore }}">{{ $customer->title }}. {{ $customer->clientName }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right" style="margin-right:15px" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                        </section>
                                    </div>

                                    <!-- <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Type Server :</label>
                                        </section>
                                        <section class="col col-8">
                                            <label class="input">
                                                <select name="typeServer" id="input_typeServer" class="select2 select2-offscreen " placeholder="-- Choose Type Server --" tabindex="-1" title="">
                                                    <option value="" selected="selected"></option>
                                                    <option value="allMERA">ALL MERA</option>
                                                    <option value="allVOS">ALL VOS</option>
                                                    <option value="allDIRECT">ALL DIRECT (ELASTIX - ASTERISK)</option>
                                                </select>
                                            </label>
                                        </section>
                                    </div> -->
                                </fieldset>
                                
                            </form>
                        </div>

                        @if($import_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="{{route('download_reportbiayacustomer', 'active')}}" id="download_data" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-cloud-download"></i></span>
                                    Download <?php echo $page_title; ?>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                               data-source="{{url('reportbiayacustomerload')}}"
                                data-paginate="500"
                               data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Customer</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-tachometer text-muted hidden-md hidden-sm hidden-xs"></i> Durasi Real</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-tachometer text-muted hidden-md hidden-sm hidden-xs"></i> Durasi Tagih</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-money text-muted hidden-md hidden-sm hidden-xs"></i> Biaya</th>
                                </tr>
                            </thead>
                        </table>
                        
                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- WIDGET END -->

    </div>
    <!-- end row -->

</section>
<!-- end widget grid -->



<script type="text/javascript">
    
    function getEncriptedUrl(){
        var encriptUrl = "{{route('encriptUrl', 'active')}}";
        var dataSearch = $('#filter_table').serialize();
        $.ajax({
            url: encriptUrl, 
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:dataSearch,
            cache: false,
            beforeSend: function() {
                $('body').css('cursor','wait');
                // $("#download_data").prop("disabled", true);
                $("#download_data").attr("disabled", "disabled");
            },
            complete: function() {	
                $('body').css('cursor','default');
                $("#download_data").removeAttr("disabled");

            },
            success: function(response) {

                // Mendapatkan URL yang ada saat ini
                download_data = $("#download_data").attr("href");
                
                // Memecah URL menjadi potongan-potongan berdasarkan tanda '/'
                urlParts =  download_data.split('/');
                
                // Mendapatkan bagian terakhir dari URL, yang berisi "active"
                var lastPart = urlParts[urlParts.length - 2];
                
                // Memperbarui bagian terakhir dari URL dengan yang baru
                urlParts[urlParts.length - 2] = response;

                // Menggabungkan potongan-potongan URL kembali menjadi URL lengkap
                var newDownloadData = urlParts.join('/');

                // Debugging: menampilkan URL yang telah diubah
                $("#download_data").attr("href", newDownloadData);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error dari server
            }
        });
    }
    
	var pagefunction = function() {
		 // Date Range Picker
		// $("#ftDateStart").datepicker({
		// 	defaultDate: "now",
		// 	changeMonth: true,
		// 	numberOfMonths: 2,
		// 	prevText: '<i class="fa fa-chevron-left"></i>',
		// 	nextText: '<i class="fa fa-chevron-right"></i>',
		// 	onClose: function (selectedDate) {
		// 		$("#ftDateEnd").datepicker("option", "maxDate", selectedDate);
		// 	}
		// });
		// $("#ftDateEnd").datepicker({
		// 	defaultDate: "+1w",
		// 	changeMonth: true,
		// 	numberOfMonths: 2,
		// 	prevText: '<i class="fa fa-chevron-left"></i>',
		// 	nextText: '<i class="fa fa-chevron-right"></i>',
		// 	onClose: function (selectedDate) {
		// 		$("#ftDateStart").datepicker("option", "minDate", selectedDate);
		// 	}
		// });
		 // Date Range Picker
		$("#ftDateStart").datepicker({
            dateFormat: "dd/mm/yy", 
			defaultDate: "now",
			changeMonth: true,
            changeYear: true,
            yearRange: 'c-10:c+1',
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateEnd").datepicker("option", "minDate", selectedDate);
			}
		});
		$("#ftDateEnd").datepicker({
            dateFormat: "dd/mm/yy", 
			defaultDate: "+1w",
			changeMonth: true,
            changeYear: true,
            yearRange: 'c-10:c+1',
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateStart").datepicker("option", "maxDate", selectedDate);
			}
		});
	};
    $( document ).ready(function() {
        pageSetUp();
        pagefunction();
        my_data_table.init('#dt_basic');
        my_form.init();


        
    });
</script>
