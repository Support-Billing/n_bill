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
            <!-- <pre>
            <?php echo $query ; ?>
            </pre> -->
            <form action="{{ route('customergroup.store_project', ['idxCustomerGroup' => $idxCustomerGroup, 'idxCustomer' => $idxCustomer]) }}" 
            id="finputProject" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                 @csrf
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Projects Name<sup>*</sup></label>
                        <div class="col-md-6">
                            <select name="projectID" id="input_projectID" class="select2 select2-offscreen" >
                                <option value="" selected="selected" >-- Choose Project Name --</option>
                                @foreach ($projects as $key => $project)
                                    <option value="{{ $project->projectID }}" >{{ $project->projectName }}</option>
                                @endforeach
                            </select>
                            <span id="error_projectID"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back-project" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybutton-add-project" class="btn btn-labeled btn-success" onclick="my_form.submit('#finputProject')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                pageSetUp();
                my_form.init();
            });
        </script>
    </body>
</html>