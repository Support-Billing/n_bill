var my_clock = my_clock || {};

my_clock.default = {
    icon : ''
};

my_clock.live = function (id) {
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    d = date.getDate();
    day = date.getDay();
    days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    h = date.getHours();
    if (h < 10)
    {
        h = "0" + h;
    }
    m = date.getMinutes();
    if (m < 10)
    {
        m = "0" + m;
    }
    s = date.getSeconds();
    if (s < 10)
    {
        s = "0" + s;
    }
    result = my_clock.default.icon + ' ' + days[day] + ', ' + ' ' + d + ' ' + months[month] + ' ' + year + ' ' + h + ':' + m + ':' + s;
    document.getElementById(id).innerHTML = result;
    setTimeout('my_clock.live("' + id + '");', '1000');
    return true;
};