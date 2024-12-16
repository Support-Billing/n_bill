
<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-white" id="wid-id-0" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-togglebutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>List <?php echo $page_title; ?> </h2>

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
                                <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="Add" onclick="my_form.open(this.id)" data-module="syssetting" data-url="syssetting/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?></a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                               data-source="{{url('syssettingload')}}"
                               data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-key text-muted hidden-md hidden-sm hidden-xs"></i> Key</th>
                                    <th data-hide="user"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name</th>
                                    <th data-hide="phone,tablet"><i class="fa fa-fw fa-pencil txt-color-blue hidden-md hidden-sm hidden-xs"></i> Value</th>
                                    <th data-hide="phone,tablet"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i> Description </th>
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
});
</script>
