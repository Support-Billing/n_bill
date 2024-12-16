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
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span><?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <form action="{{route('project.store_accounts', $projectID)}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>
                    <div class="row form-group smart-form">
                        <label class="col-md-4 control-label">Account/SIP Info <sup>*</sup></label>
                        <section class="col col-5">
                            <label class="input"> 
                                <input type="text" name="accountNumber" id="input_accountNumber" class="form-control">
                                <span id="error_accountNumber"></span>
                            </label>
                        </section>
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
                // setup page
                pageSetUp();
                my_form.init();
                $.sound_path = "{{url('/sound')}}/";
            });
        </script>
        
    </body>
</html>