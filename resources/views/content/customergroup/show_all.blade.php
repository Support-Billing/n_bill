<div class="row">
    <section class="col col-sm-12">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" ><h1> <!-- {{$data_detil->idxCore}} //--> Customer Group : {{ucwords($data_detil->name)}}</h1></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-3" ><strong>Status</strong></td>
                    <td>: <span class="label label-{{ $data_detil->active ? 'success' : 'warning' }}">{{ $data_detil->active ? 'Active' : 'Inactive' }}</span></td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Created </strong></td>
                    <td>: 
                        <strong>By </strong> {{ $data_detil->created_by ?? '-' }}
                        <strong>At </strong> {{ $data_detil->created_at ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Updated</strong></td>
                    <td>: 
                        <strong>By </strong> {{ $data_detil->updated_by ?? '-' }}
                        <strong>At </strong> {{ $data_detil->updated_at ?? '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="col col-sm-6">
        <form action="" id="filter_table_member" class="smart-form">
            @csrf
            <input type="hidden" name="idxcustomergroup" value="{{$data_detil->idxCore}}" >
        </form>
        <table id="dt_member" class="table table-striped table-bordered table-hover" width="100%" data-source="{{url('customergroupmemberload')}}" data-filter="#filter_table_member" >
            <thead>
                <tr>
                    <th colspan="3" style="vertical-align:middle" >
                        Customer Group Members
                    </th>
                    <td>
                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a 
                                    href="customergroupmember/{{$data_detil->idxCore}}/customer_group_members" 
                                    id='mybutton-add-customer'
                                    class='btn btn-primary btn-xs margin-right-5' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal2'>
                                        <i class='fa fa-plus'></i> 
                                        Add Customer
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>No</strong></td>
                    <td class="col-3" ><strong>Customer</strong></td>
                    <th data-hide="phone,tablet"><strong>Project</strong></th>
                    <th data-hide="phone,tablet"><strong>Action</strong></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
    <section class="col col-sm-6">
        <form action="" id="filter_table_price" class="smart-form">
            @csrf
            <input type="hidden" name="idxcustomergroup" value="{{$data_detil->idxCore}}" >
        </form>
        <table id="dt_price" class="table table-striped table-bordered table-hover" width="100%"  data-source="{{url('customergrouppriceload')}}" data-filter="#filter_table_price">
            <thead>
                <tr>
                    <th colspan="5" style="vertical-align:middle" >Customer Group Prices</th>
                    <td>
                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a 
                                    href="customergroupprice/{{$data_detil->idxCore}}/customer_group_prices" 
                                    id='mybutton-add-customer'
                                    class='btn btn-primary btn-xs margin-right-5' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <i class='fa fa-plus'></i> 
                                        Add Prices 
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Tarif Per Menit</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
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

<div class="modal fade bs-example-modal-lg" id="remoteModal2" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">
            <!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
        </div>  
    </div>  
</div>

<div class="modal fade" id="remoteModal3" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
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
    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_price');
        my_data_table.init('#dt_member');
        
    });
</script>