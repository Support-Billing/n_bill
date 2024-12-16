
    
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
                                            <label class="label">Project :</label>
                                        </section>
                                        <section class="col col-10">
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
                                            <label class="label">Prefix :</label>
                                        </section>
                                        <section class="col col-10">
                                            <label class="input">
                                                <select name="projectPrefixSrv[]" id="input_projectPrefixSrv" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
                                                    @foreach ($projectPrefixSrvs as $keyp => $projectPrefixSrv)
                                                        <option value="{{ $projectPrefixSrv->prefixNumber }}">{{ $projectPrefixSrv->prefixNumber }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Status Project :</label>
                                        </section>
                                        <section class="col col-10">
                                            <label class="input">
                                                <select name="statusProject" id="input_statusProject" class="select2 select2-offscreen" placeholder="-- Choose Project  --" tabindex="-1" title="">
                                                    @switch($statusDataGet)
                                                        @case('active')
                                                            <option value="0" selected="selected" >Waiting</option>
                                                            <option value="1" selected="selected" >Free Trial</option>
                                                            <option value="2" selected="selected" >Trial on Subscribe </option>
                                                            <option value="3" selected="selected" >Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break

                                                        @case('inactive')
                                                            <option value="0" selected="selected" >Waiting</option>
                                                            <option value="1">Free Trial</option>
                                                            <option value="2">Trial on Subscribe </option>
                                                            <option value="3">Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break

                                                        @case('free')
                                                            <option value="0">Waiting</option>
                                                            <option value="1" selected="selected" >Free Trial</option>
                                                            <option value="2">Trial on Subscribe </option>
                                                            <option value="3">Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break
                                                        @case('trial')
                                                            <option value="0">Waiting</option>
                                                            <option value="1">Free Trial</option>
                                                            <option value="2" selected="selected" >Trial on Subscribe </option>
                                                            <option value="3">Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break
                                                        @case('subscribe')
                                                            <option value="0">Waiting</option>
                                                            <option value="1">Free Trial</option>
                                                            <option value="2">Trial on Subscribe </option>
                                                            <option value="3" selected="selected" >Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break
                                                        @case('waiting')
                                                            <option value="0" selected="selected" >Waiting</option>
                                                            <option value="1">Free Trial</option>
                                                            <option value="2">Trial on Subscribe </option>
                                                            <option value="3">Subscribe</option>
                                                            <option value="4">Closed</option>
                                                            @break
                                                            
                                                        @default
                                                            <option value="" selected="selected"></option>
                                                            <option value="0">Waiting</option>
                                                            <option value="1">Free Trial</option>
                                                            <option value="2">Trial on Subscribe </option>
                                                            <option value="3">Subscribe</option>
                                                            <option value="4">Closed</option>
                                                    @endswitch
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    <!-- 
                                    <div class="row">
                                        <section class="col col-2 label">
                                            <label class="label">Status Data :</label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="input">
                                                <select name="statusData" id="input_statusData" class="select2 select2-offscreen" placeholder="-- Choose Status Data  --" tabindex="-1" title="">
                                                    <option value="" selected="selected"></option>
                                                    <option value="1" >Status : Active</option>
                                                    <option value="2" >Status : Closed</option>
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    -->
                                    
                                    <div class="row">
                                        <section class="col col-2 label"> 
                                            <label class="label">CLI :</label>
                                        </section>
                                        <section class="col col-8">
                                            <label class="input">
                                                <select name="isCLI" id="input_isCLI" class="select2 select2-offscreen" placeholder="-- Choose CLI --" tabindex="-1" title="">
                                                    <option value="" selected="selected"></option>
                                                    <option value="">All Status CLI </option>
                                                    <option value="0">Non CLI</option>
                                                    <option value="1">CLI</option>
                                                </select>
                                            </label>
                                        </section>
                                        
                                        <section class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right " style="margin-right:15px"  onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                        </section>
                                    </div>
                                    <!--                                     
                                    <div class="row">
                                        <section class="col-md-12 margin-right-2 input">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.reload('#dt_basic'); getEncriptedUrl();"><i class="fa fa-search"></i> Search</a> 
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm pull-right margin-right-5" onclick="my_data_table.filter.reset('#dt_basic')"><i class="fa fa-refresh"></i> Reset</a>
                                        </section>
                                    </div>
                                     -->
                                </fieldset>
                            </form>
                        </div>

                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="project" data-url="project/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?></a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                                data-source="{{url('projectload')}}"
                                data-paginate="500"
                                data-filter="#filter_table">
                            <thead>                         
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Alias Name</th>
                                    <th data-hide="phone" width="250px" ><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i> Prefix</th>
                                    <th data-hide="delicious" style="text-align:center" > Modified</th>
                                    <th data-hide="delicious"><i class="fa fa-fw fa-delicious text-muted hidden-md hidden-sm hidden-xs"></i> Status</th>
                                    <th data-hide="phone,tablet" width="150px" >Action</th>
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



        // Handle project select change
        $('#input_idxCoreProject').on('change', function() {
            if ($(this).val()) {
                // Disable prefix select
                $('#input_projectPrefixSrv').prop('disabled', true).val(null).trigger('change');
            } else {
                // Enable prefix select if no project is selected
                $('#input_projectPrefixSrv').prop('disabled', false);
            }
        });
 
        
        // Handle prefix select change
        $('#input_projectPrefixSrv').on('change', function() {
            if ($(this).val()) {
                // Disable project select
                $('#input_idxCoreProject').prop('disabled', true).val(null).trigger('change');
            } else {
                // Enable project select if no project is selected
                $('#input_idxCoreProject').prop('disabled', false);
            }
        });

    });
</script>
