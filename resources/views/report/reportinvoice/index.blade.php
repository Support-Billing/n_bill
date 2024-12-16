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
                    
                    <!-- widget content -->
                    <div class="widget-body no-padding">
<!--                         
                        <div class="alert alert-warning fade in" style="margin-bottom:0" >
                            <i class="fa-fw fa fa-warning"></i>
                            <strong>Warning</strong> 
                            Management Report Invoice.
                        </div>
                    
<pre>
<code class="javascript">
Report Invoice adalah
</code>
</pre>
-->

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
                                                <input class="form-control" id="ftDateStart" type="text" placeholder="From" name="ftDateStart" value="">
                                            </label>
                                        </section>
                                        <section class="col col-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">s/d</span>
                                                <input class="form-control" id="ftDateEnd" type="text" placeholder="Select a date" name="ftDateEnd" value="">
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
                                            <label class="label">Project Name :</label>
                                        </section>
                                        <section class="col col-8">
                                            <label class="input">
                                                <select name="idxCoreProject[]" id="input_idxCoreProject" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($projects as $keyp => $project)
                                                        <option value="{{ $project->idxCore }}">{{ $project->projectAlias }}</option>
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
                                                <select name="typeServer" id="input_typeServer" class="select2 select2-offscreen" placeholder="-- Choose Type Server --" tabindex="-1" title="">
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
                                <!-- <a href="{{route('download_reportinvoice', 'active')}}" id="download_data" disabled="disabled" class="btn btn-labeled bg-color-greenLight" > -->
                                <a href="{{route('download_reportinvoice', 'active')}}" id="download_data" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-cloud-download"></i></span>
                                    Download <?php echo $page_title; ?>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                               data-source="{{url('reportinvoiceload')}}"
                               data-paginate="500"
                               data-filter="#filter_table" >
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand" style="min-width: 80px;" ><i class="fa fa-fw fa-calendar text-muted hidden-md hidden-sm hidden-xs"></i> Prefix</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-group text-muted hidden-md hidden-sm hidden-xs"></i> Project Name</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-tachometer text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Real</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-tachometer text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Tagih</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-tachometer text-muted hidden-md hidden-sm hidden-xs"></i> Biaya</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-money text-muted hidden-md hidden-sm hidden-xs"></i> Tarif Telkom</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-money text-muted hidden-md hidden-sm hidden-xs"></i> Biaya Telkom</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-paperclip text-muted hidden-md hidden-sm hidden-xs"></i> Penghematan</th>
                                    <th data-class="expand"> Action</th>
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
                // alert("response:"+ response);
                // alert("href:"+ download_data);
                
                // Memecah URL menjadi potongan-potongan berdasarkan tanda '/'
                urlParts =  download_data.split('/');
                
                // Mendapatkan bagian terakhir dari URL, yang berisi "active"
                var lastPart = urlParts[urlParts.length - 2];

                // Debugging: menampilkan bagian terakhir dari URL
                // alert("Bagian terakhir dari URL:"+ lastPart);

                
                // Memperbarui bagian terakhir dari URL dengan yang baru
                urlParts[urlParts.length - 2] = response;

                // Menggabungkan potongan-potongan URL kembali menjadi URL lengkap
                var newDownloadData = urlParts.join('/');

                // Debugging: menampilkan URL yang telah diubah
                // alert("URL baru:"+ newDownloadData);
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
        //     dateFormat: "dd/mm/yy", 
		// 	defaultDate: "now",
		// 	changeMonth: true,
        //     changeYear: true,
		// 	numberOfMonths: 2,
        //     yearRange: 'c-10:c+1',
		// 	prevText: '<i class="fa fa-chevron-left"></i>',
		// 	nextText: '<i class="fa fa-chevron-right"></i>',
		// 	onClose: function (selectedDate) {
		// 		$("#ftDateEnd").datepicker("option", "minDate", selectedDate);
		// 	}
		// });
		// $("#ftDateEnd").datepicker({
        //     dateFormat: "dd/mm/yy", 
		// 	defaultDate: "+1w",
		// 	changeMonth: true,
        //     changeYear: true,
		// 	numberOfMonths: 2,
        //     yearRange: 'c-10:c+1',
		// 	prevText: '<i class="fa fa-chevron-left"></i>',
		// 	nextText: '<i class="fa fa-chevron-right"></i>',
		// 	onClose: function (selectedDate) {
		// 		$("#ftDateStart").datepicker("option", "maxDate", selectedDate);
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
