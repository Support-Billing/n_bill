
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
                    <h2>Add User</h2>
                </header>

                <!-- widget div--> 
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        
                        <form action="{{route('bank.update', [$data->bankID])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
	                        <fieldset>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Bank Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" value="{{$data->bankName}}" class="form-control">
                                        <span id="error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Bank Account <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="acc" value="{{$data->bankAcc}}" class="form-control">
                                        <span id="error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Bank Code <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="code" value="{{$data->bankCode}}" class="form-control">
                                        <span id="error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Bank Address/Branch <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="address" value="{{$data->bankAddress}}" class="form-control">
                                        <span id="error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Account Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="accname" value="{{$data->accName}}" class="form-control">
                                        <span id="error_name"></span>
                                    </div>
                                </div>
                                    

	                        </fieldset>

	                        <div class="form-actions">
	                            <div class="row">
	                                <div class="col-md-12 margin-right-5">
	                                    <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>

	                                    <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>                                
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
    
    $( document ).ready(function() {
        // setup page
        pageSetUp();
        my_form.init();
        $.sound_path = "{{url('/sound')}}/";

        var pageFunction = function () {
            $('input[name=phone]').numeric();
            my_global.auto_generate('auto_generate');
        };
        
        pageFunction();
    });
</script>
