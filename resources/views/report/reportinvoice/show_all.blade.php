<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-white" 
                id="wid-id-0" 
                data-widget-sortable="false" 
                data-widget-deletebutton="false" 
                data-widget-editbutton="false" 
                data-widget-togglebutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2><?php echo $page_title; ?> </h2>
                    <div class="widget-toolbar hidden-phone">
                        <div class="smart-form">
                            <label class="toggle"><input type="checkbox" name="checkbox-toggle" value="#box_filter" checked="checked" id="demo-switch-to-pills" onclick="my_data_table.filter.toggle(this.id)">
                                <i data-swchon-text="Show" data-swchoff-text="Hide"></i>Filtering
                            </label>
                        </div>
                    </div>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <div id="box_filter" class="no-padding border-bottom-1">
                            <form action="" id="filter_table" class="smart-form">
                                @csrf
                            </form>
                        </div>
                        
                        @if($import_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="{{route('download_reportinvoicedetil', $urlData)}}" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>
                                    Download <?php echo $page_title; ?>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%" 
                            data-source="{{url('reportcdrloaddetil')}}"
                            data-filter="#filter_table" data-paginate="false" data-setting_footer_body="false" data-setting_head="false" >
                            <thead>
                                <tr>
                                    <th colspan='10' class='text-center' > {{$ProjectAlias}} </th>
                                </tr>
                                <tr>
                                    <th colspan='10' class='text-center' > {{ $dateEnd ? $dateStart . ' s/d ' . $dateEnd : $dateStart }} </th>
                                </tr>
                                <tr>
                                    <th data-hide="phone">No</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Tanggal</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Tujuan</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Real</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Tagih</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Tarif/Menit</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Biaya</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Tarif Telkom</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Biaya Telkom</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> penghematan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $TotalPrice = 0;
                                    $WaktuReal = 0;
                                    $Duration = 0;
                                    $BiayaTelkom = 0;
                                @endphp
                                @foreach ($results as $key => $val)
                                    <tr>
                                        <td data-hide="phone">{{$key+1}}</td>
                                        <td data-class="expand">{{$val->date}}</td>
                                        <td data-class="expand">{{$val->destNoPrefixName}}</td>
                                        <td data-class="expand" class="text-right" >{{$val->WaktuReal}}</td>
                                        <td data-class="expand" class="text-right" >{{$val->Duration}}</td>
                                        <td data-class="expand" class="text-right" >{{number_format($val->custPrice, 0, ',', '.')}}</td>
                                        <td data-class="expand" class="text-right" >{{number_format($val->TotalPrice, 0, ',', '.')}}</td>
                                        <td data-class="expand" class="text-right" >{{number_format($val->tarifTelkom, 0, ',', '.')}}</td>
                                        <td data-class="expand" class="text-right" >{{number_format($val->TotalTelkom, 0, ',', '.')}}</td>
                                        @php
                                            $biaya = round($val->TotalPrice, 2);
                                            $biayaTelkom = round($val->TotalTelkom, 2);
                                            if ($biayaTelkom != 0) {
                                                $penghematanPercentage = ($biayaTelkom - $biaya) / $biayaTelkom * 100;
                                            } else {
                                                $penghematanPercentage = 0; // Atau nilai default sesuai kebutuhan Anda
                                            }
                                            $penghematan = number_format($penghematanPercentage, 0);
                                        @endphp
                                        <td data-class="expand" class="text-right">{{$penghematan}}%</td>
                                    </tr>
                                    @php
                                        $WaktuReal += $val->WaktuReal;
                                        $Duration += $val->Duration;
                                        $TotalPrice += $val->TotalPrice;
                                        $BiayaTelkom += $val->TotalTelkom;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfooter>
                                <tr>
                                    <td colspan='3' class='text-right' >Total</td>
                                    <td class="text-right" >{{number_format($WaktuReal, 0, ',', '.')}}</td>
                                    <td class="text-right" >{{number_format($Duration, 0, ',', '.')}}</td>
                                    <td>&nbsp;</td>
                                    <td class="text-right" >{{number_format($TotalPrice, 0, ',', '.')}}</td>
                                    <td>&nbsp;</td>
                                    <td class="text-right" >{{number_format($BiayaTelkom, 0, ',', '.')}}</td>
                                        @php
                                            $allBiaya = round($TotalPrice, 2);
                                            $allBiayaTelkom = round($BiayaTelkom, 2);
                                            if ($allBiayaTelkom != 0) {
                                                $allPenghematanPercentage = ($allBiayaTelkom - $allBiaya) / $allBiayaTelkom * 100;
                                            } else {
                                                $allPenghematanPercentage = 0; // Atau nilai default sesuai kebutuhan Anda
                                            }
                                            $allPenghematan = number_format($allPenghematanPercentage, 0);
                                        @endphp
                                    <td class="text-right" >{{$allPenghematan}}%</td>
                                </tr>
                            </tfooter>
                        </table>
                        

                    </div>
                    <!-- end widget content -->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12 margin-right-2">
                                    <a href="javascript:void(0);" id="mybutton-back" class="btn btn-labeled btn-default margin-right-2" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
                                </div>
                            </div>
                        </div>
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
// var button = document.getElementById("mybutton-calculate");
// var elementToShow = document.getElementById("laksjdasldj");
// button.addEventListener("click", function() {
//     // Ubah display menjadi block ketika tombol diklik
//     elementToShow.style.display = "block";
// });
	// var pagefunction = function() {
	// 	 // Date Range Picker
	// 	$("#ftDateStart").datepicker({
	// 		defaultDate: "now",
	// 		changeMonth: true,
	// 		numberOfMonths: 2,
	// 		prevText: '<i class="fa fa-chevron-left"></i>',
	// 		nextText: '<i class="fa fa-chevron-right"></i>',
	// 		onClose: function (selectedDate) {
	// 			$("#ftDateEnd").datepicker("option", "maxDate", selectedDate);
	// 		}
	// 	});
	// 	$("#ftDateEnd").datepicker({
	// 		defaultDate: "+1w",
	// 		changeMonth: true,
	// 		numberOfMonths: 2,
	// 		prevText: '<i class="fa fa-chevron-left"></i>',
	// 		nextText: '<i class="fa fa-chevron-right"></i>',
	// 		onClose: function (selectedDate) {
	// 			$("#ftDateStart").datepicker("option", "minDate", selectedDate);
	// 		}
	// 	});
	// };
    // $( document ).ready(function() {
    //     pageSetUp();
    //     pagefunction();
    //     my_data_table.init('#dt_basic');
    //     my_form.init();
    // });
</script>
