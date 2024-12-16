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
						<form action="{{route('customer.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							@csrf
							<div class="form-group">
								<div class="col-md-10">
									<div class="row">
										<div class="col-sm-3">
											<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label class="first" >
															<input type="checkbox" name="leads" class="checkbox style-0" value="1" >
															<span>&nbsp;&nbsp;Leads</span>
														</label>
													</span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<header><b>&nbsp; &nbsp; Business Info</b></header>
							<br />
							<fieldset title="Business Info" >

								<div class="form-group">
									<label class="col-md-3 control-label">Business Entity <sup>*</sup></label>
									<div class="col-md-5">
										<select name="titleCompany" id="input_titleCompany" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="" selected="selected">-- Choose Business Entity --</option>
											<option value="CV">CV - Commanditaire Vennootschap</option>
											<option value="PT">PT - Perseroan Terbatas</option>
										</select>
										<span id="error_titleCompany" ></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Corporate Name/Title <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="clientName" value="" id="input_clientName" class="form-control">
										<span id="error_clientName"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Contact Person/PIC <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="contact" value="" id="input_contact" class="form-control">
										<span id="error_contact"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Telephone Number <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="telephone1" value="" id="input_telephone1" class="form-control">
										<span id="error_telephone1"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Fax Line <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="fax" value="" id="input_fax" class="form-control">
										<span id="error_fax"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Email Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="email1" value="" id="input_email1" class="form-control">
										<span id="error_email1"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Address Information <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="address1" value="" id="input_address1" class="form-control">
										<span id="error_address1"></span>
									</div>
								</div>

							</fieldset>

							<header><b>&nbsp; &nbsp; Tax & Sales Info</b></header>
							<br />
							<fieldset title="Tax & Sales Info" >
								<div class="form-group">
									<label class="col-md-3 control-label">TAX ID Number <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="taxID" value="" id="input_taxID" class="form-control">
										<span id="error_taxID"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Tax Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="taxAddress" value="" id="input_taxAddress" class="form-control">
										<span id="error_taxAddress"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type Primary Manage Name <sup>*</sup></label>
									<div class="col-md-5">
										<select name="marketing1" id="input_marketing1" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="" selected="selected">-- Choose Manage --</option>
											@foreach ($UserResults as $key => $UserResult)
                                            <option value="{{ $UserResult->id }}">{{ $UserResult['employee']->name }}</option>
                                        	@endforeach
										</select>
										<span id="error_marketing1"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type Secondary Manage Name <sup>*</sup></label>
									<div class="col-md-5">
										<select name="marketing2" id="input_marketing2" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="" selected="selected">-- Choose Business Entity --</option>
											@foreach ($UserResults as $key => $UserResult)
                                            <option value="{{ $UserResult->id }}">{{ $UserResult['employee']->name }}</option>
                                        	@endforeach
										</select>
										<span id="error_marketing2"></span>
									</div>
								</div>
							</fieldset>

							<header><b>&nbsp; &nbsp; Project Info</b></header>
							<br />
							<fieldset title="Project Info" >
								<div class="form-group">
									<label class="col-md-3 control-label">Alias Corporate Name/Title <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="clientName2" value="" id="input_clientName2" class="form-control">
										<span id="error_clientName2"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Contact Person/PIC <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="contact2" value="" id="input_contact2" class="form-control">
										<span id="error_contact2"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Email Address, more than 1 use ; <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="email2" value="" id="input_email2" class="form-control">
										<span id="error_email2"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Secondary Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="address2" value="" id="input_address2" class="form-control">
										<span id="error_address2"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Fill Other Information, Such as other Contact Person/PIC name, address ,numbers, etc. <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="otherDetails" value="" id="input_otherDetails" class="form-control">
										<span id="error_otherDetails"></span>
									</div>
								</div>
							</fieldset>

							<header><b>&nbsp; &nbsp; Date</b></header>
							<br />
							<fieldset title="Date" >

								<div class="form-group">
									<label class="col-md-3 control-label">Status <sup>*</sup></label>
									<div class="col-md-5">
										<select name="statusData" id="input_statusData" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="" selected="selected">-- Choose Status --</option>
											<option value="Active">Active</option>
											<option value="Non Active">Non Active</option>
											<option value="Delete">Delete</option>
										</select>
										<span id="error_statusData"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3" for="prepend">Date Approach</label>
									<div class="col-md-8">
										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<span class="input-group-addon">
														<span class="checkbox">
															<label>
																<input id="checkbox_approached" type="checkbox" name="approached" class="checkbox style-0" value="1" >
																<span></span>
															</label>
														</span>
													</span>
													<input type="text" id="input_approached" name="approachedDate" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd/mm/yy" disabled="disabled" >
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3" for="prepend">Date Proposed</label>
									<div class="col-md-8">
										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<span class="input-group-addon">
														<span class="checkbox">
															<label>
																<input id="checkbox_proposed" type="checkbox" name="proposed" class="checkbox style-0" value="1" >
																<span></span>
															</label>
														</span>
													</span>
													<input type="text" id="input_proposedDate" name="proposedDate" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd/mm/yy" disabled="disabled" >
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Invoicing Priority <sup>*</sup></label>
									<div class="col-md-5">
										<select name="invoicePrior" id="input_invoicePrior" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="" selected="selected">-- Choose Invoicing Priority --</option>
											<option value="regular">Regular</option>
											<option value="priority">Priority</option>
											<option value="medium">Medium</option>
										</select>
										<span id="error_invoicePrior"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3" for="prepend">&nbsp;</label>
									<div class="col-md-9">
										<div class="row">
											<div class="col-sm-3">
													<div class="input-group">
													<span class="input-group-addon">
														<span class="checkbox">
															<label>
																<input type="checkbox" name="isCompare" class="checkbox style-0" >
																<span>&nbsp;&nbsp;CDR Comparation</span>
															</label>
														</span>
													</span>
												</div>
											</div>
											<div class="col-sm-3">
													<div class="input-group">
													<span class="input-group-addon">
														<span class="checkbox">
															<label>
																<input type="checkbox" name="isCustom" class="checkbox style-0" >
																<span>&nbsp;&nbsp;Custom CDR</span>
															</label>
														</span>
													</span>
												</div>
											</div>
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
<!-- 
								<div class="form-group">
									<label class="control-label col-md-3" for="prepend">Start Tiering</label>
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
									<label class="col-md-3 control-label">Customer Group <sup>*</sup></label>
									<div class="col-md-5">
										<select name="idxCoreCustomerGroup" id="input_idxCoreCustomerGroup" class="select2 select2-offscreen" placeholder="-- Choose Business Entity --" tabindex="-1" title="" disabled="disabled" >
											<option value="" selected="selected"></option>
											@foreach ($customerGroups as $keyp => $customerGroup)
                                            <option value="{{ $customerGroup->idxCore }}">{{ $customerGroup->name }}</option>
                                        	@endforeach
										</select>
										<span id="error_idxCoreCustomerGroup"></span>
									</div>
								</div> -->

								<div class="form-group">
									<label class="col-md-3 control-label">Address <sup>*</sup></label>
									<div class="col-md-9">
										<textarea name="customNote" cols="40" rows="10" class="form-control"></textarea>
										<span id="error_address"></span>
									</div>
								</div>
							</fieldset>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-12 margin-right-2">
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
