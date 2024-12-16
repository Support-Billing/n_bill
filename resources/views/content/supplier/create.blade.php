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
                        <form action="{{route('supplier.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                        <fieldset>
                            

                            <div class="form-group">
                                <label class="col-md-2 control-label">Supplier Name <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverName" value="" id="input_serverName" class="form-control">
                                    <span id="error_servername"></span>
                                </div>
                            </div>
                            <!-- 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Detik Awal <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="detikAwal" value="" id="input_detikAwal" class="form-control">
                                    <span id="error_detikawal"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Detik Per Unit <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="detikUnit" value="" id="input_detikUnit" class="form-control">
                                    <span id="error_detikUnit"></span>
                                </div>
                            </div> -->

							<header><b>&nbsp; &nbsp; IP Supplier </b></header>
							<br />
							<fieldset title="IP Supplier" >
                                <div class="row form-group smart-form">
                                    <label class="col col-2 control-label">IP Address <sup>*</sup></label>
                                    <section class="col col-2">
                                        <label class="input"> 
                                            <input type="text" name="name" placeholder="Name" class="form-control">
                                        </label>
                                        <span id="error_name"></span>
                                    </section>
                                    <section class="col col-2">
										<div class="input-group">
											<span class="input-group-addon">To</span>
                                            <input type="text" name="name" placeholder="&nbsp;&nbsp;Name" class="form-control">
										</div>
                                        <span id="error_name"></span>
                                    </section>
                                </div>
							</fieldset>

							<header><b>&nbsp; &nbsp; Prefix </b></header>
							<br />
							<fieldset title="IP Supplier" >

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Prefix <sup>*</sup></label>
                                    <div class="col-md-4">
                                        <input type="text" name="contact1" value="" id="input_xxx" class="form-control">
                                        <span id="error_name"></span>
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

							<header><b>&nbsp; &nbsp; Price </b></header>
							<br />
							<fieldset title="IP Supplier" >
                                <div class="row form-group smart-form">
                                    <label class="col col-2 control-label">Range <sup>*</sup></label>
                                    <section class="col col-3">
                                        <label class="input-group"> 
                                            <input class="form-control" id="input_startRange" type="text" placeholder="Start Range" name="startRange" >
                                            <span class="input-group-addon">menit</span>
                                        </label>
                                        <span id="error_startRange"></span>
                                    </section>
                                    <section class="col col-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">s/d</span>
                                            <input class="form-control" id="input_endRange" type="text" placeholder="End Range" name="endRange">
                                            <span class="input-group-addon">menit</span>
                                        </div>
                                        <span id="error_endRange"></span>
                                    </section>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tarif Per Menit <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="tarifPerMenit" value="" id="input_tarifPerMenit" class="form-control">
                                        <span id="error_tarifPerMenit"></span>
                                    </div>
                                </div>
							</fieldset>
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
