<form action="menu/proses_ordering/data" id="finput_ordering" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
@csrf
<input type="hidden" value="PUT" name="_method">

<?php // if ($this->laccess->otoritas('edit')) : ?>
    <div id="konfirmasi_ordering" class="alert alert-info alert-block" style="display: none;">
        <h4 class="alert-heading">Info!</h4>
        Menu order has been change, to save please click the following button:<br>
        <a href="javascript:void(0);" id="mybutton-add" class="btn btn-labeled btn-success" onclick="my_form.submit('#finput_ordering')"><span class="btn-label"><i class="glyphicon glyphicon-floppy-disk"></i></span> Save New Order</a>
    </div>
<?php // endif; ?>

<div class="row">
    <!-- Penampung Order -->
    <input type="hidden" class="form-control font-md" name="nestable_output" >
    <input type="hidden" class="form-control font-md" name="nestable_temp" >
</div>
</form>

<div class="dd" id="nestable3" style="max-width: none !important;">
    <?php echo $list_data_menu; ?>
</div>


<script type="text/javascript">

$( document ).ready(function() {
    var konfirmasi_ordering = function (status) {
        var _box = $('#konfirmasi_ordering');
        if (status) {
            _box.show();
        } else {
            _box.hide();
        }

    };

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target), _output = list.data('output');
        if (window.JSON) {

            var _serialize_order = list.nestable('serialize');
            var _temp = $('[name="nestable_temp"]');

            if (_temp.val() === '') {
                _temp.val(window.JSON.stringify(_serialize_order));
            }

            _output.val(window.JSON.stringify(_serialize_order));

            if (_temp.val() !== _output.val()) {
                konfirmasi_ordering(true);
            } else {
                konfirmasi_ordering(false);
            }
        } else {
            alert('JSON browser support required for this demo.');
        }
    };

    $('#nestable3').nestable().on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable3').data('output', $('[name="nestable_output"]')));

    $('#nestable-menu').on('click', function (e) {
        var target = $(e.target), action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    my_form.init();
});
</script>