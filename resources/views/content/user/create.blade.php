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
                        <form action="{{route('user.store')}}"  id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            				@csrf
	                        <fieldset>
	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Name <sup>*</sup></label>
	                                <div class="col-md-3">
	                                    <input type="text" name="nama_user" value="" class="form-control">
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Username <sup>*</sup></label>
	                                <div class="col-md-3">
	                                    <input type="text" name="username_user" value="" class="form-control">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Employee <sup>*</sup></label>
	                                <div class="col-md-7"  id="participan_name">
	                                    <input type="text" name="employee_name" value="" class="form-control typeahead" style="width:100%;">
	                                    <div class="note">
	                                        <strong>Example : </strong> 
	                                        <span class="label label-default">Husnudzon Barkah</span>
	                                    </div>
	                                    <span id="error_employee_name"></span>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Employee Information</label>
	                                <div class="col-md-9">
	                                    <div id="info-in-participant" class="alert alert-info">
											<input type="hidden" name="employee_name_temp" value="">
											<input type="hidden" name="employee_name_temp" value="">
											<input type="hidden" name="id_pegawai" value="">
	                                        <div class="row">
	                                            <div class="col-sm-2"><strong>Unit</strong></div>
	                                            <div class="col-sm-8">: <span id="info-unit"></span></div>
	                                        </div>
	                                        <div class="row">
	                                            <div class="col-sm-2"><strong>Work Locatin</strong></div>
	                                            <div class="col-sm-8">: <span id="info-dept"></span></div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Role <sup>*</sup></label>
	                                <div class="col-md-5">
	                                    <select name="id_role" class="select2">
											<option value="" selected="selected">--Pilih--</option>
											<option value="0001">ROLE_SUPER_ADMIN</option>
											<option value="0002">ROLE_ADMIN</option>
											<option value="0003">ROLE_PEGAWAI</option>
											<option value="0004">ROLE_OWNER</option>
											<option value="0005">ROLE_GENERAL_MANAGER</option>
											<option value="0006">ROLE_MANAGER</option>
											<option value="0008">ROLE_ASSISTANCE_MANAGER</option>
											<option value="0009">ROLE_KEPALA_BAGIAN</option>
											<option value="0011">ROLE_STAFF</option>
											<option value="0012">ROLE_KOR_WIL</option>
											<option value="0013">ROLE_PIMPINAN_LOKASI</option>
											<option value="0014">ROLE_SUPERVISOR</option>
										</select>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label class="col-md-2 control-label">Status <sup>*</sup></label>
	                                <div class="col-md-2">
	                                    <select name="active_user" class="form-control ">
											<option value="" selected="selected">--All--</option>
											<option value="1">Aktif</option>
											<option value="2">Tidak Aktif</option>
										</select>
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


</div>


<script type="text/javascript">
    pageSetUp();
    // my_form.init();
	$.sound_path = "{{url('/sound')}}/";
    var url_base = "{{url('/')}}";

    
    var _set_participant_info = function (datum) {
        var box = $('#info-in-participant');
		alert(nama_lokasi_kerja);
        $('#info-dept', box).html(datum.nama_lokasi_kerja !== '' ? datum.nama_lokasi_kerja : '-');
        $('#employee_name_temp', box).val(datum.value);
        $('#id_pegawai', box).val(datum.id_pegawai);
    };


	$( document ).ready(function() {
		/*
		* AUTO COMPLETE
		*/
		var loadTypeaheadjs = function () {
			var bestPictures = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				remote: url_base + 'employee/%QUERY'
			});

			bestPictures.initialize();

			$('#participan_name .typeahead').typeahead(null, {
				name: 'best-pictures',
				displayKey: 'value',
				source: bestPictures.ttAdapter()
			}).bind("typeahead:selected", function (obj, datum, name) {
				_set_participant_info(datum);
			});
		};
    	loadTypeaheadjs();
	});
	
    loadScript("{{url('/js/plugin/typeahead/typeahead.bundle.js')}}");
</script>


<script type="text/javascript">
    // $('input[name=username_user]').alphanumeric({allow: "_"});
</script>

