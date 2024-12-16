<div class="row">
    <section class="col col-sm-12">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" >Nama Customer Group : {{ucwords($data_detil->nama)}} </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-3" ><strong>Status</strong></td>
                    <td>: 
                        @if($data_detil->active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-warning">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Created </strong></td>
                    <td>: 
                        <strong>By </strong> 
                        @if($data_detil->created_by)
                            {{$data_detil->created_by}}
                        @else
                            -
                        @endif
                        <strong>At </strong> 
                        @if($data_detil->created_at)
                            {{$data_detil->created_at}}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Updated</strong></td>
                    <td>: 
                        <strong>By </strong> 
                        @if($data_detil->updated_by)
                            {{$data_detil->updated_by}}
                        @else
                            -
                        @endif
                        <strong>At </strong> 
                        @if($data_detil->updated_at)
                            {{$data_detil->updated_at}}
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
                    <th colspan="2" style="vertical-align:middle" >Customer Group Prices ~ Jumlah : {{$data_detil['customergroupprice']->count()}}</th>
                    <td colspan="2" >                            
                        
                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a 
                                    href="customergroup/{{$data_detil->idx}}/customer_group_prices" 
                                    id='mybutton-add-customer'
                                    class='btn btn-primary btn-xs margin-right-5' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <i class='fa fa-plus'></i> 
                                        Add <?php echo $page_title; ?> Prices 
                                </a>
                                <!-- <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-primary" data-breadcrumb="create" onclick="my_form.open(this.id)" data-module="customergroup" data-url="customergroup/create"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add <?php echo $page_title; ?> Prices</a>
                                <div class="clearfix"></div> -->
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Tarif Per Menit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_detil['customergroupprice'] as $prices)
                    <tr>
                        <td>{{$prices->startRange}}</td>
                        <td>{{$prices->endRange}}</td>
                        <td>{{$prices->tarifPerMenit}}</td>
                        <td>
                            @if($prices->active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-warning">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        
        <table id="dt_price" class="table table-striped table-bordered table-hover" width="100%"  data-source="{{url('customergrouppriceload')}}" data-filter="#filter_table">
            <thead>
                <tr>
                    <th colspan="2" style="vertical-align:middle" >Customer Group Prices ~ Jumlah : {{$data_detil['customergroupprice']->count()}}</th>
                    <td colspan="2" >
                        @if($insert_otoritas_modul)
                            <div class="padding-5 border-bottom-1">
                                <a 
                                    href="customergroup/{{$data_detil->idx}}/customer_group_prices" 
                                    id='mybutton-add-customer'
                                    class='btn btn-primary btn-xs margin-right-5' 
                                    data-toggle='modal' 
                                    data-target='#remoteModal'>
                                        <i class='fa fa-plus'></i> 
                                        Add <?php echo $page_title; ?> Prices 
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Tarif Per Menit</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </section>
    <section class="col col-sm-12">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" >
                        <strong>Customer Group Members</strong>
                        <a 
                            href='menu/customer/add_child' 
                            id='mybutton-add-customer'
                            class='btn btn-primary btn-xs margin-right-5' 
                            data-toggle='modal' 
                            data-target='#remoteModal'><i class='fa fa-plus'></i> Add Customer Group Members</a>
                    </td>
                </tr>
                <tr>
                    <td><strong>No</strong></td>
                    <td class="col-3" ><strong>Customer</strong></td>
                    <td ><strong>isDefault</strong></td>
                    <td ><strong>Status</strong></td>
                    <th data-hide="phone,tablet"><strong>Tambah Project</strong></th>
                </tr>
                @foreach($data_detil2 as $key => $members)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$members->clientName}}</td>
                        <td>{{$members->isDefault}}</td>
                        <td>
                            @if($members->active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($members->active)
                            <a 
                                href='menu/{{$members->idxCustomer}}/add_child' 
                                id='mybutton-add-child-{{$members->idxCustomer}}'
                                class='btn btn-primary btn-xs margin-right-5' 
                                data-toggle='modal' 
                                data-target='#remoteModal'><i class='fa fa-plus'></i> Project</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
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
<!-- /.modal -->



<script type="text/javascript">
    $( document ).ready(function() {
        pageSetUp();
        my_data_table.init('#dt_basic');
    });
</script>