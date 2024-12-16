var my_global = my_global || {};

my_global.loading = {
    smart: function (status) {
        if (status === 'close') {
            my_global.force.smart();
        } else {
            $.SmartMessageBox({
                title: 'Loading...',
                content: "Silahkan tunggu, data sedang diproses!",
                buttons: '',
                sound: false
            });
        }
    }
};

my_global.force = {
    smart: function () {
        ExistMsg = 0;
        $("#MsgBoxBack").fadeOut().remove();
//        $('.modal-backdrop').remove();
    }
};

my_global.default = {
    timeout: 4000,
    base_url: {
        assets: ''
    },
    notification: {
        sound: false
    }
};

my_global.go_back = function () {
    window.history.back();
};

my_global.config = {
    base_url: {
        assets: ''
    }
};


my_global.select_all = function (id) {
    var target = $('#' + id).attr('target-selected');
    if ($('#' + id).is(':checked')) {
        $('.' + target).prop('checked', true);
    } else {
        $('.' + target).prop('checked', false);
    }
};


my_global.set_value_selected = function (id) {
    var target = $('#' + id).attr('target-selected');
    var val = $('#' + id).val();
    $('.' + target).val(val);
};

my_global.auto_generate = function (id) {
    var tempid = $('#' + id);
    var target = tempid.attr('data-target');
    if (tempid.is(':checked')) {
        $('#' + target).attr("readonly", true);
        $('#req_' + target).hide();
    } else {
        $('#' + target).attr("readonly", false);
        $('#req_' + target).show();
    }
};

// Row Action
my_global.action = {
    ajax: function (id) {
        var tagid = '#' + id;
        var conf_message = $(tagid).data('confirm');
        var url = $(tagid).data('url');

        if (typeof conf_message === 'undefined') {
            conf_message = "...";
        }

        $.SmartMessageBox({
            title: 'Confirmation!',
            content: conf_message,
            buttons: '[No][Yes]',
            sound: my_global.default.notification.sound
        }, function (ButtonPressed) {
            if (ButtonPressed === "Yes") {
                my_global.loading.smart('open');
                $.post(url, function (res) {
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
                            timeout: my_global.default.timeout,
                            sound: my_global.default.notification.sound
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
                            timeout: my_global.default.timeout,
                            sound: my_global.default.notification.sound
                        });
                    }
                }, 'json');
            }
            else {
                my_global.force.smart();
            }
        });
    }
};


my_global.get_number = {
    autoNumeric: function (a) {
        if (typeof a !== 'undefined') {
            a = a.replace(",00", "");
            a = a.replace(/\./g, "");
            a = a.replace(/,/g, ".");
            return a;
        } else {
            return '';
        }
    },
    NilaiRupiah: function (jumlah, decimal)
    {
        var prefix = '';
        if (jumlah < 0) {
            jumlah = '' + jumlah;
            jumlah = jumlah.replace('-', '');
            prefix = '-';
        }

        var titik = ".";
        var nilai = new String(jumlah);
        var pecah = [];
        while (nilai.length > 3)
        {
            var asd = nilai.substr(nilai.length - 3);
            pecah.unshift(asd);
            nilai = nilai.substr(0, nilai.length - 3);
        }

        if (nilai.length > 0) {
            pecah.unshift(nilai);
        }
        nilai = pecah.join(titik);
        if (typeof decimal === 'undefined') {
            return prefix + nilai + ',00';
        } else {
            return prefix + nilai;
        }
    },
    numeric: function (a) {
        if (typeof a !== 'undefined') {
            a = a.replace(/\,/g, ".");
            return a;
        } else {
            return '';
        }
    }
};

my_global.load_by_ajax = function (id) {
    var objID = $(id);
    var link = objID.data('source');
    var filter = objID.data('filter');
    var data = typeof filter !== 'undefined' ? $(filter).serialize() : {};
    objID.load(link, data);
};