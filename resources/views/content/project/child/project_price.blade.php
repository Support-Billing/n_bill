<html>
    <head>
        <script id="tinyhippos-injected">
            if (window.top.ripple) {
                window.top.ripple("bootstrap").inject(window, document);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    </head>
    <body>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-times"></i>
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span><?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <form action="{{route('project.store_price', $projectID)}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>
							<div class="alert alert-warning fade in">
								<i class="fa-fw fa fa-warning"></i>
								<strong>Warning</strong> Fill All Price.
							</div>
                            <div class="row form-group smart-form">
                                <label class="col-md-4 control-label">Set New Price Mobile&SLJJ/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-mobile">&nbsp;IDR</i>
                                        <input type="text" name="newMobile" id="input_newMobile" class="form-control">
                                        <span id="error_newMobile"></span>
                                    </label>
                                </section>
                            </div>
                            <div class="row form-group smart-form">
                                <label class="col-md-4 control-label">Set New Mobile&SLJJ BC <sup>*</sup></label>
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
                                <label class="col-md-4 control-label">Set New Price PSTN/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-phone">&nbsp;IDR</i>
                                        <input type="text" name="newPSTN" id="input_newPSTN" class="form-control">
                                        <span id="error_newPSTN"></span>
                                    </label>
                                </section>
                            </div>
							<div class="row form-group smart-form">
								<label class="col-md-4 control-label">Set New PSTN BC <sup>*</sup></label>
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
								<label class="col-md-4 control-label">Set New Price Premium/Minutes <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-money">&nbsp;IDR</i>
                                        <input type="text" name="newPremium" id="input_newPremium" class="form-control">
                                        <span id="error_newPremium"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-4 control-label">Set New Premium BC <sup>*</sup></label>
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
								<label class="col-md-4 control-label">Set New Min Comm <sup>*</sup></label>
                                <section class="col col-5">
                                    <label class="input"> 
                                        <i class="icon-prepend change-prepend fa fa-tasks">&nbsp;IDR</i>
                                        <input type="text" name="newMinComm" id="input_newMinComm" class="form-control">
                                        <span id="error_newMinComm"></span>
                                    </label>
                                </section>
							</div>
							<div class="row form-group smart-form">
								<label class="col-md-4 control-label">&nbsp;<sup>*</sup></label>
								<div class="col col-5">
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
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                // setup page
                pageSetUp();
                my_form.init();
                $.sound_path = "{{url('/sound')}}/";
            });
        </script>
        
    </body>
</html>