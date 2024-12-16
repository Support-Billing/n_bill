
<div class="row">
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" >Detail Customer : {{$data_detil->title .'. '. ucwords($data_detil->clientName)}} </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"> {{$data_detil->clientName2}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Addr 1</strong></td>
                    <td> {!! $data_detil->address1 !!} </td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Tax ID</strong></td>
                    <td>: {{$data_detil->taxID}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Tax Addr</strong></td>
                    <td> {!! $data_detil->taxAddress !!} </td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Phone</strong></td>
                    <td>: {{$data_detil->telephone1}}</td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    @if($data_detil->custStatus==1)
                        <th colspan="2" >Status : <span class="label label-success">Active</span> / Jumlah Project : {{$jumlah_project}} </th>
                        <!-- <th colspan="2" >Status : <span class="label label-success">Active</span> / ~jumlah Project~ </th> -->
                    @else
                        <th colspan="2" >Status : <span class="label label-warning">Not Active</span> / Jumlah Project : {{$jumlah_project}} </th>
                        <!-- <th colspan="2" >Status : <span class="label label-warning">Not Active</span> / ~jumlah Project~ </th> -->
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" ><strong>Leads</strong></td>
                </tr>
                <tr>
                    <td class="col-sm-5" ><strong>Primary Manage by</strong></td>
                    <td>: {{$data_detil->marketingID}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Secondary Manage by</strong></td>
                    <td>: {{$data_detil->marketingID2}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Custom CDR</strong></td>
                    <td>: {{$data_detil->isTier}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Compare CDR</strong></td>
                    <td>: {{$data_detil->isTier}}</td>
                </tr>
                <tr>
                    <td class="col-sm-3" ><strong>Tiering </strong></td>
                    <td>: {{$data_detil->isTier}}</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>