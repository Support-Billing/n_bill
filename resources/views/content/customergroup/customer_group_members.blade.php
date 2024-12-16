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
            <form action="customergroupmember/{{$idx}}/store_members" id="finputmembersData" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Customer Name <sup>*</sup></label>
                        <div class="col-md-6">
                            <select name="idxCoreCust" id="input_idxCoreCust" class="select2 select2-offscreen" placeholder="-- Choose Customer --" multiple="multiple" tabindex="-1" title="">
                                @foreach ($customers as $key => $customer)
                                    <option value="{{ $customer->idxCore }}"  >{{ $customer->clientName }}</option>
                                @endforeach
                            </select>
                            <span id="error_idxCustomer"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Project <br /><sup>*</sup> Choose Customer First</label>
                        <div class="col-md-6" id="memberGetProject" ></div>
                    </div>
                    
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" id="mybutton-back-member" class="btn btn-labeled btn-default margin-right-2" data-dismiss="modal"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span> Back</a>
            <a href="javascript:void(0);" id="mybutton-add-member" class="btn btn-labeled btn-success" onclick="my_form.submit('#finputmembersData')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                pageSetUp();
                my_form.init();

                        
                // On change event
                $('#input_idxCoreCust').on('change', function() {
                    $('#memberGetProject').html('response');

                    var linkProject = "{{route('customergroupmember.member_get_project', 'memberGetProject')}}"; 
                    var dataType = 'html'; 
                    var dataSend = {};
                    var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    dataSend['_token'] = csrfToken;
                    dataSend['_method'] = 'POST';
                    var selectedValue = $(this).val();
                    dataSend['customers'] = selectedValue;

                    $.ajax({
                        url: linkProject,
                        type: 'POST',
                        dataType: 'html', // Asumsi respons adalah HTML, sesuaikan dengan kebutuhan
                        data: dataSend,
                        beforeSend: function () {
                            // Anda bisa menambahkan kode untuk menunjukkan loading state, misalnya:
                            $('#memberGetProject').html('Loading...');
                        },
                        success: function (response) {
                            $('#memberGetProject').html(response);

                            // Pastikan elemen baru tersedia sebelum menginisialisasi select2
                            $('#memberGetProject').promise().done(function() {
                                // Inisialisasi select2
                                $('#memberGetProject select').select2();

                                // Pastikan ini dijalankan setelah select2 diinisialisasi
                                setTimeout(function() {
                                    // Set width 100% pada elemen select2 pertama
                                    $('#memberGetProject .select2').first().css('width', '100%');
                                }, 0);
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Penanganan kesalahan, Anda bisa menampilkan pesan kesalahan atau log error
                            console.error('AJAX Error:', textStatus, errorThrown);
                            $('#memberGetProject').html('An error occurred, please try again.');
                        },
                        complete: function () {
                            // Kode yang ingin dijalankan setelah permintaan selesai
                        }
                    });

                });

            });
        </script>
    </body>
</html>