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
                            <form action="" id="filter_table" class="smart-form">
                                <fieldset>
                                    <div class="row">
                                        <section class="col col-sm-2">
                                            <label class="label">Work Location :</label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">
                                                <select name="id_lokasi_kerja" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                                    <option value="" selected="selected">--Choose Work Location--</option>
                                                    @foreach ($worklocations as $key => $worklocation)
                                                        <option value="{{ $worklocation->id }}">{{ $worklocation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col col-sm-2">
                                            <label class="label">Department Name :</label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">
                                                <select name="id_unit_kerja" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                                    <option value="" selected="selected">--Choose Department--</option>
                                                    <option value="K0001">Comex Head</option>
                                                    <option value="K0002">Cluster Head</option>
                                                    <option value="K0003">Branch Manager</option>
                                                    <option value="K0004">BSO</option>
                                                    <option value="K0005">Verificator Head</option>
                                                    <option value="K0006">Appraisal Head</option>
                                                    <option value="K0007">Appraisal</option>
                                                    <option value="K0008">Verificator</option>
                                                    <option value="K0009">Legal Head</option>
                                                    <option value="K0010">Legal</option>
                                                    <option value="K0011">Commite</option>
                                                    <option value="K0012">BI Admin</option>
                                                    <option value="K0013">Acceptance</option>
                                                    <option value="K0014">Disburse</option>
                                                    <option value="K0015">Custudy</option>
                                                    <option value="K0016">CPR Head</option>
                                                    <option value="K0017">CPR</option>
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-sm-2">
                                            <label class="label">NIK's / Empoyee Name :</label>
                                        </section>
                                        <section class="col col-3">
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
                                </fieldset>
                            </form>
                        </div>
                        
                        @if($insert_otoritas_modul)
                        <div class="padding-5 border-bottom-1">
                            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="employee" data-url="employee/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?></a>
                            <div class="clearfix"></div>
                        </div>
                        @endif

                        <div class="overflow-x">
                            <table id="dt_basic" 
                                   class="table table-striped table-bordered table-hover" 
                                   width="100%" style="margin-top: 0 !important;"
                                   data-source="{{url('employeeload')}}"
                                   data-filter="#filter_table">
                                <thead>                         
                                    <tr>
                                        <th data-hide="phone" class="text-align-center">NO</th>
                                        <th data-class="expand" class="text-align-center">NIK</th>
                                        <th data-class="expand">EMPLOYEE NAME</th>
                                        <th data-class="expand">WORK LOCATION NAME</th>
                                        <th data-class="expand">PHONE</th>
                                        <th data-hide="phone" class="text-align-center">FUNCTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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
$( document ).ready(function() {
    pageSetUp();
    my_data_table.init('#dt_basic');
});
</script>