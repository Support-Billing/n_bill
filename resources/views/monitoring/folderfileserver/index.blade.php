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
                                        <section class="col col-4 label">
                                            <label class="label">Folder Name :</label>
                                        </section>
                                        <section class="col col-4 label">
                                            <label class="label">Date Period :</label>
                                        </section>
                                        <!-- <section class="col col-2 label">
                                            <label class="label">Difference File :</label>
                                        </section> -->
                                    </div>
                            
                                    <div class="row">
                                        <section class="col col-4">
                                            <label class="input">
                                                <select name="folderName" id="input_folderName" class="select2 select2-offscreen " placeholder="-- Choose Folder Name --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($results as $keyf => $result)
                                                        <option value="{{ $result->FolderName }}">{{ $result->FolderName }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
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
                                        <!-- <section class="col col-2">
                                            <label class="input">
                                                <select name="serverType" id="input_serverType" class="select2 select2-offscreen " placeholder="" tabindex="-1" title="">
                                                    <option value="" selected="selected">-- Choose Type --</option>
                                                    <option value="MERA">MERA</option>
                                                    <option value="ELASTIX">ELASTIX</option>
                                                    <option value="VOS">VOS</option>
                                                    <option value="ASTERISK">ASTERISK</option>
                                                </select>
                                            </label>
                                        </section> -->
                                    </div>

                                    <div class="row">
                                        <section class="col-md-12 margin-right-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic');"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        @if($import_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <!-- <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled bg-color-greenLight" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="customer" data-url="filelog/download"><span class="btn-label"><i class="fa fa-file-excel-o"></i></span> Download <?php echo $page_title; ?></a> -->
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                               data-source="{{url('folderfileserverload')}}"
                               data-filter="#filter_table">
                            <thead>
                                <tr>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> No</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Folder Name</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> File Name</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> size Core</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> size Ready</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> size Result</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> jumlah Result</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Status Ready Result</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Action</th>
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
		$("#ftDateStart").datepicker({
			defaultDate: "now",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateEnd").datepicker("option", "maxDate", selectedDate);
			}
		});
		$("#ftDateEnd").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateStart").datepicker("option", "minDate", selectedDate);
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
