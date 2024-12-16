
    
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
                    <h2><?php echo $page_title; ?></h2>
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
    
                            <!--
                            <div class="alert alert-info fade in" style="margin-bottom:0" >
                                <i class="fa-fw fa fa-info"></i>
                                <strong>Information</strong> 
                                Customer Group.
                            </div>

<pre style="margin:0 0 0" >
<code class="javascript">
    - Data pada form Filtering Customer Group adalah data yg active & Inactive.
    - Data yang dihasilkan setelang melakukan filtering berdasarkan Customer Group adalah data Customer Group.
     
    - Data pada form Filtering Customer adalah data Customer yang active.
    - Data yang dihasilkan setelang melakukan filtering berdasarkan Customer adalah data Customer Group yang memiliki relasi dengan Customer yang dipilih.
    - Data pada form Filtering Project adalah data Project yang active.
    - Data yang dihasilkan setelang melakukan filtering berdasarkan Project adalah data Customer Group yang memiliki relasi dengan Project yang dipilih.

    - Filtering Customer berfungsi untuk membantu/mempercepat dalam ketepatan pencarian data Customer Group yang memiliki dengan relasi Customer Group.
    - Filtering Project berfungsi untuk membantu/mempercepat dalam ketepatan pencarian data Project yang memiliki dengan relasi Customer Group.
    - Adapun perubahan fungsi inputan text sebelumnya menjadi select adalah untuk percepatan dan ketepatan dalam pencarian.
    - Filtering Customer Group berfungsi untuk membantu/mempercepat dalam ketepatan pencarian data Customer Group yang dibutuhkan.
    - Filtering Customer berfungsi untuk membantu/mempercepat dalam ketepatan pencarian data Customer yang dibutuhkan.
    - Filtering Project berfungsi untuk membantu/mempercepat dalam ketepatan pencarian data Project yang dibutuhkan.
    - Data yang dimunculkan di Filtering Customer Group adalah data yg active & Inactive.
    - Data yang dimunculkan di Filtering Customer adalah data Customer yang active.
    - Data yang dimunculkan di Filtering Project adalah data Project yang active.  
    - Adapun perubahan fungsi inputan text sebelumnya menjadi select adalah untuk percepatan dan ketepatan dalam pencarian.
</code>
</pre>
 -->
                            <form action="" id="filter_table" class="smart-form">
                                @csrf
                                <fieldset>
                                    <!-- 
                                    <div class="row">
                                        <section class="col col-2">
                                            <label class="label">Customer Group Name :</label>
                                        </section>
                                        <section class="col col-5">
                                            <label class="input">
                                                <input type="text" name="keyword" value="" class="input-sm" placeholder="">
                                            </label>
                                        </section>
                                        
                                        <section class="col col-2">
                                            <label class="input">
                                                <a href="javascript:void(0);" class="btn btn-default btn-sm pull-left margin-right-5" onclick="my_data_table.reload('#dt_basic')"><i class="fa fa-search"></i> Search</a> 
                                                <a href="javascript:void(0);" class="btn btn-default btn-sm pull-left" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                            </label>
                                        </section>
                                        
                                    </div>
                                    -->
                                    
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Customer Group</label>
                                        </section>
                                        <section class="col col-8">
                                            <label class="input">
                                                <select name="idxCoreCustomerGroup[]" id="input_idxCoreCustomerGroup" class="select2 select2-offscreen" placeholder="-- Choose Customer Groups --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($customerGroups as $keyp => $customerGroup)
                                                        <option value="{{ $customerGroup->idxCore }}">{{ $customerGroup->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col-md-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div>
<!-- 
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Customer :</label>
                                        </section>
                                        <section class="col col-6">
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
                                        <section class="col col-2 label">
                                            <label class="label">Project :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="idxCoreProject[]" id="input_idxCoreProject" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($projects as $keyp => $project)
                                                        <option value="{{ $project->idxCore }}">{{ $project->projectAlias }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Status Customer Group :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="statusData" id="input_statusData" class="select2 select2-offscreen" placeholder="-- Choose Status --" tabindex="-1" title="">
                                                    <option value="" selected></option>
                                                    <option value="">Active & Inactive</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                     -->
                                    
<!-- 
                                    <div class="row">
                                        <section class="col-md-12 margin-right-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div> -->

                                </fieldset>
                            </form>
                        </div>

                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="customergroup" data-url="customergroup/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?></a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                                data-source="{{url('customergroupload')}}"
                                data-paginate="500"
                                data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-credit-card text-muted hidden-md hidden-sm hidden-xs"></i> Jumlah Price</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-credit-card text-muted hidden-md hidden-sm hidden-xs"></i> Jumlah Customer</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-credit-card text-muted hidden-md hidden-sm hidden-xs"></i> Jumlah Project</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Status</th>
                                    <th data-hide="phone,tablet">Action</th>
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
    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_basic');
        my_form.init();
    });
</script>
