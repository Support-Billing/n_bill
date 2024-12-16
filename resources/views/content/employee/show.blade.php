<table class="table table-bordered table-striped hidden-mobile">
    <thead>
        <tr>
            <th colspan="4">Detail Pegawai : {{ucwords($data_detil->name)}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-sm-3"><strong>Photo</strong></td>
            <td>: {{$data_detil->avatar}}</td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>NIK</strong></td>
            <td>: {{$data_detil->nik}}</td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>Name</strong></td>
            <td>: {{ucwords($data_detil->name)}}</td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>NIK</strong></td>
            <td>: {{$data_detil->nik}}</td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>Work Location</strong></td>
            <td>: <?php echo $data_detil->worklocation->name; ?></td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>Phone</strong></td>
            <td>: {{$data_detil->phone}}</td>
        </tr>
        <tr>
            <td class="col-sm-3"><strong>City</strong></td>
            <td>: {{$data_detil->city}}</td>          
        </tr>
        <tr>
            <td class="col-sm-3"><strong>Address</strong></td>
            <td>: {{$data_detil->address}}</td>          
        </tr>
    </tbody>
</table>