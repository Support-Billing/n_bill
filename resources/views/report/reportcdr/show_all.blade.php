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
                        
                        <div class="alert alert-warning fade in">
                            <i class="fa-fw fa fa-warning"></i>
                            <strong>Warning</strong> Informasi detil Data Laporan CDR.
                        </div>
<pre>
<code class="javascript">
Data row CDR yang dimunculkan dibatasi 1000 baris. Adapun kebutuhan dan fungsi lainnya dituangkan pada point berikut :
1. Agar penggunaan user tidak terlalu berat ketika membuka browser maka Data CDR dibatasi 1000 baris.
2. User dapat melihat data cdr secara keseluruhan pada excel yang dapat di download pada tombol "Download <?php echo $page_title; ?>".
3. Excel yang dapat di download dalam waktu harian atau detil informasi Data CDR dari Summary terkait.
</code>
</pre>
                        @if($import_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a href="{{route('download_reportcdrdetil', $urlData)}}" class="btn btn-labeled bg-color-greenLight text-white" >
                                    <span class="btn-label"><i class="fa fa-cloud-download"></i></span>
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
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Jam</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> No. Asal</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> IP</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> No. Tujuan</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Real</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Waktu Tagih</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Tarif/Menit</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    $TotalPrice = 0;
                                    $WaktuReal = 0;
                                    $Duration = 0;
                                ?>
                                @foreach ($results as $key => $val)
                                    <tr>
                                        <td data-hide="phone">{{$key+1}}</td>
                                        <td data-class="expand">{{ date('Y-m-d', strtotime($val->datetime)) }}</td>
                                        <td data-class="expand">{{ date('H:i:s', strtotime($val->datetime)) }}</td>
                                        <td data-class="expand">{{$val->sourceNoOut}}</td>
                                        <td data-class="expand">{{$val->sourceIPOnly}}</td>
                                        <td data-class="expand">{{$val->destNo}}</td>
                                        <!-- WaktuReal -->
                                        <td data-class="expand" class="text-right" >{{$val->elapsedTime}} </td>
                                        <!-- WaktuTagih / Duration -->
                                        <td data-class="expand" class="text-right" >
                                            @if(isset($_DataProjectDetailsCLI))
                                                @if($val->custTime <= 60)
                                                    @php
                                                        $val->custTime = 60;
                                                    @endphp
                                                @endif
                                                {{ $val->custTime }}
                                            @endif
                                        </td>
                                        @if(!empty($priceGroup))
                                            <td data-class="expand" class="text-right">{{ number_format($priceGroup, 2, ',', '.') }}</td>
                                        @else
                                            <td data-class="expand" class="text-right">{{ number_format($val->custPrice, 2, ',', '.') }}</td>
                                        @endif
                                        <?php
                                            $Tarif = 0;
                                            if(!empty($priceGroup)){
                                                $Tarif = $priceGroup;
                                            }else{
                                                $Tarif = $val->custPrice;
                                            }
                                            // biaya / TotalPrice
                                            if($val->custPrice != Null){
                                                $getMenit_custTimeCDR = $val->custTime/60 ;
                                                $get_custPrice = $getMenit_custTimeCDR * $Tarif;
                                            }
                                        ?>
                                        <td data-class="expand" class="text-right" >{{number_format($get_custPrice, 0, ',', '.')}}</td>
                                    </tr>
                                    <?php
                                        $WaktuReal = $WaktuReal + $val->elapsedTime;
                                        $Duration = $Duration + $val->custTime;
                                        $TotalPrice = $TotalPrice + $get_custPrice;
                                    ?>
                                @endforeach
                            </tbody>
                            <tfooter>
                                <!-- <tr>
                                    <td colspan='6' class='text-right' > {{$ProjectAlias}} </td>
                                    <td class="text-right" >{{number_format($WaktuReal, 0, ',', '.')}}</td>
                                    <td class="text-right" >{{number_format($Duration, 0, ',', '.')}}</td>
                                    <td>&nbsp;</td>
                                    <td class="text-right" >{{number_format($TotalPrice, 0, ',', '.')}}</td>
                                </tr> -->
                                <tr>
                                    <td colspan='6' class='text-right' > {{$ProjectAlias}} </td>
                                    <td class="text-right" >{{number_format($resultSumReportCdr->jmlWaktuReal, 0, ',', '.')}}</td>
                                    <td class="text-right" >{{number_format($resultSumReportCdr->jmlWaktuTagih, 0, ',', '.')}}</td>
                                    <td>&nbsp;</td>
                                    <td class="text-right" >{{number_format($resultSumReportCdr->biayaTagih, 0, ',', '.')}}</td>
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

