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
            <form action="{{route('customergroup.store_prices', [$idx])}}" id="finputprices" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>

                    <div class="row form-group smart-form">
                        <label class="col col-4 control-label">Range <sup>*</sup></label>
                        <section class="col col-4">
                            <label class="input-group"> 
                                <input class="form-control" id="input_startRange" type="text" name="startRange" >
                                <span class="input-group-addon">menit</span>
                            </label>
                            <span id="error_startRange"></span>
                        </section>
                        <section class="col col-4">
                            <div class="input-group">
                                <span class="input-group-addon">s/d</span>
                                <input class="form-control" id="input_endRange" type="text" name="endRange">
                                <span class="input-group-addon">menit</span>
                            </div>
                            <span id="error_endRange"></span>
                        </section>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Tarif Per Menit <sup>*</sup></label>
                        <div class="col-md-4">
                            <input type="text" name="tarifPerMenit" value="" id="input_tarifPerMenit" class="form-control">
                            <span id="error_tarifPerMenit"></span>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finputprices')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                pageSetUp();
                my_form.init();
            });
        </script>
    </body>
</html>