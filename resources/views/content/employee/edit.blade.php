

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
                        <form action="{{route('worklocation.store')}}" id="finput" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                            <fieldset>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">NIK <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="nik" value="{{$data->nik}}"  id="input_xxx" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Employee Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" value="{{$data->name}}"  id="input_xxx" class="form-control">
                                    </div>
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
                                    <label class="col-md-2 control-label">Department Name <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <select name="id_lokasi_kerja" class="select2 select2-offscreen" placeholder="" tabindex="-1" title="">
                                            <option value="" selected="selected">--Choose Work Location--</option>
                                            <option value="LK0001">LK0001 - KANTOR PUSAT</option>
                                            <option value="LK0002">LK0002 - KC. Equity Equity Tower</option>
                                            <option value="LK0003">LK0003 - KC. Bogor Pajajaran</option>
                                            <option value="LK0008">LK0008 - KC. Bandung Naripan</option>
                                            <option value="LK0015">LK0015 - KC. Bandung Abdurachman Saleh</option>
                                            <option value="LK0022">LK0022 - KC. Sukabumi</option>
                                            <option value="LK0030">LK0030 - KC. Cirebon</option>
                                            <option value="LK0036">LK0036 - KC. Semarang Pemuda</option>
                                            <option value="LK0041">LK0041 - KC. Yogyakarta</option>
                                            <option value="LK0050">LK0050 - KC Solo Veteran</option>
                                            <option value="LK0056">LK0056 - KC. Surabaya Darmo Square</option>
                                            <option value="LK0059">LK0059 - KC. Denpasar</option>
                                            <option value="1506040000">1506040000 - KC. Tanjung Pinang</option>
                                            <option value="1506040001">1506040001 - KC. Batam</option>
                                            <option value="1506040002">1506040002 - KC. Pekanbaru Sudirman</option>
                                            <option value="1506040003">1506040003 - KC. Palembang</option>
                                            <option value="1506040004">1506040004 - KC Lampung</option>
                                            <option value="1506040005">1506040005 - KC. Pontianak A.YAni Megamall</option>
                                            <option value="1506040006">1506040006 - KC. Makassar</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Phone <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="phone" value="{{$data->phone}}"  id="input_xxx" class="form-control">
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email <sup>*</sup></label>
                                    <div class="col-md-3">
                                        <input type="text" name="email" value="{{$data->email}}"  id="input_xxx" class="form-control">
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
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Address <sup>*</sup></label>
                                    <div class="col-md-5">
                                        <textarea name="address" cols="40" rows="10" class="form-control">{{$data->address}}</textarea>
                                    </div>
                                </div> 

                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12 margin-right-2">
                                        
                                        <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-2" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>

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
