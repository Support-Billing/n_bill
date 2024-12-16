<html>
    <head>
        <script id="tinyhippos-injected">
            if (window.top.ripple) {
                window.top.ripple("bootstrap").inject(window, document);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    </head>
    <body>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-times"></i>
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span> <?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <form action="{{route('menu.update', [$data->id])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                @csrf
                <input type="hidden" value="PUT" name="_method">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Menu Name<sup>*</sup></label>
                        <div class="col-md-8">
                            <input type="text" name="menu_name" value="{{$data->name}}" id="input_menu_name" class="form-control">
                            <span id="error_menu_name"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">URL <sup>*</sup></label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-link"></i></span>
                                <input type="text" name="menu_url" value="{{$data->url}}" id="input_menu_url" class="form-control">
                            </div>
                            <span id="error_menu_url"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Icon</label>
                        <div class="col-md-3">
                            <input type="text" name="menu_icon" value="{{$data->icon}}" id="input_menu_icon" class="form-control">
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                pageSetUp();
                my_form.init();
            });
        </script>
    </body>
</html>