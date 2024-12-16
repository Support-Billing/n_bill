var my_cronjob = my_clock || {};

my_cronjob.run = function (func, time) {
    func();
    setTimeout('my_cronjob.run(' + func + ', ' + time + ');', time);
};