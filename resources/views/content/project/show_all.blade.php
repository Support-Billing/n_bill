<div class="row">
    
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" > Project Info </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-4" ><strong>Nama Customer</strong></td>
                    <td>: {{ucwords(@$data_detil->clientName)}}</td>
                </tr>
                <tr>
                    <td><strong>Project Name</strong></td>
                    <td>: {{ucwords($data_detil_project->projectName)}}</td>
                </tr>
                <!-- <tr>
                    <td><strong>Status</strong></td>
                    <td>: 
                        @if($data_detil_project->statusData)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-warning">Inactive</span>
                        @endif
                    </td>
                </tr> -->
                <tr>
                    <td colspan="2" class="form-horizontal"> 
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                            <span class="input-group-addon">
                                                    @if($data_detil_project->isSIPTRUNK)
                                                        <i class="fa fa-check-square-o"></i> SIP TRUNK
                                                        @else
                                                        <i class="fa fa-square-o"></i> SIP TRUNK
                                                    @endif
                                            </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    @if($data_detil_project->isSIPREG)
                                                        <i class="fa fa-check-square-o"></i> SIP REG
                                                        @else
                                                        <i class="fa fa-square-o"></i> SIP REG
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    @if($data_detil_project->isFWT)
                                                        <i class="fa fa-check-square-o"></i> FWT
                                                        @else
                                                        <i class="fa fa-square-o"></i> FWT
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    @if($data_detil_project->isApps)
                                                        <i class="fa fa-check-square-o"></i> APP
                                                        @else
                                                        <i class="fa fa-square-o"></i> APP
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="checkbox">
                                                    @if($data_detil_project->isSLI)
                                                        <i class="fa fa-check-square-o"></i> SLI
                                                        @else
                                                        <i class="fa fa-square-o"></i> SLI
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3 form-group" ><strong>CLI</strong></td>
                    <td class="form-horizontal"> 
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <span class="input-group-addon">
                                            @if($data_detil_project->isCLI)
                                                <i class="fa fa-check-square-o"></i> CLI
                                                @else
                                                <i class="fa fa-check-square-o"></i> Non CLI
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Contact Person/PIC </strong></td>
                    <td>: {{$data_detil_project->contactName}} </td>
                </tr>
                <tr>
                    <td><strong>Telephone Number </strong></td>
                    <td>: {{$data_detil_project->telephone}} </td>
                </tr>
                <tr>
                    <td><strong>Email Address </strong></td>
                    <td>: {{$data_detil_project->email}} </td>
                </tr>
                <tr>
                    <td><strong>Address Detail </strong></td>
                    <td>: {!! $data_detil_project->address !!} </td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td colspan="2" >
                        <strong>By </strong> 
                        @if(@$data_detil_project->modBy)
                            {{$data_detil_project->modBy}}
                        @else
                            -
                        @endif
                        <strong>At </strong> 
                        @if($data_detil_project->dateMod)
                            {{$data_detil_project->dateMod}}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Project Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Address Detail </strong></td>
                    <td>{!! $data_detil_project->detailProject1 !!} </td>
                </tr>
                <tr>
                    <td><strong>Other Detail </strong></td>
                    <td>{!! $data_detil_project->detailProject2 !!} </td>
                </tr>
            </tbody>
        </table>
        
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Status Project </strong></td>
                    <td>: 
                        <?php $varStatusProject = array ( 0=>'Waiting', 1=>'Free Trial', 2=>'Trial on Subscribe ',3=>'Subscribe',4=>'Closed'); ?>
                        <?php
                            $status = $data_detil_project->statusProject;

                            // Periksa apakah status ada dalam array $varStatusProject
                            echo isset($varStatusProject[$status]) ? $varStatusProject[$status] : 'Unknown Status';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Status Data </strong></td>
                    <td>: 
                        <?php $varStatusData = array ( 1=>'Active', 2=>'Closed'); ?>
                        <?php
                            $status = $data_detil_project->statusData;

                            // Periksa apakah status ada dalam array $varStatusProject
                            echo isset($varStatusData[$status]) ? $varStatusData[$status] : 'Unknown Status';
                        ?>
                        
                    </td>
                </tr>
                <tr>
                    <td><strong>Free Trial Interval </strong></td>
                    <td>: {!! $data_detil_project->startFT !!} - {!! $data_detil_project->endFT !!} </td>
                </tr>
                <tr>
                    <td><strong>On Subscribe Trial Interval </strong></td>
                    <td>: {!! $data_detil_project->startPT !!} {!! $data_detil_project->endPT !!} </td>
                </tr>
                <tr>
                    <td><strong>Start Joined/Subscribe </strong></td>
                    <td>: {!! $data_detil_project->startClient !!} </td>
                </tr>
            </tbody>
        </table>
    </section>
</div>

<div class="row" >
    <section class="col col-sm-12" id="all_table"></section>
</div>

<div class="row">
    <section class="col col-sm-12">
        <form action="" id="filter_table_price" class="smart-form">
            @csrf
            <input type="hidden" name="projectID" value="{{$data_detil_project->idxCore}}" >
        </form>
        <table id="dt_price" class="table table-striped table-bordered table-hover" width="100%" data-source="{{url('projectpriceload')}}" data-filter="#filter_table_price" >
            <thead>
                <tr>
                    <th colspan="6" >List Price : {{ucwords($data_detil_project->projectName)}}</td>
                    <th colspan="2" class="text-center">
                        <a
                        href='project/{{$data_detil_project->idxCore}}/project_price' 
                        id='mybutton-add-project-{{$data_detil_project->idxCore}}'
                        class='btn btn-primary btn-xs margin-right-5' 
                        data-toggle='modal' 
                        data-target='#remoteModal_lg'><i class='fa fa-plus'></i> Price</a>
                    </th>
                </tr>
                <tr>
                    <th>MOBILE&SLJJ(IDR)</th>
                    <th>PSTN(IDR)	</th>
                    <th>Premium(IDR)</th>
                    <th>Min Comm (IDR)</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-12 margin-right-5">
            <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-default margin-right-5" onclick="my_form.go_back()"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
        </div>
    </div>
</div>

<!-- Dynamic Modal -->  
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>

<div class="modal fade bs-example-modal-lg" id="remoteModal_lg" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>  
<!-- /.modal -->



<script type="text/javascript">
    var pagefunction = function(data_run) {
        console.log('pagefunction');
        console.log(data_run);
        function reset_price() {
            my_form.reset('#finput');
            my_data_table.reload('#dt_price');
        }
        function reset_data(moadal_status) {
            console.info(data_run);
            var link = `{{route('project.show_all_table')}}`; 
            var dataType = 'html'; 
            $.ajax({
                "url": link,
                "type": 'GET',
                "dataType": dataType,
                "data": {projectID:'{{$data_detil_project->idxCore}}'},
                beforeSend: function () {
                    // beforeSend
                },
                success: function (data) {
                    $('#all_table').html(data);
                    var XampleFile_get = $('#XampleFile_get').val();
                    var JumlahColumn_get = $('#JumlahColumn_get').val();

                    $('#JumlahColumn_show').text(JumlahColumn_get);
                    $('#XampleFile_show').text(XampleFile_get);

                    if (moadal_status == 'close'){
                        $("#remoteModal").modal("hide");
                        $("#remoteModal_lg").modal("hide");
                    }

                    
                }, complete: function () {
                    // complete
                }
            });
        }
        if (data_run == 'reset_data_close_modal'){
            reset_data('close');
        }
        if (data_run == 'reset_data'){
            reset_data('open');
        }
        if (data_run == 'reset_price'){
            reset_price();
        }
	};

    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_price');
        pagefunction('reset_data');
    });
</script>