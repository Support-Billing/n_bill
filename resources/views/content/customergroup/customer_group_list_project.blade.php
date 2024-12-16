<html>
    <head>
        <script id="tinyhippos-injected">
            if (window.top.ripple) {
                window.top.ripple("bootstrap").inject(window, document);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    </head>
    <body>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-times"></i>
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span> <?php echo $page_title; ?>
            </h6>
        </div>
        <div class="modal-body">
            <table id="dt_member" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <td><strong>No</strong></td>
                        <th data-hide="phone,tablet"><strong>Keterangan Project</strong></th>
                        <th data-hide="phone,tablet"><strong>Action</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $key => $value)
                        <tr>
                            <td><strong>{{ $key + 1 }}</strong></td>
                            <td class="col-3" ><strong>{{ $value->idxFDesktop }} : {{$value->projectAlias}}</strong></td>
                            <td class="col-3" >
                                <a href="customergroupmember" 
                                    id="mybutton-add-project-{{$value->idxFDesktop}}"
                                    class="btn bg-color-orange btn-xs margin-right-5" 
                                    data-toggle="modal" 
                                    data-target="#remoteModal_lg">
                                    <i class="fa fa-eye"></i> &nbsp; View
                                </a>
                                
                                <a href='javascript:void(0);'
                                    class='btn btn-danger btn-xs' 
                                    id='mybutton-delete-{{$value->idxFDesktop}}'
                                    onclick='my_data_table.row_action.ajax(this.id)'
                                    data-original-title='Delete' 
                                    rel='tooltip' 
                                    data-url="{{ url('customergroup/'.$value->idxFDesktop.'/customer') }}"
                                    ><i class='fa fa-trash-o'></i> &nbsp; Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back-project" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                pageSetUp();
                my_form.init();
            });
        </script>
    </body>
</html>