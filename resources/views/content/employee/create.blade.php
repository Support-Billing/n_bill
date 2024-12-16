

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
                        <form action="{{route('employee.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            @csrf
                            <fieldset>

                                <!-- 
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Code <sup id="req_id_lokasi_kerja" style="display: none;">*</sup></label>
                                    <div class="col-md-10">
                                        <div class="col-md-2 no-padding margin-right-5">
                                            <input type="text" name="no_lokasi_kerja" value="" id="input_xxx" id="id_lokasi_kerja" class="form-control" readonly="readonly">
                                        </div>
                                        <div class="smart-form col-md-3">
                                            <section>
                                                <label class="toggle">
                                                    <input type="checkbox" name="auto_generate" value="t" checked="checked" id="auto_generate" data-target="id_lokasi_kerja" onchange="my_global.auto_generate(this.id)">
                                                    <i data-swchon-text="ON" data-swchoff-text="OFF"></i>
                                                    Auto Generate
                                                </label>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                -->

                                <div class="form-group">
                                    <label class="col-md-2 control-label">NIK <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="nik" value="" id="input_nik" class="form-control">
                                        <span id="error_nik"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Employee <sup>*</sup></label>
                                    <div class="col-md-7"  id="participan_name">
                                        <input type="text" name="name" id="input_name" placeholder="Name" style="width:100%;" class="form-control typeahead">
                                        <div class="note">
                                            <strong>Autocomplete, Example : </strong> 
                                            <span class="label label-default">Mark Zuckerberg</span>
                                        </div>
                                        <span id="error_employee_name"></span>
                                    </div>
                                </div>

                                <div class="row form-group smart-form">
                                    <label class="col-md-2 control-label">Employee Name <sup>*</sup></label>
                                    <section class="col col-5">
                                        <label class="input"> 
                                            <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="name" id="input_name" placeholder="Name" class="form-control">
                                            <span id="error_name"></span>
                                        </label>
                                    </section>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Work Location Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <select name="id_worklocation" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                            <option value="" selected="selected">--Choose Work Location--</option>
                                            @foreach ($worklocations as $key => $worklocation)
                                                <option value="{{ $worklocation->id }}">{{ $worklocation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Phone <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="phone" value="" id="input_phone" class="form-control">
                                        <span id="error_phone"></span>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="email" value="" id="input_email" class="form-control">
                                        <span id="error_email"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">City <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <select name="city" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                            <option value="" selected="selected">--Choose City--</option>
                                            <option value="Bandung">Bandung</option>
                                            <option value="Jakarta">Jakarta</option>
                                        </select>
                                        <span id="error_city"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Address <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <textarea name="address" cols="40" rows="10" id="input_address" class="form-control"></textarea>
                                        <span id="error_address"></span>
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
    
    var _set_participant_info = function (datum) {
        var box = $('#info-in-participant');
        $('#info-unit', box).html(datum.nama_unit_kerja !== '' ? datum.nama_unit_kerja : '-');
        $('#info-dept', box).html(datum.nama_lokasi_kerja !== '' ? datum.no_lokasi_kerja + '-' + datum.nama_lokasi_kerja : '-');

        $('#employee_name_temp', box).val(datum.value);
        $('#id_pegawai', box).val(datum.id_pegawai);
    };

    /*
     * AUTO COMPLETE
     */
    var loadTypeaheadjs = function () {
        var bestPictures = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: 'http://10.10.11.50/#employee/create/?query=%QUERY'
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


    // loadTypeaheadjs();
    loadScript(my_global.config.base_url.assets + "/js/plugin/typeahead/typeahead.bundle.js", loadTypeaheadjs);
    
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
