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
                                        <section class="col col-2">
                                            <label class="label">Name :</label>
                                        </section>
                                        <section class="col col-5">
                                            <label class="input">
                                                <input type="text" name="keyword" value="" class="input-sm" placeholder="">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-2">
                                            <label class="label">9 Point Kolom : <br /><sup>* Pilihan tidak memiliki kolom </sup> </label>
                                        </section>
                                        <section class="col col-5">
                                            <label class="input">
                                                <select name="poinKolom[]" id="input_poinKolom" class="select2 select2-offscreen " placeholder="-- Choose Poin Kolom --" multiple="multiple" tabindex="-1" title="">
                                                    <option value="datetime" > Datetime </option>
                                                    <option value="sourceNo" > Source No </option>
                                                    <option value="sourceNoOut" > Source No Out </option>
                                                    <option value="sourceIP" > Source IP </option>
                                                    <option value="elapsedTime" > Elapsed Time </option>
                                                    <option value="destNo" > Dest No </option>
                                                    <option value="destNoOut" > Dest No Out </option>
                                                    <option value="destIP" > Dest IP </option>
                                                    <option value="destName" > Dest Name </option>
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col col-2">
                                            <label class="input">

                                                <a href="javascript:void(0);" class="btn btn-default btn-sm pull-left margin-right-5" onclick="my_data_table.reload('#dt_basic')"><i class="fa fa-search"></i> Search</a> 

                                                <a href="javascript:void(0);" class="btn btn-default btn-sm pull-left" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>

                                            </label>
                                        </section>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        @if($import_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled bg-color-greenLight text-white" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="customer" data-url="filelog/download"><span class="btn-label"><i class="fa fa-cloud-download"></i></span> Download <?php echo $page_title; ?></a>
                                
                                <a 
                                    href='repaircsvparser/repaircsvparser/get_kolom' 
                                    id='mybutton-calculate'
                                    class='btn btn-labeled bg-color-orange text-white' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>
                                        Get 9 Kolom
                                </a>
                                
                                <a 
                                    href='repaircsvparser/repaircsvparser/get_kolom_customer' 
                                    id='mybutton-calculate-customer'
                                    class='btn btn-labeled bg-color-orange text-white' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>
                                        Get Kolom Customer
                                </a>
                                
                                <a 
                                    href='repaircsvparser/repaircsvparser/get_kolom_supplier' 
                                    id='mybutton-calculate-supplier'
                                    class='btn btn-labeled bg-color-orange text-white' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>
                                        Get Kolom Supplier
                                </a>
                                
                                <a 
                                    href='repaircdr/repaircdr/readymovecdr' 
                                    id='mybutton-calculate'
                                    class='btn btn-labeled bg-color-orange text-white'
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>
                                        Move To CDR
                                </a>
                                
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                               data-source="{{url('repaircsvparserload')}}"
                               data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Type Server</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Folder Name</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> File Name</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Line Number</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Check Data</th>
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
<div class="modal fade bs-example-modal-lg" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>  
<!-- /.modal -->


<script type="text/javascript">
    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_basic');
        my_form.init();
    });
</script>
