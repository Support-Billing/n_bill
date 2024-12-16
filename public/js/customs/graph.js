/* 
 * By Koala jengke
 * ini JS buat grapik coy
 */

var my_graph = my_graph || {};

my_graph.init = function (f) {
    loadScript(my_global.config.base_url.assets + "/js/plugin/highchart/js/highcharts.js", function () {
        loadScript(my_global.config.base_url.assets + "/js/plugin/highchart/js/modules/exporting.js", function () {
            loadScript(my_global.config.base_url.assets + "/js/plugin/highchart/js/modules/drilldown.js", function () {
                if (typeof f !== 'undefined') {
                    f();
                }
            });
        });
    });
};

my_graph.GraphRender = function (container) {
    var url, data, filter;
    url = $(container).attr('data-source');
    filter = $(container).attr('data-filter');
    var data = {};
    if (typeof filter !== 'undefined') {
        data = $(filter).serialize();
    }
    $(container).html('').addClass('loading-progress');
    $.post(url, data, function (out) {
        $(container).removeClass('loading-progress');
        my_graph.grapik(container, xhr_result());
    }, 'script');
};

my_graph.grapik = function (container, data) {
    /**
     * Grid-light theme for Highcharts JS
     * @author Torstein Honsi
     */

    // Load the fonts
    Highcharts.createElement('link', {
        href: '//fonts.googleapis.com/css?family=Dosis:400,600',
        rel: 'stylesheet',
        type: 'text/css'
    }, null, document.getElementsByTagName('head')[0]);

    Highcharts.theme = {
        colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
            "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
        chart: {
            backgroundColor: null,
            style: {
                fontFamily: "Dosis, sans-serif"
            }
        },
        title: {
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
                textTransform: 'uppercase'
            }
        },
        tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
        },
        legend: {
            itemStyle: {
                fontWeight: 'bold',
                fontSize: '13px'
            }
        },
        xAxis: {
            gridLineWidth: 1,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yAxis: {
            minorTickInterval: 'auto',
            title: {
                style: {
                    textTransform: 'uppercase'
                }
            },
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        plotOptions: {
            candlestick: {
                lineColor: '#404048'
            }
        },
        // General
        background2: '#F0F0EA'

    };

    // Apply the theme
    Highcharts.setOptions(Highcharts.theme);
    
    $(container).highcharts(data);
};

my_graph.reset = function(id) {
    var filter = $(id).attr('data-filter');
    console.log(filter);
    var myfilter = $(filter);
    myfilter[0].reset();
    // Reset Selec2
    if ($('.select2', myfilter).length > 0) {
        $('.select2', myfilter).select2('val', '');
    }
    my_graph.GraphRender(id);
};
