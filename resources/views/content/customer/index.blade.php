
    
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
<!-- 
                            <div class="alert alert-info fade in" style="margin-bottom:0" >
                                <i class="fa-fw fa fa-info"></i>
                                <strong>Information</strong> 
                                Customer
                            </div>

<pre style="margin:0 0 0" >
<code class="javascript">
    - Customer Tiering adalah Customer yang memiliki loyalitas/tergabung kedalam salah satu Group Member.
    - Customer Flat Structure adalah Customer yang tidak tergabung kedalam Group Member.
</code>
</pre> -->
                            <form action="" id="filter_table" class="smart-form">
                                @csrf
                                <fieldset>
                                    <!--                                     
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Customer Group :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="idxCoreCustomerGroup[]" id="input_idxCoreCustomerGroup" class="select2 select2-offscreen" placeholder="-- Choose Customer Group --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($customerGroups as $keyp => $customerGroup)
                                                        <option value="{{ $customerGroup->idxCore }}">{{ $customerGroup->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                    </div> 
                                    -->

                                    <div class="row">
                                        <section class="col col-1 label">
                                            <label class="label">Customer</label>
                                        </section>
                                        <section class="col col-9">
                                            <label class="input">
                                                <select name="idxCoreCustomer[]" id="input_idxCoreCustomer" class="select2 select2-offscreen" placeholder="-- Choose Customer --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($customers as $keyp => $customer)
                                                        <option value="{{ $customer->idxCore }}">{{ $customer->title }}. {{ $customer->clientName }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <section class="col col-1 label">
                                            <label class="label">Project :</label>
                                        </section>
                                        <section class="col col-9">
                                            <label class="input">
                                                <select name="idxCoreProject[]" id="input_idxCoreProject" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($projects as $keyp => $project)
                                                        <option value="{{ $project->idxCore }}">{{ $project->projectAlias }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm " onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div>
<!--                                     
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Status Customer :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="statusData" id="input_statusData2" class="select2 select2-offscreen" placeholder="-- Choose Status --" tabindex="-1" title="">
                                                    <option value="" selected="selected"></option>
                                                    <option value="Active">Active</option>
                                                    <option value="Non Active">Non Active</option>
                                                    <option value="Delete">Delete</option>
                                                </select>
                                            </label>
                                        </section>
                                        
                                    </div>
                                    
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Tiering :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="isTier" id="input_isTier" class="select2 select2-offscreen" placeholder="-- Choose Tiering --" tabindex="-1" title="">
                                                    <option value="" selected="selected"></option>
                                                    <option value="">All Data</option>
                                                    <option value="1">Tiering</option>
                                                    <option value="0">Flat Structure</option>
                                                </select>
                                            </label>
                                        </section>
                                    </div> 
                                    
                                    <div class="row">
                                        <section class="col-md-12 margin-right-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div> -->
                                </fieldset>
                            </form>
                            <!-- <form action="" id="filter_table" class="smart-form">
                                @csrf
                                <fieldset>
                                    <div class="row">
                                        <section class="col col-5 label">
                                            <label class="label">Name :</label>
                                        </section>
                                        <section class="col col-7 label">
                                            <label class="label">Date Created :</label>
                                        </section>
                                    </div>
                            
                                    <div class="row">
                                        <section class="col col-5">
                                            <label class="input">
                                                <input type="text" name="keyword" value="" class="input-sm" placeholder="">
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
                                    </div>
                                    
                                    <div class="row">
                                        <section class="col-md-12 margin-right-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic')"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div>
                                </fieldset>
                            </form> -->
                        </div>

                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="customer" data-url="customer/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?></a>
                                
                                <!-- <a 
                                    href='customer/{customer}/edit_cdr' 
                                    id='mybutton-recalculate'
                                    class='btn btn-labeled bg-color-orange' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Update CDR <?php echo $page_title; ?></a> -->
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                                data-source="{{url('customerload')}}"
                                data-paginate="500"
                                data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Alias</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Phone</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Primary Manage Name</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Contact Person/PIC</th>
                                    <th width="300px" data-hide="phone,tablet">Action</th>
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
	var pagefunction = function() {
		 // Date Range Picker
		$("#ftDateStart").datepicker({
			defaultDate: "now",
			changeMonth: true,
			numberOfMonths: 2,
            dateFormat: 'dd-mm-yy',
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateEnd").datepicker("option", "minDate", selectedDate);
			}
		});
		$("#ftDateEnd").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 2,
            dateFormat: 'dd-mm-yy',
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

        // $('#input_statusData').select2({
        //     placeholder: "-- Choose Status --",
        //     minimumResultsForSearch: Infinity // Disable the search box
        // });

    });
</script>
