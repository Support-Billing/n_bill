
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
                    <h2>Show Server</h2>
                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        
                        <form action="javascript:void(0);"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
                        <fieldset>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Server Name </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverName" value="{{$data->serverName}}" disabled="disabled" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">IP Address </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverIP" disabled="disabled" value="{{$data->serverIP}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Username </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverUsername" disabled="disabled" value="{{$data->serverUsername}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverPassword" disabled="disabled" value="{{$data->serverPassword}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Fingerprint </label>
                                <div class="col-md-5">
                                    <input type="text" name="Fingerprint" disabled="disabled" value="{{$data->Fingerprint}}" class="form-control">
                                    <span id="error_Fingerprint"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Port </label>
                                <div class="col-md-2">
                                    <input type="text" name="serverPort" disabled="disabled" value="{{$data->serverPort}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Type </label>
                                <div class="col-md-5">
                                    <select name="serverType" disabled="disabled" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                        <option value="" >-- Choose Type --</option>
                                        @if($data->serverType ==1)
                                            <option value="elastix">ELASTIX</option>
                                            <option itemref="selected" value="mera">MERA</option>
                                            <option value="vos">VOS</option>
                                        @elseif($data->serverType ==2)
                                            <option value="mera">MERA</option>
                                            <option selected="selected" value="elastix">ELASTIX</option>
                                            <option value="vos">VOS</option>
                                        @else  
                                            <option value="mera">MERA</option>
                                            <option value="elastix">ELASTIX</option>
                                            <option selected="selected" value="vos">VOS</option>
                                        @endif
                                    </select>
                                    <span id="error_name"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Protocol </label>
                                <div class="col-md-5">
                                    <select name="serverProtocol" disabled="disabled" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                        <option value="sftp" selected="selected" >SSH File Transfer Protocol (SFTP)</option>
                                    </select>
                                    <span id="error_name"></span>
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
                                                            <input type="checkbox" name="VPN" disabled="disabled" class="checkbox style-0" value="1" checked='checked'>
                                                            <span style="color:#fff" >&nbsp;&nbsp;Use VPN</span>
                                                        </label>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Name </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNName" disabled="disabled" value="{{$data->serverVPNName}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Username </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNUsername" disabled="disabled" value="{{$data->serverVPNUsername}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">VPN Password </label>
                                <div class="col-md-5">
                                    <input type="text" name="serverVPNPassword" disabled="disabled" value="{{$data->serverVPNPassword}}" class="form-control">
                                    <span id="error_name"></span>
                                </div>
                            </div>

                        </fieldset>

	                        <div class="form-actions">
	                            <div class="row">
	                                <div class="col-md-12 margin-right-5">
	                                    <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
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
