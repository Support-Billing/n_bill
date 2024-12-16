<div class="row">
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile" id="BusinessInfo" >
            <thead>
                <tr>
                    <th colspan="2" > Business Info </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-5" ><strong>Corporate Name</strong></td>
                    <td>: {{ucwords($data_detil->clientName)}}</td>
                </tr>
                <tr>
                    <td><strong>Contact Person/PIC</strong></td>
                    <td>: {{ucwords($data_detil->contactName)}}</td>
                </tr>
                <tr>
                    <td><strong>Telephone Number </strong></td>
                    <td>: {{$data_detil->telephone1}}</td>
                </tr>
                <tr>
                    <td><strong>Fax Line</strong></td>
                    <td>: {{$data_detil->fax}}</td>
                </tr>
                <tr>
                    <td><strong>Email Address</strong></td>
                    <td>: {{$data_detil->email1}}</td>
                </tr>
                <tr>
                    <td><strong>Address Information</strong></td>
                    <td> {!! $data_detil->address1 !!}</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Tax & Sales Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-5" ><strong>TAX ID Number</strong></td>
                    <td>: {{$data_detil->taxID}}</td>
                </tr>
                <tr>
                    <td><strong>Tax Address</strong></td>
                    <td> {!! $data_detil->taxAddress !!}</td>
                </tr>
                <tr>
                    <td><strong>Managed By</strong></td>
                    <td>: {{$data_detil->marketingName}}</td>
                </tr>
                <tr>
                    <td><strong>Assisted By</strong></td>
                    <td>: {{$data_detil->marketingName2}}</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>

<div class="row">

    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Project Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Alias Corporate Name/Title</td>
                    <td>: {{$data_detil->clientName2}}</td>
                </tr>
                <tr>
                    <td>Contact Person/PIC</td>
                    <td>: {{$data_detil->contactName2}}</td>
                </tr>
                <tr>
                    <td>Email Address, more than 1 use</td>
                    <td>: {{$data_detil->email2}}</td>
                </tr>
                <tr>
                    <td>Secondary Address</td>
                    <td> {!! $data_detil->address2 !!}</td>
                </tr>
<!-- 
                <tr>
                    <td>Other Information</td>
                    <td> {!! $data_detil->otherDetails !!}</td>
                </tr>
                 -->
            </tbody>
        </table>
    </section>

    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Status </td>
                    <td>:
                    <?php $Status = array( 0 => 'Active', 1 => 'Active', 3 => 'Closed' ); ?>
                    @foreach ($Status as $keyStatus => $StatusResult)
                        @if($data_detil->custStatus == $keyStatus)
                            {{ $StatusResult}}
                        @endif
                    @endforeach
                    </td>
                </tr>
                <tr>
                    <td>Date Approach </td>
                    <td>: {{ $data_detil->submitDate == '0000-00-00' ? '-' : $data_detil->submitDate }}</td>
                </tr>
                <tr>
                    <td>Date Prospected </td>
                    <td>: {{ $data_detil->prospectDate == '0000-00-00' ? '-' : $data_detil->prospectDate }}</td>
                </tr>
                <tr>
                    <td>Invoicing Priority </td>
                    <td>: {{ $data_detil->invoicePrior ? 'General' : 'Priority' }}                    </td>
                </tr>
                <tr>
                    <td>Address </td>
                    <td>: {{$data_detil->customText}}</td>
                </tr>
            </tbody>
        </table>
    </section>

    
    <section class="col col-sm-12">
        <table class="table table-bordered table-striped hidden-mobile">
            <tbody>
                <tr>
                    <td>Other Information</td>
                    <td> {!! $data_detil->otherDetails !!}</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>



<div class="row">
    <section class="col col-sm-12">
        <form action="" id="filter_table_project" class="smart-form">
            @csrf
            <input type="hidden" name="idxCustomer" value="{{$data_detil->idxCore}}" >
        </form>
        <table id="dt_project" class="table table-striped table-bordered table-hover" width="100%" data-source="{{url('customerprojectload')}}" data-filter="#filter_table_project" >
            <thead>
                <tr>
                    <th colspan="2" >List Project : {{ucwords($data_detil->clientName)}}</td>
                    <th colspan="2" class="text-center">
                        <!-- 
                            <a
                            href='customer/{{$data_detil->idxCore}}/customer_project' 
                            id='mybutton-add-project-{{$data_detil->idxCore}}'
                            class='btn btn-primary btn-xs margin-right-5' 
                            data-toggle='modal' 
                            data-target='#remoteModal'><i class='fa fa-plus'></i> Project</a>
                        -->
                    </th>
                </tr>
                <tr>
                    <th>Project Name</th>
                    <th>Detail Project</th>
                    <th>Status</th>
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
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>  
<script type="text/javascript">
    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_project');
    });
</script>