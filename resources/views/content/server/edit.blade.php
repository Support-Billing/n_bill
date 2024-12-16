
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
                    <h2>Edit Server</h2>
                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        
                        <form action="{{route('server.update', [$data->serverID])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
                            <fieldset>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Server Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverName" value="{{$data->serverName}}" id="input_serverName" class="form-control">
                                        <span id="error_serverName"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">IP Address <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverIP" value="{{$data->serverIP}}" id="input_serverIP" class="form-control">
                                        <span id="error_serverIP"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Username <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverUsername" value="{{$data->serverUsername}}" id="input_serverUsername" class="form-control">
                                        <span id="error_serverUsername"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Password <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverPassword" value="{{$data->serverPassword}}" id="input_serverPassword" class="form-control">
                                        <span id="error_serverPassword"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Fingerprint <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="Fingerprint" value="{{$data->Fingerprint}}" id="input_Fingerprint" class="form-control">
                                        <span id="error_Fingerprint"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Port <sup>*</sup></label>
                                    <div class="col-md-2">
                                        <input type="text" name="serverPort" value="{{$data->serverPort}}" id="input_serverPort" class="form-control">
                                        <span id="error_serverPort"></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Type <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <select name="serverType" id="input_serverType" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                            @if($data->serverType ==1)
                                                <option itemref="selected" value="1">MERA</option>
                                                <option value="2">ELASTIX</option>
                                                <option value="3">VOS</option>
                                            @elseif($data->serverType ==2)
                                                <option value="1">MERA</option>
                                                <option selected="selected" value="2">ELASTIX</option>
                                                <option value="3">VOS</option>
                                            @else  
                                                <option value="1">MERA</option>
                                                <option value="2">ELASTIX</option>
                                                <option selected="selected" value="3">VOS</option>
                                            @endif
                                        </select>
                                    <span id="error_serverType"></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Protocol <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <select name="serverProtocol" id="input_serverProtocol" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                            <option value="sftp" selected="selected" >SSH File Transfer Protocol (SFTP)</option>
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
                                                                <input type="checkbox" name="VPN" class="checkbox style-0" value="1" checked='checked' >
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
                                        <input type="text" name="serverVPNName" value="{{$data->serverUsername}}" id="input_serverVPNName" class="form-control">
                                        <span id="error_serverVPNName"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">VPN Username <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverVPNUsername" value="{{$data->serverVPNUsername}}" id="input_serverVPNUsername" class="form-control">
                                        <span id="error_serverVPNUsername"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">VPN Password <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="serverVPNPassword" value="{{$data->serverVPNPassword}}" id="input_serverVPNPassword" class="form-control">
                                        <span id="error_serverVPNPassword"></span>
                                    </div>
                                </div>

                            </fieldset>

	                        <div class="form-actions">
	                            <div class="row">
	                                <div class="col-md-12 margin-right-5">
	                                    <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>

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
