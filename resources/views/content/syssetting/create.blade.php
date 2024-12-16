

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
                        <form action="{{route('syssetting.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Code <sup id="req_id_lokasi_kerja" style="display: none;">*</sup></label>
                                <div class="col-md-10">
                                    <div class="col-md-2 no-padding margin-right-5">
                                        <input type="text" name="no_lokasi_kerja" value="" id="input_xxx" id="id_lokasi_kerja" class="form-control" readonly="readonly">
                                    </div>
                                    <div class="smart-form col-md-3">
                                        <section>
                                            <label class="toggle">
                                                <input type="checkbox" name="auto_generate" value="t" checked="checked" id="auto_generate" data-target="id_lokasi_kerja" onchange="my_global.auto_generate(this.id)">
                                                <i data-swchon-text="ON" data-swchoff-text="OFF"></i>
                                                Auto Generate
                                            </label>
                                        </section>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Key <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="key" value="" id="input_xxx" class="form-control">
                                </div>
                                    <span id="error_key"></span>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Name <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="name" value="" id="input_xxx" class="form-control">
                                </div>
                                    <span id="error_name"></span>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Value <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="value" value="" id="input_xxx" class="form-control">
                                </div>
                                    <span id="error_value"></span>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Description <sup>*</sup></label>
                                <div class="col-md-9">
                                    <textarea name="description" cols="40" rows="10" class="form-control"></textarea>
                                </div>
                                <span id="error_description"></span>
                            </div>

                        </fieldset>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12 margin-right-2">
                                    
                                    <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-2" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>

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
