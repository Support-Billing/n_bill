<div class="row">
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    <th colspan="2" >Project Name : {{ucwords($data_detil->projectName)}} </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"> Project Alias : {{$data_detil->projectAlias}}</td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="col col-sm-6">
        <table class="table table-bordered table-striped hidden-mobile">
            <thead>
                <tr>
                    @if($data_detil->active==1)
                        <th colspan="2" >Status : <span class="label label-success">Active</span></th>
                    @else
                        <th colspan="2" >Status : <span class="label label-warning">Not Active</span></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" ><strong>xxx</strong></td>
                </tr>
            </tbody>
        </table>
    </section>
</div>