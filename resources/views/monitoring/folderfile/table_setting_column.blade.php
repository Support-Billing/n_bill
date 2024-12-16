<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
    
    <header>
        <h2>Xample File dan Setting Parser Row</h2>
        <?php $valuesArray = str_getcsv($data_detil->DataXample); ?>
        <?php $jsonResult = json_encode($valuesArray, JSON_PRETTY_PRINT); ?>
    </header>

    <!-- widget div-->
    <div style="padding : 13px 0 0" >

        <!-- widget content -->
        <div class="widget-body">

            <div class="tabs-left">
                <ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
                    <li class="active">
                        <a href="#tab-r1" data-toggle="tab">
                            Update Parser
                        </a>
                    </li>
                    <li>
                        <a href="#tab-r2" data-toggle="tab">
                            Data Real CSV
                        </a>
                    </li>
                    <li>
                        <a href="#tab-r3" data-toggle="tab">
                            CSV To Json
                        </a>
                    </li>
                    <li>
                        <a href="#tab-r4" data-toggle="tab">
                            CSV To Array
                        </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="tab-r1">
                        <table class="table table-bordered table-striped hidden-mobile" style="width:95%!important" >
                            <thead>
                                <tr>
                                    <th colspan="4" class="text-center" >Data Parser</th>
                                </tr>
                                <tr>
                                    <th>Nama System</th>
                                    <th>Pilih Kolom Data</th>
                                    <th>Filter Data</th>
                                    <th>Show Xample Data</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($setting_colom as $key => $val)
                                    <?php
                                        /************************* Setting Data Parser */
                                        $temp_clear_data = '';
                                        $clear_data = '';
                                        $pattern_regex = '';
                                        $datakol = $val->Kolom;
                                        $datavalue  = $data_detil->$datakol;
                                        $stringWithoutK  = str_replace('k', '', $datavalue);
                                        $numericValue = intval($stringWithoutK);
                                        $numericValueMinusOne = $numericValue - 1;
                                        if($numericValueMinusOne < 0){
                                            $clear_data = '-';
                                        }else{
                                            $temp_clear_data = $valuesArray[$numericValueMinusOne];
                                            /************************* Setting Data Parser Regex */
                                            $datakol_regex = 'regex_'.$val->Kolom;
                                            $pattern_regex  = $data_detil['setting_parser_regex']->$datakol_regex;
                                            if(!empty($pattern_regex)){
                                                // $pattern_regex = preg_quote($pattern_regex, '/');
                                                // if (preg_match($pattern_regex, $temp_clear_data, $matches)) {
                                                if (preg_match($pattern_regex, $temp_clear_data, $matches)) {
                                                    if (isset($matches[1])) {
                                                        $clear_data = $matches[1];
                                                    } else {
                                                        $clear_data = 'Tidak ada nilai setelah melakukan parser.';
                                                    }
                                                }
                                            } else {
                                                $clear_data = $temp_clear_data;
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            {{ $val->Name }}
                                            @if($val->mandatory == 'yes')
                                            <span class="text-danger" >*</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a 
                                                href="javascript:void(0);" 
                                                class="setting_colom" 
                                                data-type="select2" 
                                                data-pk="<?php echo $val->Kolom; ?>" 
                                                data-select-search="true" 
                                                data-value="<?php echo $datavalue; ?>" 
                                                data-original-title="Pilih Kolom">
                                            </a>
                                        </td>
                                        <td>
                                            <a 
                                                href="javascript:void(0);"
                                                class="regex999" 
                                                data-type="text" 
                                                data-pk="<?php echo $val->Kolom; ?>" 
                                                data-original-title="Settting Regex">
                                                    <?php echo $pattern_regex; ?>
                                                </a>
                                        </td>
                                        <td id="update_<?php echo $val->Kolom; ?>" >
                                                <?php echo $clear_data; ?>
                                                <a 
                                                    href="javascript:void(0);" 
                                                    class="btn btn-danger btn-xs" 
                                                    style="float:right" 
                                                    onclick="pagefunction('delete_value_kolom', '<?php echo $val->Kolom; ?>','delete')" 
                                                    data-original-title="Delete" 
                                                    rel="tooltip" 
                                                    data-placement="left">
                                                        <i class="fa fa-trash-o"></i>
                                                </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="tab-r2" style="margin-right:20px" >
                        <pre><code class="javascript"><?php echo $data_detil->DataXample; ?></code></pre>
                        
                        <input type="hidden" id="XampleFile_get" value="<?php echo $data_detil->XampleFile; ?>" >
                        <input type="hidden" id="JumlahColumn_get" value="<?php echo $data_detil->jumlahcolumn; ?>" >
                    </div>

                    <div class="tab-pane" id="tab-r3">
                        <pre><code class="javascript"><?php echo $jsonResult; ?></code></pre>
                    </div>

                    <div class="tab-pane" id="tab-r4">
                        <table class="table table-bordered table-striped hidden-mobile" style="width:95%!important" >
                            <thead>
                                <tr>
                                    <th colspan="3" >Data Parser</th>
                                </tr>
                                <tr>
                                    <th>Nama Kolom</th>
                                    <th>Xample Data</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php foreach (range(1, $data_detil->jumlahcolumn) as $number) { ?>
                                    <tr>
                                        <td>Data Kolom ke-<?php echo $number; ?></td>
                                        <td id="KK<?php echo $number; ?>" ><?php echo $valuesArray[$number-1] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->
