var my_cookie = my_cookie || {};
/**
 * Create cookie with javascript
 *
 * @param {string} name cookie name
 * @param {string} value cookie value
 * @param {int} days2expire
 * @param {string} path
 * var cookie_name = 'pontikis_net_js_cookie';
 * var cookie_value = 'test_cookie_created_with_javascript';
 * create_cookie(cookie_name, cookie_value, 30, "/");
 * 
 */
my_cookie.create = function (name, value, days2expire, path) {
    var date = new Date();
    date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
//    date.setTime(date.getTime() + parseInt(milisecond));
    var expires = date.toUTCString();
    document.cookie = name + '=' + value + ';' +
            'expires=' + expires + ';' +
            'path=' + path + ';';
};

/**
 * Retrieve cookie with javascript
 *
 * @param {string} name cookie name
 * var cookie_name = 'pontikis_net_js_cookie';
 * var res = retrieve_cookie(cookie_name);
 * if(res) {
 *   alert('Cookie with name "' + cookie_name + '" value is ' + '"' res + '"');
 * } else {
 *   alert('Cookie with name "' + cookie_name + '" does not exist...');
 * }
 * 
 */
my_cookie.retrieve = function (name) {
    var cookie_value = "",
            current_cookie = "",
            name_expr = name + "=",
            all_cookies = document.cookie.split(';'),
            n = all_cookies.length;

    for (var i = 0; i < n; i++) {
        current_cookie = all_cookies[i].trim();
        if (current_cookie.indexOf(name_expr) == 0) {
            cookie_value = current_cookie.substring(name_expr.length, current_cookie.length);
            break;
        }
    }
    return cookie_value;
};

/*
 *  Update Cookies
 var cookie_name = 'pontikis_net_js_cookie';
 var cookie_name = 'test_cookie_updated_with_javascript';
 create_cookie(cookie_name, cookie_value, 60, "/");
 */

/**
 * Delete cookie with javascript
 *
 * @param {string} name cookie name
 * var cookie_name = 'pontikis_net_js_cookie';
 * delete_cookie(cookie_name);
 */
my_cookie.delete = function (name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
};