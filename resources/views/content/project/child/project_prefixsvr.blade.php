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
            <form action="{{route('project.store_prefixsvr', $projectID)}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Server IP Destination<sup>*</sup></label>
                        <div class="col-md-5">
                            <select name="serverID" id="input_serverID" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                <option value="" selected="selected">-- Choose Server Name --</option>
                                @foreach ($servers as $key => $server)
                                    <option value="{{ $server->serverID }}">{{ $server->serverID }} - {{ $server->serverName }}</option>
                                @endforeach
                            </select>
                            <span id="error_serverID"></span>
                        </div>
                    </div>
                    <div class="row form-group smart-form">
                        <label class="col-md-4 control-label">Short Server Name <sup>*</sup></label>
                        <section class="col col-5">
                            <label class="input"> 
                                <input type="text" name="serverName" id="input_serverName" class="form-control">
                                <span id="error_serverName"></span>
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