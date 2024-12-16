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
                        <form action="{{route('server.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                        <fieldset>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Server Name <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverName" value="" id="input_serverName" class="form-control">
                                    <span id="error_serverName"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">IP Address <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverIP" value="" id="input_serverIP" class="form-control">
                                    <span id="error_serverIP"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Username <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverUsername" value="" id="input_serverUsername" class="form-control">
                                    <span id="error_serverUsername"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverPassword" value="" id="input_serverPassword" class="form-control">
                                    <span id="error_serverPassword"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Fingerprint <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="Fingerprint" value="" id="input_Fingerprint" class="form-control">
                                    <span id="error_fingerprint"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Port <sup>*</sup></label>
                                <div class="col-md-2">
                                    <input type="text" name="serverPort" value="" id="input_serverPort" class="form-control">
                                    <span id="error_serverPort"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Type <sup>*</sup></label>
                                <div class="col-md-5">
                                    <select name="serverType" id="input_serverType" class="select2 select2-offscreen " placeholder="" tabindex="-1" title="">
                                        <option value="" selected="selected">-- Choose Type --</option>
                                        <option value="1">MERA</option>
                                        <option value="2">ELASTIX</option>
                                        <option value="3">VOS</option>
                                    </select>
                                    <span id="error_serverType"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Protocol <sup>*</sup></label>
                                <div class="col-md-5">
                                    <select name="serverProtocol" id="input_serverProtocol" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                        <option value="" selected="selected">-- Choose Protocol --</option>
                                        <option value="sftp">SSH File Transfer Protocol (SFTP)</option>
                                    </select>
                                    <span id="error_serverProtocol"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">&nbsp;</label>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="checkbox">
                                                        <label class="first" >
                                                            <input type="checkbox" name="VPN" id="input_VPN"  class="form-control checkbox style-0" value="1" >
                                                            <span>&nbsp;&nbsp;Use VPN</span>
                                                        </label>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Name <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNName" value="" id="input_serverVPNName" class="form-control">
                                    <span id="error_serverVPNName"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Username <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNUsername" value="" id="input_serverVPNUsername" class="form-control">
                                    <span id="error_serverVPNUsername"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Password <sup>*</sup></label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNPassword" value="" id="input_serverVPNPassword" class="form-control">
                                    <span id="error_serverVPNPassword"></span>
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
