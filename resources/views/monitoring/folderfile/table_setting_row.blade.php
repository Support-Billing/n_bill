<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
    
    <header>
        <h2>Xample File dan Setting Parser Row</h2>
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
                                    <th>Filter Data</th>
                                    <th>Show Xample Data</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($setting_colom as $key => $val)
                                    <?php
                                            $temp_clear_data = $data_detil->DataXample;
                                            $clear_data = '';
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
                                                $clear_data = '-';
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
                                                class="regex999" 
                                                data-type="text" 
                                                data-pk="<?php echo $val->Kolom; ?>" 
                                                data-original-title="Settting Regex">
                                                    <?php echo $pattern_regex; ?>
                                                </a>
                                        </td>
                                        <td id="update_<?php echo $val->Kolom; ?>" >
                                                <?php 

                                                    if (strlen($clear_data) > 100) {
                                                        $clear_data = substr($clear_data, 0, 100) . '...';
                                                    }else{
                                                        echo $clear_data;
                                                    }
                                                ?>
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
                        <?php $valuesArray = str_getcsv($data_detil->DataXample); ?>
                        <?php $jsonResult = json_encode($valuesArray, JSON_PRETTY_PRINT); ?>
                        <pre><code class="javascript"><?php echo $jsonResult; ?></code></pre>
                    </div>

                </div>
            </div>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->
