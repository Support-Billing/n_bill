var my_data_table = my_data_table || {};

my_data_table.default = {
    timeout: 4000,
    base_url: {
        assets: ''
    },
    notification: {
        sound: false
    },
    list_table: [],
    oLanguage: {
        sEmptyTable: 'No data available in table'
    }
};

my_data_table.reset_list_table = function () {
    my_data_table.default.list_table = [];
};

my_data_table.filter = {};

// Init
my_data_table.init = function (id, sEmptyTable) {

    my_data_table.default.oLanguage.sEmptyTable = typeof sEmptyTable !== 'undefined' ? sEmptyTable : 'No data available in table';

    loadScript(my_global.config.base_url.assets + "/js/plugin/datatables/jquery.dataTables.min.js", function () {
        loadScript(my_global.config.base_url.assets + "/js/plugin/datatables/dataTables.colVis.min.js", function () {
            loadScript(my_global.config.base_url.assets + "/js/plugin/datatables/dataTables.tableTools.min.js", function () {
                loadScript(my_global.config.base_url.assets + "/js/plugin/datatables/dataTables.bootstrap.min.js", function () {
                    if (typeof id === 'undefined') {
                        loadScript(my_global.config.base_url.assets + "/js/plugin/datatable-responsive/datatables.responsive.min.js");
                        if (my_data_table.default.list_table.length > 0) {
                            $.each(my_data_table.default.list_table, function (idx, val) {
                                console.info('masuk val', val);
                                my_data_table.render(val);
                            });
                        }
                    } else {
                        loadScript(my_global.config.base_url.assets + "/js/plugin/datatable-responsive/datatables.responsive.min.js", my_data_table.render(id));
                    }
                });
            });
        });
    });
};

my_data_table.set_table = function (id) {
    my_data_table.default.list_table.push(id);
};

// render table 
my_data_table.render = function (id) {
    var url = $(id).data('source');
    var filter = $(id).data('filter');
    var paginate = $(id).data('paginate');
    var serializeArray = $(filter).serializeArray();
    
    // alert(paginate);
    // alert(JSON.stringify(serializeArray));

    var head_body = $(id).data('setting_head');
    var footer_body = $(id).data('setting_footer_body');
    var data_head = '';
    var data_footer_body = '';
    var _bPaginate = true;
    if (typeof paginate !== 'undefined') {
        _bPaginate = paginate;
    }
    $(id).dataTable({
        "ordering": false,
        "bFilter": false,
        "pagingType": 'full_numbers',
        "bProcessing": true,
        "serverSide": true,
        "bPaginate": _bPaginate,
        "pageLength": paginate,
        "oLanguage": {
            "sEmptyTable": my_data_table.default.oLanguage.sEmptyTable
        },
        "ajax": {
            "url": url,
            "type": "POST",
            "headers" : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            "data": function (d) {
                console.info('my_data_table render',d);
                // data_head_body = '<tr><th colspan="11" class="text-center"  >nama customer</th></tr>';
                // data_footer_body = '<tr><td colspan="10" >sum footer</td><td>count</td></tr>';
                d.extra_search = typeof filter !== 'undefined' ? $(filter).serializeArray() : [];
            },
            "dataSrc": function ( json ) {
                
                if (typeof head_body !== 'undefined') {
                    data_head_body = json.data_head_body;
                }
                if (typeof footer_body !== 'undefined') {
                    data_footer_body = json.data_footer_body;
                }
                //Make your callback here.
                // alert("Done!");
                return json.data;
            }   
        },
        "fnInitComplete": function () {
            $(id).prev('.dt-toolbar').remove();
        },
        "drawCallback": function (settings) {
            // console.info('my_data_table settings',settings);
            if ($("[rel=tooltip]", $(id)).length > 0) {
                $("[rel=tooltip]", $(id)).attr('data-placement', 'left');
                $("[rel=tooltip]", $(id)).tooltip();
            }
            
            if (typeof head_body !== 'undefined') {
                $('#dt_basic thead').prepend(data_head_body);
            }
            if (typeof footer_body !== 'undefined') {
                $('#dt_basic tbody').append(data_footer_body);
            }
            
        }
    });

};

// reload table
my_data_table.reload = function (id) {
    var table = $(id).DataTable();
    table.ajax.reload();
};

// toogle filtering
my_data_table.filter.toggle = function (id) {
    var tagid = '#' + id;
    var val = $(tagid).val();
    if ($(tagid).is(':checked')) {
        $(val).show();
    } else {
        $(val).hide();
    }
};

// reset filtering
my_data_table.filter.reset = function (id) {
    var filter = $(id).attr('data-filter');
    var myfilter = $(filter);
    myfilter[0].reset();
    // Reset Selec2
    if ($('.select2', myfilter).length > 0) {
        $('.select2', myfilter).select2('val', '');
    }
    my_data_table.reload(id);
};

// Row Action
my_data_table.row_action = {
    ajax: function (id, extend_data) {
        var tagid = '#' + id;
        var conf_message = $(tagid).attr('data-confirm');
        var url = $(tagid).attr('data-url');
        var data = {};
        var _token = false;
        var data_token = '';
        var id = '';

        if (typeof extend_data != '') {
            data = extend_data;
        }

        if (typeof conf_message === 'undefined') {
            _token = true;
            conf_message = "Are you sure to delete the data?";
        }

        $.SmartMessageBox({
            title: 'Confirmation!',
            content: conf_message,
            buttons: '[No][Yes]',
            sound: my_data_table.default.notification.sound
        }, function (ButtonPressed) {
            if (ButtonPressed === "Yes") {
                my_global.loading.smart('open');
                if (_token) {
                    data_token = $("#fdelete input[name='_token']").val();
                    id = $("#fdelete input[name='id']").val();
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'id': $('meta[name="csrf-token"]').attr('content'),
                        },
                        // data: data,
                        // cache: false,
                        data: {
                            "_token": data_token,
                            "id": id
                        },
                        dataType: 'json',
                        type: 'DELETE',
                        success: function(res) {
                            // alert(res);
                            // console.log(res);

                            // Do something with the result
                            my_global.loading.smart('close');
                            var content_id = res[3];
                            var alert_content = res[2];
                            var alert_title = res[1];
                            var fist_index = res[0];
                            var _icon = 'fa-warning';
                            var _color = '#77021d';
                            if (fist_index === true) {
                                _icon = 'fa-check';
                                _color = "#659265";

                                $.smallBox({
                                    title: alert_title,
                                    content: alert_content,
                                    color: _color,
                                    iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                    timeout: my_data_table.default.timeout,
                                    sound: my_data_table.default.notification.sound
                                });

                                if (my_validation.is_valid_url(content_id)) {
                                    window.location = content_id;
                                } else {
                                    if (typeof content_id !== 'undefined' && content_id !== '') {
                                        var patt = /^#/;

                                        if (patt.test(content_id)) {
                                            window.location.href = content_id;
                                        } else {
                                            eval('(' + content_id + ')');
                                        }
                                    }
                                }
                            } else {
                                $.smallBox({
                                    title: alert_title,
                                    content: alert_content,
                                    color: _color,
                                    iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                    timeout: my_data_table.default.timeout,
                                    sound: my_data_table.default.notification.sound
                                });
                            }
                        }
                    });

                } else {
                    $.post(url, data, function (res) {
                        my_global.loading.smart('close');
                        var content_id = res[3];
                        var alert_content = res[2];
                        var alert_title = res[1];
                        var fist_index = res[0];
                        var _icon = 'fa-warning';
                        var _color = '#77021d';

                        if (fist_index === true) {
                            _icon = 'fa-check';
                            _color = "#659265";

                            $.smallBox({
                                title: alert_title,
                                content: alert_content,
                                color: _color,
                                iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                timeout: my_data_table.default.timeout,
                                sound: my_data_table.default.notification.sound
                            });

                            if (my_validation.is_valid_url(content_id)) {
                                window.location = content_id;
                            } else {
                                if (typeof content_id !== 'undefined' && content_id !== '') {
                                    var patt = /^#/;

                                    if (patt.test(content_id)) {
                                        window.location.href = content_id;
                                    } else {
                                        eval('(' + content_id + ')');
                                    }
                                }
                            }
                        } else {
                            $.smallBox({
                                title: alert_title,
                                content: alert_content,
                                color: _color,
                                iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                timeout: my_data_table.default.timeout,
                                sound: my_data_table.default.notification.sound
                            });
                        }
                    }, 'json');

                }
            }
            else {
                my_global.force.smart();
            }
        });
    }
};

// Row Action change status
my_data_table.row_action_change = {
    ajax: function (id, extend_data) {
        var tagid = '#' + id;
        var conf_message = $(tagid).attr('data-confirm');
        var url = $(tagid).attr('data-url');
        var data = {};
        var _token = false;
        var data_token = '';
        var id = '';

        if (typeof extend_data != '') {
            data = extend_data;
        }

        if (typeof conf_message === 'undefined') {
            _token = true;
            conf_message = "Are you sure to change the data?";
        }

        $.SmartMessageBox({
            title: 'Confirmation!',
            content: conf_message,
            buttons: '[No][Yes]',
            sound: my_data_table.default.notification.sound
        }, function (ButtonPressed) {
            if (ButtonPressed === "Yes") {
                my_global.loading.smart('open');
                if (_token) {
                    data_token = $("#fdelete input[name='_token']").val();
                    id = $("#fdelete input[name='id']").val();
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'id': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: {
                            "_token": data_token,
                            "id": id
                        },
                        dataType: 'json',
                        type: 'GET',
                        success: function(res) {

                            // Do something with the result
                            my_global.loading.smart('close');
                            var content_id = res[3];
                            var alert_content = res[2];
                            var alert_title = res[1];
                            var fist_index = res[0];
                            var _icon = 'fa-warning';
                            var _color = '#77021d';
                            if (fist_index === true) {
                                _icon = 'fa-check';
                                _color = "#659265";

                                $.smallBox({
                                    title: alert_title,
                                    content: alert_content,
                                    color: _color,
                                    iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                    timeout: my_data_table.default.timeout,
                                    sound: my_data_table.default.notification.sound
                                });

                                if (my_validation.is_valid_url(content_id)) {
                                    window.location = content_id;
                                } else {
                                    if (typeof content_id !== 'undefined' && content_id !== '') {
                                        var patt = /^#/;

                                        if (patt.test(content_id)) {
                                            window.location.href = content_id;
                                        } else {
                                            eval('(' + content_id + ')');
                                        }
                                    }
                                }
                            } else {
                                $.smallBox({
                                    title: alert_title,
                                    content: alert_content,
                                    color: _color,
                                    iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                    timeout: my_data_table.default.timeout,
                                    sound: my_data_table.default.notification.sound
                                });
                            }
                        }
                    });

                }
            }
            else {
                my_global.force.smart();
            }
        });
    }
};

my_data_table.row_detail = function (id) {
    var temid = $('#' + id);
    var data_id = temid.data('id');
    var link = temid.data('source');
    var clone = temid.data("clone");
    var dataType = temid.data("type");

    if (dataType === "undefined") {
        dataType = "json";
    }

    var tr = $("<tr/>");
    var tr_id = 'row_' + data_id;
    tr.attr('id', tr_id);

    var div = $(clone).clone();
    if (!$("#clone_" + data_id).length) {
        div.attr("id", "clone_" + data_id);
    } else {
        div = $("#clone_" + data_id);
    }

    if (!temid.hasClass("open")) {
        temid.addClass("open");
        temid.html('<i class="fa fa-angle-up"></i>');
        var row = temid.closest('tr');
        var len = row.children("td").length;

        var td = $("<td/>");
        td.attr("colspan", len);
        tr.insertAfter(row);

        td.html('Loading...');
        tr.append(td);

        div.slideDown("fast", function () {
            $("html, body").animate({scrollTop: $("#" + id).offset().top}, "fast");
        });

        $.ajax({
            "url": link,
            "type": 'GET',
            "dataType": dataType,
            "data": {},
            beforeSend: function () {
            },
            success: function (data) {
                if (typeof clone !== 'undefined') {
                    td.append(div);
                    tr.append(td);
                } else {
                    td.html(data);
                    tr.append(td);
                }
            }, complete: function () {
                div.slideDown("fast", function () {
                    $("html, body").animate({scrollTop: $("#" + id).offset().top}, "fast");
                });
            }
        });
    } else {
        temid.removeClass("open");
        temid.html('<i class="fa fa-angle-down"></i>');
        if (typeof clone !== 'undefined') {
            div.slideUp("fast", function () {
                div.closest("tr").remove();
            });
        } else {
            $('#' + tr_id).remove();
        }
    }
};

my_data_table.delete_append = function (id, id_table) {

    var $tbl = $(id_table);
    $.SmartMessageBox({
        title: 'Confirmation!',
        content: $('#' + id, $tbl).data('message'),
        buttons: '[No][Yes]',
        sound: my_data_table.default.notification.sound
    }, function (ButtonPressed) {
        if (ButtonPressed === "Yes") {
            $('#' + id, $tbl).closest('tr').remove();

            if ($('.tcontent-list', $tbl).length > 0) {
                my_data_table.append_render($tbl);
            } else {
                $('.tcontent-empty', $tbl).removeClass('hidden');
                $('.tcontent-empty', $tbl).attr('data-status', 'on');
            }
        } else {
            my_global.force.smart();
        }
    });
};

my_data_table.append_render = function ($tempid) {
    // Generate no
    var $no = 1;
    $.each($('.tcontent-no-list', $tempid), function () {
        $(this).html($no);
        $no++;
    });

    // Generate no
    var $no = 1;
    $.each($('.tcontent-action-delete', $tempid), function () {
        $(this).attr('id', $(this).data('id') + $no);
        $no++;
    });
};

my_data_table.append = function (id, callback) {
    var $tempid = $(id);
    var $tclone = $('.tclone', $tempid).children('tr').clone();
    var $tcontent = $('.tcontent', $tempid);

    $tclone.addClass('tcontent-list');

    // Set TD No
    $tclone.find('td.tcontent-no').addClass('tcontent-no-list');

    // Set form
    var $tcontent_empty = $tcontent.children('tr.tcontent-empty');
    if ($tcontent_empty.data('status') === 'on') {
        $tcontent_empty.addClass('hidden');
        $tcontent_empty.attr('data-status', 'off');
    }

    $.each($tclone.find('.clone-form'), function () {
        $(this).attr('name', $(this).data('name'));
        $(this).attr('name', $(this).data('name'));
    });

    $tcontent.append($tclone);

    my_data_table.append_render($tempid);

    if (typeof callback !== 'undefined') {
        eval('(' + callback + ')');
    }
};
