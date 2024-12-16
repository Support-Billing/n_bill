
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
                        
                        <form action="{{route('project.update', [$data->idxCore])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
						<fieldset>
							
							<div class="form-group">
								<label class="col-md-2 control-label">Client Name<sup>*</sup></label>
								<div class="col-md-5">
									<select name="idxCustomer" id="input_idxCustomer" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" selected="selected">-- Choose Client Name --</option>
										@foreach ($customers as $key => $customer)
											<option value="{{ $customer->idxCustomer }}">{{ $customer->clientName }}</option>
										@endforeach
									</select>
									<span id="error_idxCustomer"></span>
								</div>
							</div>

							<header><b>&nbsp; &nbsp; Project Info</b></header>
							<br />
							<div class="form-group">
								<div class="col-md-10">
									<div class="row">
										<div class="col-sm-2">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" class="checkbox style-0" value="1" name="isSIPTRUNK" >
															<span>&nbsp;&nbsp;SIP TRUNK</span>
														</label>
													</span>
												</span>
											</div>
										</div>
										<div class="col-sm-2">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" class="checkbox style-0" value="1" name="isSIPREG" >
															<span>&nbsp;&nbsp;SIP REG</span>
														</label>
													</span>
												</span>
											</div>
										</div>
										<div class="col-sm-2">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" class="checkbox style-0" value="1" name="isFWT" >
															<span>&nbsp;&nbsp;FWT</span>
														</label>
													</span>
												</span>
											</div>
										</div>
										<div class="col-sm-2">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" class="checkbox style-0" value="1" name="isApps" >
															<span>&nbsp;&nbsp;APP</span>
														</label>
													</span>
												</span>
											</div>
										</div>
										<div class="col-sm-2">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" class="checkbox style-0" value="1" name="isSLI" >
															<span>&nbsp;&nbsp;SLI</span>
														</label>
													</span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Project Name <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="projectName" value="" id="input_projectName" class="form-control">
									<span id="error_projectName"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">&nbsp; <sup>*</sup></label>
								<div class="col-md-5">
									<select name="isCLI" id="input_isCLI" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" selected="selected">-- Choose CLI --</option>
										<option value="0" >Non CLI</option>
										<option value="1">CLI</option>
									</select>
									<span id="error_isCLI"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Contact Person/PIC <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="contact" value="" id="input_contact" class="form-control">
									<span id="error_contact"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Telephone Number <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="telephone" value="" id="input_telephone" class="form-control">
									<span id="error_telephone"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Email Address <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="email" value="" id="input_email" class="form-control">
									<span id="error_email"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Address Detail <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="address" value="" id="input_address" class="form-control">
									<span id="error_address"></span>
								</div>
							</div>
							
							<header><b>&nbsp; &nbsp; Project Details</b></header>
							<br />
							<div class="form-group">
								<label class="col-md-2 control-label">Address Detail <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="detailProject1" value="" id="input_detailProject1" class="form-control">
									<span id="error_detailProject1"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Other Detail <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="detailProject2" value="" id="input_detailProject2" class="form-control">
									<span id="error_detailProject2"></span>
								</div>
							</div>
							<header><b>&nbsp; &nbsp; Status</b></header>
							<br />
							<div class="form-group">
								<label class="col-md-2 control-label">Status Project <sup>*</sup></label>
								<div class="col-md-5">
									<select name="statusProject" id="input_statusProject" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" selected="selected">-- Choose Project  --</option>
										<option value="0">Waiting</option>
										<option value="1">Free Trial</option>
										<option value="2">Trial on Subscribe </option>
										<option value="3">Subscribe</option>
										<option value="4">Closed</option>
									</select>
									<span id="error_statusProject"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Status Data <sup>*</sup></label>
								<div class="col-md-5">
									<select name="statusData" id="input_statusData" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" selected="selected">-- Choose Status Data  --</option>
										<option value="1" >Status : Active</option>
										<option value="2" >Status : Closed</option>
									</select>
									<span id="error_statusData"></span>
								</div>
							</div>
							
							<div class="row form-group smart-form">
								<label class="col col-2 control-label">Free Trial Interval <sup>*</sup></label>
								<section class="col col-2">
									<label class="input"> 
									<input class="form-control" id="ftDateStart" type="text" placeholder="From" name="ftDateStart" >
									</label>
									<span id="error_name"></span>
								</section>
								<section class="col col-2">
									<div class="input-group">
										<span class="input-group-addon">s/d</span>
										<input class="form-control" id="ftDateEnd" type="text" placeholder="Select a date" name="ftDateEnd" >
									</div>
									<span id="error_name"></span>
								</section>
							</div>
							
							<div class="row form-group smart-form">
								<label class="col col-2 control-label">On Subscribe Trial Interval <sup>*</sup></label>
								<section class="col col-2">
									<label class="input"> 
									<input class="form-control" id="ptDateStart" type="ftDateStart" placeholder="From" name="ptDateStart" >
									</label>
									<span id="error_name"></span>
								</section>
								<section class="col col-2">
									<div class="input-group">
										<span class="input-group-addon">s/d</span>
										<input class="form-control" id="ptDateEnd" type="text" placeholder="Select a date" name="ptDateEnd" >
									</div>
									<span id="error_name"></span>
								</section>												
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Start Joined/Subscribe <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="startClient" placeholder="Select a date" id="input_startClient" class="form-control datepicker" data-dateformat="dd/mm/yy">
									<span id="error_startClient"></span>
								</div>
							</div>
							
							<header><b>&nbsp; &nbsp; Price</b></header>
							<br />
							<div class="alert alert-warning fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-warning"></i>
								<strong>Warning</strong> Fill All Price.
							</div>
                            <div class="row form-group smart-form">
                                <label class="col-md-2 control-label">Set New Price Mobile&SLJJ/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-mobile">&nbsp;IDR</i>
                                        <input type="text" name="newMobile" id="input_newMobile" placeholder="Name" class="form-control">
                                        <span id="error_newMobile"></span>
                                    </label>
                                </section>
                            </div>
                            <div class="row form-group smart-form">
                                <label class="col-md-2 control-label">Set New Mobile&SLJJ BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newMobileBC" placeholder="Name" id="input_newMobileBC" class="form-control">
                                        <span class="icon-append">&nbsp;$</span>
                                        <span id="error_newMobileBC"></span>
                                    </label>
                                </section>
                            </div>
                            <div class="row form-group smart-form">
                                <label class="col-md-2 control-label">Set New Price PSTN/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-phone">&nbsp;IDR</i>
                                        <input type="text" name="newPSTN" placeholder="Set New Price PSTN/Minutes" id="input_newPSTN" class="form-control">
                                        <span id="error_newPSTN"></span>
                                    </label>
                                </section>
                            </div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New PSTN BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newPSTNBC" placeholder="Name" id="input_newPSTNBC" class="form-control">
                                        <span class="icon-append">&nbsp;$</span>
                                        <span id="error_newPSTNBC"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New Price Premium/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-money">&nbsp;IDR</i>
                                        <input type="text" name="newPremium" placeholder="Name" id="input_newPremium" class="form-control">
                                        <span id="error_newPremium"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New Premium BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newPremiumBC" placeholder="Name" id="input_newPremiumBC" class="form-control">
                                        <span class="icon-append">&nbsp;$</span>
                                        <span id="error_newPremiumBC"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New Min Comm <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-tasks">&nbsp;IDR</i>
                                        <input type="text" name="newMinComm" placeholder="Name" id="input_newMinComm" class="form-control">
                                        <span id="error_newMinComm"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">&nbsp;<sup>*</sup></label>
								<div class="col-md-10">
									<div class="row">
										<div class="col-sm-2">
											<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input name="isPriority" type="checkbox" class="checkbox style-0" value="1">
															<span>&nbsp;&nbsp;Priority</span>
														</label>
													</span>
												</span>
											</div>
										</div>
									</div>
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
