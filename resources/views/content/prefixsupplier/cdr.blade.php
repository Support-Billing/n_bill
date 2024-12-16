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
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>Update CDR <?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <form action="{{route('menu.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            @csrf
            <fieldset>
                <div class="row form-group smart-form">
                    <label class="col col-3 control-label">Periode Free <sup>*</sup></label>
                    <section class="col col-3">
                        <label class="input"> 
                            <input class="form-control" id="from" type="text" placeholder="From">
                        </label>
                        <span id="error_name"></span>
                    </section>
                    <section class="col col-3">
                        <div class="input">
                            <input class="form-control" id="to" type="text" placeholder="Select a date">
                        </div>
                        <span id="error_name"></span>
                    </section>
                </div>

            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-recalculate" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Recalculate</a>
            <a href="javascript:void(0);" id="mybutton-recalculate-all" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span> Recalculate All</a>
        </div>
        <script type="text/javascript">
            
            var pagefunction = function() {
                // Date Range Picker
                $("#from").datepicker({
                    defaultDate: "now",
                    changeMonth: true,
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    onClose: function (selectedDate) {
                        $("#to").datepicker("option", "minDate", selectedDate);
                    }
                });
                $("#to").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    onClose: function (selectedDate) {
                        $("#from").datepicker("option", "maxDate", selectedDate);
                    }
                });
            };

            $( document ).ready(function() {
                // setup page
                pageSetUp();
                pagefunction();
                my_form.init();
                $.sound_path = "{{url('/sound')}}/";
            });
        </script>
        
    </body>
</html>