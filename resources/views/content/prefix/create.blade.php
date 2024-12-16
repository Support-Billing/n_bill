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
                        <form action="{{route('prefix.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                        <fieldset>

                            <div class="form-group">
								<label class="col-md-2 control-label">&nbsp; Project<sup>*</sup></label>
								<div class="col-md-5">
									<select name="projectID" id="error_projectID" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" id="input_xxx" selected="selected">-- Choose Project --</option>
										<option value="0" >Non CLI</option>
										<option value="1">CLI</option>
									</select>
									<span id="error_projectID"></span>
								</div>
							</div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Prefix <sup>*</sup></label>
                                <div class="col-md-3">
                                    <!-- customerName jadinya di hilangin -->
                                    <!-- <input type="hidden" name="customerName" value="customerName" class="form-control"> -->
                                    <!-- <input type="hidden" name="projectID" value="customerName" class="form-control"> -->
                                    <input type="text" name="prefixNumber" value="" id="input_prefixNumber" class="form-control">
                                    <span id="error_prefixNumber"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">&nbsp;</label>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <span class="input-group-addon">
                                                <section class="smart-form">
                                                    <label class="checkbox" style="padding-top:0">
                                                        <input type="checkbox" name="allip" id="subscription">
                                                        <i>&nbsp;</i>All IP
                                                    </label>
                                                </section>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </fieldset>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12 margin-right-2">
                                    
                                    <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>

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
    });
    
</script>
