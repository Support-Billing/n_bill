
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
                    <h2>Edit {{$page_title}}</h2>
                </header>

                <!-- widget div-->
                <div>
					<!-- widget content -->
					<div class="widget-body">
						<form action="{{route('customer.update', [$data->idxCore])}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" value="PUT" name="_method">
							<div class="form-group">
								<div class="col-md-10">
									<div class="row">
										<div class="col-sm-3">
											<div class="input-group">
												<span class="input-group-addon">
													<span class="checkbox">
														<label class="first" >
															@if($data->leads)
																<input type="checkbox" checked="checked" name="leads" class="checkbox style-0" value="1" >
															@else
																<input type="checkbox" name="leads" class="checkbox style-0" value="1" >
															@endif
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
									<label class="col-md-3 control-label">Business Entity<sup>*</sup></label>
									<div class="col-md-5">
										<select name="titleCompany" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<?php $titleCompany = array('CV' => 'CV - Commanditaire Vennootschap','PT' => 'PT - Perseroan Terbatas'); ?>
											@foreach ($titleCompany as $key => $titleResult)
												@if($data->title == $key)
													<option value="{{ $key }}" selected="selected" >{{ $titleResult}}</option>
												@else
													<option value="{{ $key }}" >{{ $titleResult}}</option>
												@endif
                                        	@endforeach
										</select>
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Corporate Name/Title <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="clientName" value="{{$data->clientName}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Contact Person/PIC <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="contact" value="{{$data->contactName}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Telephone Number <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="telephone1" value="{{$data->telephone1}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Fax Line <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="fax" value="" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Email Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="email1" value="{{$data->email1}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Address Information <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="address1" value="{{ strip_tags($data->otherDetails) }}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>

							</fieldset>

							<header><b>&nbsp; &nbsp; Tax & Sales Info</b></header>
							<br />
							<fieldset title="Tax & Sales Info" >
								<div class="form-group">
									<label class="col-md-3 control-label">TAX ID Number <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="taxID" value="{{$data->taxID}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Tax Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="taxAddress" value="{{$data->clientnataxAddressme}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type Primary Manage Name <sup>*</sup></label>
									<div class="col-md-5">
										<select name="marketing1" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											@foreach ($UserResults as $key => $UserResult)
												@if($UserResult->id == $data->marketingID)
													<option value="{{ $UserResult->id }}" selected="selected" >{{ $UserResult['employee']->name }}</option>
												@else
													<option value="{{ $UserResult->id }}">{{ $UserResult['employee']->name }}</option>
												@endif
                                        	@endforeach
										</select>
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type Secondary Manage Name <sup>*</sup></label>
									<div class="col-md-5">
										<select name="marketing2" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											@foreach ($UserResults as $key => $UserResult)
												@if($UserResult->id == $data->marketingID2)
													<option value="{{ $UserResult->id }}" selected="selected" >{{ $UserResult['employee']->name }}</option>
												@else
													<option value="{{ $UserResult->id }}">{{ $UserResult['employee']->name }}</option>
												@endif
                                        	@endforeach
										</select>
										<span id="error_name"></span>
									</div>
								</div>
							</fieldset>

							<header><b>&nbsp; &nbsp; Project Info</b></header>
							<br />
							<fieldset title="Project Info" >
								<div class="form-group">
									<label class="col-md-3 control-label">Alias Corporate Name/Title <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="clientName2" value="{{$data->clientName2}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Contact Person/PIC <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="contact2" value="{{$data->contactName2}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Email Address, more than 1 use ; <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="email2" value="{{$data->email2}}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Secondary Address <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="address2" value="{{ strip_tags($data->address2) }}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Fill Other Information, Such as other Contact Person/PIC name, address ,numbers, etc. <sup>*</sup></label>
									<div class="col-md-5">
										<input type="text" name="otherDetails" value="{{ strip_tags($data->otherDetails) }}" id="input_xxx" class="form-control">
										<span id="error_name"></span>
									</div>
								</div>
							</fieldset>

							<header><b>&nbsp; &nbsp; Date</b></header>
							<br />
							<fieldset title="Date" >

								<div class="form-group">
									<label class="col-md-3 control-label">Status<sup>*</sup></label>
									<div class="col-md-5">
										<select name="statusData" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<?php $Status = array( 0 => 'Non Active', 1 => 'Active', 3 => 'Delete' ); ?>
											@foreach ($Status as $keyStatus => $StatusResult)
												@if($data->statusData == $keyStatus)
													<option value="{{ $keyStatus }}" selected="selected" >{{ $StatusResult}}</option>
												@else
													<option value="{{ $keyStatus }}" >{{ $StatusResult}}</option>
												@endif
                                        	@endforeach
										</select>
										<span id="error_name"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3" for="prepend">Date Approach </label>
									<div class="col-md-8">
										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<span class="input-group-addon">
														<span class="checkbox">
															<label>
																<input type="checkbox" name="approached" class="checkbox style-0" value="1" >
																<span></span>
															</label>
														</span>
													</span>
													<input type="text" name="approachedDate" value="" class="form-control datepicker" data-dateformat="dd/mm/yy">
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
																<input type="checkbox" name="proposed" class="checkbox style-0" value="1" >
																<span></span>
															</label>
														</span>
													</span>
													<input type="text" name="proposedDate" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd/mm/yy">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Invoicing Priority <sup>*</sup></label>
									<div class="col-md-5">
										<select name="invoicePrior" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
											<option value="{{$data->clientname}}" selected="selected">-- Choose Invoicing Priority --</option>
											<option value="regular">Regular</option>
											<option value="priority">Priority</option>
											<option value="medium">Medium</option>
										</select>
										<span id="error_name"></span>
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
																<input type="checkbox" id="checkbox_isTier" name="isTier" class="checkbox style-0" >
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
								</div>

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

        var pageFunction = function () {
            $('input[name=phone]').numeric();
            my_global.auto_generate('auto_generate');
        };
        
        pageFunction();

		
		$('#checkbox_isTier').change(function() {
			if ($(this).is(':checked')) {
				$('#startTiering').prop('disabled', false); // Mengaktifkan elemen
				$('#input_idxCoreCustomerGroup').prop('disabled', false); // Mengaktifkan elemen
			} else {
				$('#startTiering').prop('disabled', true);  // Menonaktifkan elemen
				$('#input_idxCoreCustomerGroup').prop('disabled', true);  // Menonaktifkan elemen
			}
		});
		$('#checkbox_approached').change(function() {
			if ($(this).is(':checked')) {
				$('#input_approached').prop('disabled', false); // Mengaktifkan elemen
			} else {
				$('#input_approached').prop('disabled', true);  // Menonaktifkan elemen
			}
		});
		$('#checkbox_proposed').change(function() {
			if ($(this).is(':checked')) {
				$('#input_proposedDate').prop('disabled', false); // Mengaktifkan elemen
			} else {
				$('#input_proposedDate').prop('disabled', true);  // Menonaktifkan elemen
			}
		});

    });
</script>
