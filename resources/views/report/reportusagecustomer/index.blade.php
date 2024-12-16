
    
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
                                <a href="{{route('download_reportusageproject', 'active')}}" id="download_data" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-cloud-download"></i></span>
                                    Download Harian
                                </a>
                                <a href="{{route('download_reportusageprojectsummary', 'active')}}" id="download_data" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-cloud-download"></i></span>
                                    Download Summary
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                                data-source="{{url('reportusagecustomerload')}}"
                                data-paginate="500"
                                data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-archive text-muted hidden-md hidden-sm hidden-xs"></i> Prefix</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Alias Name</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-calendar text-muted hidden-md hidden-sm hidden-xs"></i> Tanggal</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-cloud text-muted hidden-md hidden-sm hidden-xs"></i> MERA</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-cloud text-muted hidden-md hidden-sm hidden-xs"></i> VOS</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-cloud text-muted hidden-md hidden-sm hidden-xs"></i> ELASTIX</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-cloud text-muted hidden-md hidden-sm hidden-xs"></i> Total </th>
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

    <!-- end row -->

</section>
<!-- end widget grid -->



<script type="text/javascript">
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
