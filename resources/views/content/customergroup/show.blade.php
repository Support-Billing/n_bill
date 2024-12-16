<div class="row">
    <section class="col col-sm-12">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" >Nama Customer Group : {{ucwords($data_detil->name)}} </th>
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
</div>
<br />
<div class="row"> 
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Customer ~ Jumlah : </th>
                </tr>
                <tr>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Tarif Per Menit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="4" >Customer Group Prices ~ Jumlah : {{$data_detil['prices']->count()}}</th>
                </tr>
                <tr>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Tarif Per Menit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_detil['prices'] as $prices)
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
    </section>
</div>