
<table class="table table-bordered table-striped hidden-mobile">
    <thead>
        <tr>
            <th class="col-sm-3 " >Prefix</th>
            <th class="col-sm-1 " >
                <a 
                    href='project/{{$data_detil_project->idxCore}}/project_prefix' 
                    id='mybutton-add-project_prefix'
                    class='btn btn-primary btn-xs margin-right-5' 
                    data-toggle='modal' 
                    data-target='#remoteModal'><i class='fa fa-plus'></i> Prefix
                </a>
            </th>
            <th>
                @foreach ($prefix_projects as $prefix_project)
                    <span style="margin-right:5px;text-decoration: underline;" >{{ $prefix_project->prefixNumber }}</span>
                @endforeach
            </th>
        </tr>
        <!-- <tr>
            <th>Accounts</th>
            <th>
                <a 
                    href='project/{{$data_detil_project->idxCore}}/project_accounts' 
                    id='mybutton-add-project_accounts'
                    class='btn btn-primary btn-xs margin-right-5' 
                    data-toggle='modal' 
                    data-target='#remoteModal'><i class='fa fa-plus'></i> Accounts
                </a>
            </th>
            <th>
                @foreach ($account_projects as $account_project)
                    <span style="margin-right:5px;text-decoration: underline;" >{{ $account_project->accountNumber }}</span>
                @endforeach
            </th>
        </tr> -->
        <tr>
            <th>Source IP(customer/origin) to End Point</th>
            <th>
                <a 
                    href='project/{{$data_detil_project->idxCore}}/project_prefixip' 
                    id='mybutton-add-project_source_ip'
                    class='btn btn-primary btn-xs margin-right-5' 
                    data-toggle='modal' 
                    data-target='#remoteModal'><i class='fa fa-plus'></i> Source IP
                </a>
            </th>
            <th>
                @foreach ($ip_projects as $ip_project)
                    <span style="margin-right:5px;text-decoration: underline;" >{{ $ip_project->startIP }}</span>
                @endforeach
            </th>
        </tr>
        <!-- <tr>
            <th>Server IP Destination (End Point)</th>
            <th>
                <a 
                    href='project/{{$data_detil_project->idxCore}}/project_prefixsvr' 
                    id='mybutton-add-customer'
                    class='btn btn-primary btn-xs margin-right-5' 
                    data-toggle='modal' 
                    data-target='#remoteModal'><i class='fa fa-plus'></i> Server IP Destination
                </a>
            </th>
            <th>
                @foreach ($billservers as $billserver)
                    <span style="margin-right:5px;text-decoration: underline;" >{{ $billserver->serverName }}</span>
                @endforeach
            </th>
        </tr> -->
    </thead>
</table>