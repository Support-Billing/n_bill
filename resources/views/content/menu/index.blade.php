<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-white" id="wid-id-0" 
                 data-widget-editbutton="false" 
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-deletebutton="false"
                 data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2><?php echo $page_title; ?></h2>
                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body ">
                        <!-- widget content -->
                        <div class="widget-body">
                            <div id="nestable-menu">

                                @if($insert_otoritas_modul)
                                    <a href="{{route('menu.create')}}" id="mybutton-add" class="btn btn-labeled btn-primary" data-toggle="modal" data-target="#remoteModal" data-keyboard="false" data-backdrop="static"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add Menu Settings</a>
                                @endif

                                <button type="button" class="btn btn-default" data-action="expand-all">
                                    Expand All
                                </button>
                                <button type="button" class="btn btn-default" data-action="collapse-all">
                                    Collapse All
                                </button>

                                <!-- Dynamic Modal -->  
                                <div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
                                    <div class="modal-dialog">  
                                        <div class="modal-content">
                                            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
                                        </div>  
                                    </div>  
                                </div>  
                                <!-- /.modal -->

                            </div>
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <h6>Drag node to sort menu order.</h6>
                                    <div id="dload"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

<script type="text/javascript">
    // function pagefunction() {
    //     $('#dload').load("menu/daas/edit");
    // };
    // function load_and_reset_form() {
    //     alert('aaaaaaaaaaa');
    //     pagefunction();
    //     my_form.reset('#finput');
    // };

    var pagefunction = function () {
            $('#dload').load("menu/listable/edit");
        };
        
    var load_and_reset_form = function () {
        pagefunction();
        my_form.reset('#finput');
    };
    $( document ).ready(function() {
        pageSetUp();

        loadScript("js/plugin/jquery-nestable/jquery.nestable.min.js", pagefunction);
    });

</script>