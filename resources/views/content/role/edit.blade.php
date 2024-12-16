<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" 
                 data-widget-editbutton="false" 
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-deletebutton="false"
                 data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2><?php echo $page_title; ?></h2>
                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <form action="{{route('role.update', [$data->id])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Role Name  <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="name" value="{{$data->name}}" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Description</label>
                                    <div class="col-md-7">
                                        <textarea name="description" cols="40" rows="10" class="form-control">{{$data->description}}</textarea>
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset>
                                <legend>
                                    Otoritas Modul
                                    <span class="pull-right font-xs">
                                        <i class="fa fa-eye"></i> View,
                                        <i class="fa fa-plus"></i> Add,
                                        <i class="fa fa-edit"></i> Edit,
                                        <i class="fa fa-trash-o"></i> Delete,
                                        <!-- <i class="fa fa-legal"></i> Approve, -->
                                        <i class="fa fa-download"></i> Export,
                                        <i class="fa fa-upload"></i> Import
                                    </span>
                                </legend>

                                <div id="nestable-menu-role">
                                    <span class="col-md-6 no-padding pull-left">
                                        <button type="button" class="btn btn-default" data-action="expand-all">
                                            Expand All
                                        </button>
                                        <button type="button" class="btn btn-default" data-action="collapse-all">
                                            Collapse All
                                        </button>
                                    </span>
                                    <span class="col-md-1 no-padding pull-right">
                                        <select class="form-control" id="opt_otoritas_data" target-selected="dd_roles" onchange="my_global.set_value_selected(this.id)">
                                            <option value="1">Self</option>
                                            <option value="2">Group</option>
                                            <option value="3">All</option>
                                        </select>
                                    </span>
                                    <span class="col-md-3 no-padding pull-right">
                                        <span style="display: none">
                                            <input type="checkbox" name="cb_select_menu" value="" id="cb_select_menu" target-selected="cb_select">
                                        </span>
                                        <a href="javascript:void(0);" class="btn btn-labeled btn-default margin-right-5" onclick="_do_change_select(true)"><span class="btn-label"><i class="fa fa-check-square-o"></i></span> Select All</a>
                                        <a href="javascript:void(0);" class="btn btn-labeled btn-default margin-right-5" onclick="_do_change_select(false)"><span class="btn-label"><i class="fa fa-square-o "></i></span> Diselect All</a>
                                    </span>
                                </div>

                                <div style="max-width: none !important;" id="nestable3" class="dd">
                                    <?php echo $data_menu; ?>
                                </div>

                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12 margin-right-2">
                                        <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a><a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a> 
                                    </div>
                                </div>
                            </div>
                        </form>

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
// $( document ).ready(function() {
    pageSetUp();
    my_form.init();

    var _nestable_setting = function () {
        $('#nestable3').nestable();


        $('#nestable-menu-role').on('click', function (e) {
            var target = $(e.target), action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });

        $(".dd-nodrag").on("mousedown", function (event) { // mousedown prevent nestable click
            event.preventDefault();
            return false;
        });

        $(".dd-nodrag").on("click", function (event) { // click event
            event.preventDefault();
            return false;
        });
    };

    var _do_change_select = function (status) {
        $('#cb_select_menu').prop('checked', status);
        my_global.select_all('cb_select_menu');
    };

    var _set_all_otoritas_data = function (val) {
        $('.dropdown_roles').val(val);
    };


    loadScript("js/plugin/jquery-nestable/jquery.nestable.min.js", _nestable_setting);
// });
</script>