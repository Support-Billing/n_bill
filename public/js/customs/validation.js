var my_validation = my_validation || {};

my_validation.is_valid_url = function (url) {
    var RegExp = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if (RegExp.test(url)) {
        return true;
    } else {
        return false;
    }
};

