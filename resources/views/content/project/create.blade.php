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
						<form action="{{route('project.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						@csrf
						<fieldset>
							
							<div class="form-group">
								<label class="col-md-2 control-label">Customer Name<sup>*</sup></label>
								<div class="col-md-5">
									<select name="idxCustomer" id="input_idxCustomer" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
										<option value="" selected="selected">-- Choose Customer Name --</option>
										@foreach ($customers as $key => $customer)
											<option value="{{ $customer->idx }}">{{ $customer->clientName }}</option>
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
								<label class="col-md-2 control-label">CLI <sup>*</sup></label>
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
							
							<!-- 							
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
							-->
							
							<div class="row form-group smart-form">
								<label class="col col-2 control-label">Free Trial Interval <sup>*</sup></label>
								<section class="col col-2">
									<label class="input"> 
									<input class="form-control" id="ftDateStart" type="text" name="ftDateStart" > 
									</label>
									<span id="error_ftDateStart"></span>
								</section>
								<section class="col col-2">
									<div class="input-group">
										<span class="input-group-addon">s/d</span>
										<input class="form-control" id="ftDateEnd" type="text" name="ftDateEnd" >
									</div>
									<span id="error_ftDateEnd"></span>
								</section>
							</div>
							
							<div class="row form-group smart-form">
								<label class="col col-2 control-label">On Subscribe Trial Interval <sup>*</sup></label>
								<section class="col col-2">
									<label class="input"> 
									<input class="form-control" id="ptDateStart" type="ftDateStart" name="ptDateStart" >
									</label>
									<span id="error_ptDateStart"></span>
								</section>
								<section class="col col-2">
									<div class="input-group">
										<span class="input-group-addon">s/d</span>
										<input class="form-control" id="ptDateEnd" type="text" name="ptDateEnd" >
									</div>
									<span id="error_ptDateEnd"></span>
								</section>												
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Start Joined/Subscribe <sup>*</sup></label>
								<div class="col-md-5">
									<input type="text" name="startClient" placeholder="Select a date" id="input_startClient" class="form-control datepicker" data-dateformat="dd/mm/yy">
									<span id="error_startClient"></span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2" for="prepend">&nbsp;</label>
								<div class="col-md-9">
									<div class="row">
										<div class="col-sm-3">
												<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label>
															<input type="checkbox" name="isTier" id="checkbox_isTier" class="checkbox style-0" >
															<span>&nbsp;&nbsp;Tiering</span>
														</label>
													</span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2" for="prepend">Start Tiering</label>
								<div class="col-md-8">
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group">
												<input type="text" id="startTiering" name="startTiering" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd/mm/yy" disabled="disabled" >
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Customer Group <sup>*</sup></label>
								<div class="col-md-5">
									<select name="idxCoreCustomerGroup" id="input_idxCoreCustomerGroup" class="select2 select2-offscreen" placeholder="-- Choose Business Entity --" tabindex="-1" title="" disabled="disabled" >
										<option value="" selected="selected"></option>
									</select>
									<span id="error_idxCoreCustomerGroup"></span>
								</div>
							</div>
							
							<header><b>&nbsp; &nbsp; Price</b></header>
							<div class="alert alert-warning fade in">
								<i class="fa-fw fa fa-warning"></i>
								<strong>Warning</strong> Fill All Price.
							</div>
                            <div class="row form-group smart-form">
                                <label class="col-md-2 control-label">Set New Price Mobile&SLJJ/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-mobile">&nbsp;IDR</i>
                                        <input type="text" name="newMobile" id="input_newMobile" class="form-control">
                                        <span id="error_newMobile"></span>
                                    </label>
                                </section>
                            </div>
                            <div class="row form-group smart-form">
                                <label class="col-md-2 control-label">Set New Mobile&SLJJ BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newMobileBC" id="input_newMobileBC" class="form-control">
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
                                        <input type="text" name="newPSTN" id="input_newPSTN" class="form-control">
                                        <span id="error_newPSTN"></span>
                                    </label>
                                </section>
                            </div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New PSTN BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newPSTNBC" id="input_newPSTNBC" class="form-control">
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
                                        <input type="text" name="newPremium" id="input_newPremium" class="form-control">
                                        <span id="error_newPremium"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-2 control-label">Set New Premium BC <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <span class="icon-prepend change-prepend">&nbsp;BC</span>
                                        <input type="text" name="newPremiumBC" id="input_newPremiumBC" class="form-control">
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
                                        <input type="text" name="newMinComm" id="input_newMinComm" class="form-control">
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
	
	var pagefunction = function() {
		 // Date Range Picker
		$("#ptDateStart").datepicker({ 
			defaultDate: "now",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ptDateEnd").datepicker("option", "minDate", selectedDate);
			}
		});
		$("#ptDateEnd").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ptDateStart").datepicker("option", "maxDate", selectedDate);
			}
		});


		 // Date Range Picker
		$("#ftDateStart").datepicker({
			defaultDate: "now",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateEnd").datepicker("option", "minDate", selectedDate);
			}
		});

		$("#ftDateEnd").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 2,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function (selectedDate) {
				$("#ftDateStart").datepicker("option", "maxDate", selectedDate);
			}
		});

	};

	$( document ).ready(function() {
		// setup page
		pageSetUp();
		pagefunction();
		my_form.init();
		$.sound_path = "{{url('/sound')}}/";


		// $('#checkbox_isTier').change(function() {
		// 	if ($(this).is(':checked')) {
		// 		$('#startTiering').prop('disabled', false); // Mengaktifkan elemen
		// 		$('#input_idxCoreCustomerGroup').prop('disabled', false); // Mengaktifkan elemen
		// 	} else {
		// 		$('#startTiering').prop('disabled', true);  // Menonaktifkan elemen
		// 		$('#input_idxCoreCustomerGroup').prop('disabled', true);  // Menonaktifkan elemen
		// 	}
		// });
		// $('#checkbox_approached').change(function() {
		// 	if ($(this).is(':checked')) {
		// 		$('#input_approached').prop('disabled', false); // Mengaktifkan elemen
		// 	} else {
		// 		$('#input_approached').prop('disabled', true);  // Menonaktifkan elemen
		// 	}
		// });
		// $('#checkbox_proposed').change(function() {
		// 	if ($(this).is(':checked')) {
		// 		$('#input_proposedDate').prop('disabled', false); // Mengaktifkan elemen
		// 	} else {
		// 		$('#input_proposedDate').prop('disabled', true);  // Menonaktifkan elemen
		// 	}
		// });
	});
	
</script>
