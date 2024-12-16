var my_form = my_form || {};

my_form.default = {
    timeout: 4000,
    base_url: {
        assets: ''
    },
    validation: {
        message_postition: 'after',
        prefix_input: 'input_',
        prefix_id: 'error_',
        icon: '<i class="fa fa-warning"></i>'
    },
    notification: {
        sound: false
    }
};

my_form.init = function () {
    loadScript(my_global.config.base_url.assets + "/js/plugin/jquery-form/jquery-form.js");
};

my_form.go_back = function () {
    $('#hiden_nav').remove();
    my_global.go_back();
};

my_form.open = function (id) {
    var tagid = '#' + id;
    var module = $(tagid).attr('data-module');
    var url = $(tagid).attr('data-url');
    var title = $(tagid).attr('data-breadcrumb');
    window.location.href = '#' + url;

    if (typeof title === 'undefined') {
        title = '';
    }

    var hiden_nav = '<ul id="hiden_nav">';
    hiden_nav += '<li style="display:none;">';
    hiden_nav += '<a href="' + url + '" title="' + title + '"><span class="menu-item-parent">' + title + '</span> </a>';
    hiden_nav += '</li>';
    hiden_nav += '</ul>';

    $('#hiden_nav').remove();
    $('a[href="' + module + '"]').after(hiden_nav);
};

my_form.reset = function (id) {
    var myform = $(id);
    myform[0].reset();
    $(id + ' .form-control').removeClass('invalid').removeClass('valid');
    $($('.select2', myform)).removeClass('invalid').removeClass('valid');
    // Reset Selec2
    if ($('.select2', myform).length > 0) {
        $('.select2', myform).select2('val', '');
    }
};

my_form.submit = function (form) {
    var conf_message = $(form).attr('data-confirm');
    var conf_tinymce = $(form).attr('data-tinymce');

    if (typeof conf_message === 'undefined') {
        conf_message = "Are you sure to save the data?";
    }

    $.SmartMessageBox({
        title: 'Confirmation!',
        content: conf_message,
        buttons: '[No][Yes]',
        sound: my_data_table.default.notification.sound
    }, function (ButtonPressed) {
        if (ButtonPressed === "Yes") {

            my_global.loading.smart('open');

            if (typeof conf_tinymce !== 'undefined' && conf_tinymce === 'true') {
                tinyMCE.triggerSave();
            }

            // Cleansing Error
            $(form + ' .ws-error').remove();
            $(form + ' .validation_message').remove();

            $(form).ajaxSubmit({
                beforeSubmit: function (a, f, o) {
                    o.dataType = 'json';
                },
                success: function (res) {
                    my_global.loading.smart('close');
                    var content_id = res[3];
                    var alert_content = res[2];
                    var alert_title = res[1];
                    var fist_index = res[0];
                    var style_message = res[4];

                    if (typeof fist_index === 'boolean' || fist_index === 'error') {
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
                                timeout: my_form.default.timeout,
                                sound: my_data_table.default.notification.sound
                            }, function () {
                                
                               // if (my_validation.is_valid_url(content_id)) {
                               //     window.location = content_id;
                               // } else {
                               //     if (typeof content_id !== 'undefined' && content_id !== '') {
                               //         var patt = /^#/;
                               //         if (patt.test(content_id)) {
                               //             window.location.href = content_id;
                               //         }
                               //     }
                               // }
                                
                            });

                            // Runing function
                            if (!my_validation.is_valid_url(content_id)) {
                                if (typeof content_id !== 'undefined' && content_id !== '') {
                                    var patt = /^#/;
                                    if (!patt.test(content_id)) {
                                        eval('(' + content_id + ')');
                                    }
                                }
                            } else {
                                if (my_validation.is_valid_url(content_id)) {
                                    window.location = content_id;
                                } else {
                                    if (typeof content_id !== 'undefined' && content_id !== '') {
                                        var patt = /^#/;
                                        if (patt.test(content_id)) {
                                            window.location.href = content_id;
                                        }
                                    }
                                }
                            }
                        } else if (fist_index === false) {
                            if (typeof style_message !== 'undefined') {
                                $.smallBox({
                                    title: alert_title,
                                    content: alert_content,
                                    color: _color,
                                    iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                    timeout: my_form.default.timeout,
                                    sound: my_data_table.default.notification.sound
                                });
                            } else {
                                if (typeof alert_content === 'object') {
                                    $.smallBox({
                                        title: alert_title,
                                        content: 'Please check form validation.',
                                        color: _color,
                                        iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                        timeout: my_form.default.timeout,
                                        sound: my_data_table.default.notification.sound
                                    });
                                    console.log(form)
                                    // Cleansing Error
                                    $(form + ' .ws-error').remove();
                                    $(form + ' .form-control').removeClass('invalid').addClass('valid');
                                    $(form + ' .select2-container').removeClass('invalid').addClass('valid');

                                    $.each(alert_content, function (form_name, form_value) {
                                        var msg_validation = '<span id="' + my_form.default.validation.prefix_id + '' + form_name + '" class="ws-error text-error help-block">' + form_value + '</span>';
                                        // $('#'+my_form.default.validation.prefix_input+form_name).removeClass('invalid').addClass('valid');
                                        if ($('#' + my_form.default.validation.prefix_id + '' + form_name).length > 0) {
                                            $(form + ' #' + my_form.default.validation.prefix_id + '' + form_name).html(msg_validation);
                                        } else {
                                            if (my_form.default.validation.message_postition === 'before') {
                                                $(form + ' [name="' + form_name + '"]').before(msg_validation);
                                            } else {
                                                $(form + ' [name="' + form_name + '"]').after(msg_validation);
                                            }
                                        }
                                        console.log(my_form.default.validation.prefix_input+form_name);
                                        if(my_form.default.validation.prefix_input+form_name == 'input_serverProtocol'){
                                            $('#s2id_input_serverProtocol').removeClass('valid').addClass('invalid');
                                        }
                                        if(my_form.default.validation.prefix_input+form_name == 'input_serverType'){
                                            $('#s2id_input_serverType').removeClass('valid').addClass('invalid');
                                        }
                                        
                                        $('#'+my_form.default.validation.prefix_input+form_name).removeClass('valid').addClass('invalid');
                                            

                                    });
                                } else {
                                    $(form).children('div.validation_message').remove();
                                    var msg_validation = '<div class="validation_message alert alert-danger fade in"> <button class="close" data-dismiss="alert"> × </button>';
                                    msg_validation += '<strong>' + alert_title + '</strong>';
                                    msg_validation += alert_content;
                                    msg_validation += '</div>';
                                    $(form).prepend(msg_validation);
                                }

                                // Runing function
                                if (!my_validation.is_valid_url(content_id)) {
                                    if (typeof content_id !== 'undefined' && content_id !== '') {
                                        var patt = /^#/;
                                        if (!patt.test(content_id)) {
                                            eval('(' + content_id + ')');
                                        }
                                    }
                                }
                            }
                        } else {
                            $.smallBox({
                                title: alert_title,
                                content: alert_content,
                                color: _color,
                                iconSmall: "fa " + _icon + " fa-2x fadeInRight animated",
                                timeout: my_form.default.timeout,
                                sound: my_data_table.default.notification.sound
                            });
                        }
                    } else {
                        $.SmartMessageBox({
                            title: 'Konfirmasi!',
                            content: conf_message,
                            buttons: '[No][Yes]',
                            sound: my_data_table.default.notification.sound
                        }, function (ButtonPressed) {
                            if (ButtonPressed === "Yes") {
                                $(content_id).val(1);
                                my_form.submit(form);
                            } else {
                                $(content_id).val(0);
                                my_global.force.smart();
                            }
                        });
                    }
                }
            });
        }
        else {
            my_global.force.smart();
        }
    });
};

my_form.ajax = {};

my_form.ajax.options = function (id, type, extend_data, func) {
    var tempid = '#' + id;
    var target = $(tempid).attr('target-options');
    var tempid_taget = '#' + target;
    var link = $(tempid_taget).attr('data-source');
    var data = {
        key: $(tempid).val(),
        extend: {}
    };

    if (typeof extend_data !== 'undefined') {
        if (typeof extend_data === 'object') {
            data.extend = extend_data;
        } else {
            data.key = extend_data;
        }
    }

    var selected = $(tempid_taget).attr('data-selected');

    $.post(link, data, function (out) {
        $(tempid_taget).html(my_form.generate.options(out, typeof selected !== 'undefined' ? selected : ''));
        if (type === 'multiple-select') {
            $(tempid_taget).multipleSelect("refresh");
        } else if (type === 'select2') {
            $(tempid_taget).select2("val", selected);
        }

        if (typeof func !== 'undefined') {
            func();
        }
    }, 'json');
};


my_form.generate = {};

my_form.generate.options = function (data, selected) {
    
    var split_selected = typeof selected !== 'undefined' ? selected.split(',') : '';

    var options = '';
    var text_selected;
    $.each(data, function (key, val) {
        text_selected = '';
        if ($.inArray(key, split_selected) !== -1) {
            text_selected = ' selected="selected" ';
        }

        options += '<option value="' + key + '" ' + text_selected + '>' + val + '</option>';
    });
    return options;
};

my_form.do_print = function (id) {
    var tempid = $('#' + id);
    var link = tempid.data('link');
    var formid = $(tempid.data('form'));
    var old_action = formid.attr('action');
    var old_method = formid.attr('method');
    var old_target = formid.attr('target');

    formid.attr('action', link);
    formid.attr('method', 'POST');
    formid.attr('target', '_blank');
    formid.submit();

    // Reset setting
    formid.attr('action', (typeof old_action !== 'undefined') ? old_action : '');
    formid.attr('method', (typeof old_method !== 'undefined') ? old_method : '');
    formid.attr('target', (typeof old_target !== 'undefined') ? old_target : '');
};

my_form.auto_generate_id = function (id_check, id_text) {
    var bool_check = $('#' + id_check).prop('checked');
    $('#' + id_text).prop('disabled', bool_check);
    $("#" + id_text).val('');
};                      
                    
my_form.logout = function (id) {
    $.SmartMessageBox({
        title: "<i class='fa fa-sign-out txt-color-orangeDark'></i> Logout <span class='txt-color-orangeDark'><strong>"+$("#show-shortcut").text()+"</strong></span>",
        content: 'You can improve your security further after logging out by closing this opened browser',
        buttons: '[No][Yes]',
        sound: my_data_table.default.notification.sound
    }, function (ButtonPressed) {
        if (ButtonPressed === "Yes") {
            document.getElementById(id).submit();
        }
    });
};