<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
    </div>
    <!--
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5> My Income <span class="txt-color-blue">$47,171</span></h5>
                <div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
                    1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
                </div>
            </li>
            <li class="sparks-info">
                <h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;45%</span></h5>
                <div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
                    110,150,300,130,400,240,220,310,220,300, 270, 210
                </div>
            </li>
            <li class="sparks-info">
                <h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
                <div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
                    110,150,300,130,400,240,220,310,220,300, 270, 210
                </div>
            </li>
        </ul>
    </div>
    -->
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    
    <!-- row -->
    <div class="row dashboard-empty text-center">
 
        <!-- New Customer Last 2 month 
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blue text-left">
                    New Customer 
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs pull-right"
                        id="lastCustomer" data-breadcrumb="home" 
                        onclick="my_form.open(this.id)" data-module="home" 
                        data-url="lastcustomer" data-original-title="New Customer Last 2 month" 
                        rel="tooltip" data-placement="left">
                    <i class="fa fa-sign-in"></i></a>
                    <br /><span class="semi-bold">Last 2 month</span>
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_customers->Total_New}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blue text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-barstacked-color='["#92A2A8", "#4493B1"]' 
                data-sparkline-height="80px">4:5,3:4,5:7,6:3,4:6,6:5,8:2,4:3,6:4,6:2,4:4,7:2,8:5,4:2</div>

            </div>
        </div>
        -->

        <!-- Unsubscribe Last 2 month
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blue text-left">
                    Close Customer
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs pull-right"
                        id="lastCustomer" data-breadcrumb="home" 
                        onclick="my_form.open(this.id)" data-module="home" 
                        data-url="lastcustomer" data-original-title="New Customer Last 2 month" 
                        rel="tooltip" data-placement="left">
                    <i class="fa fa-sign-in"></i></a>
                    <br /><span class="semi-bold">Last 2 month</span>
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_customers->Total_Close}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blue text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-barstacked-color='["#92A2A8", "#4493B1"]' 
                data-sparkline-height="80px">4:5,3:4,5:7,6:3,4:6,6:5,8:2,4:3,6:4,6:2,4:4,7:2,8:5,4:2</div>

            </div>
        </div> -->

        <!-- Active Customer 
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light padding-10">
                <h4 class="txt-color-green text-left">
                    <span class="semi-bold">Active</span> Customer
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs pull-right"
                        id="activeCustomer" data-breadcrumb="home" 
                        onclick="my_form.open(this.id)" data-module="home" 
                        data-url="customeractive" data-original-title="View Active Customer" 
                        rel="tooltip" data-placement="left">
                    <i class="fa fa-sign-in"></i></a>
                    <br /><br />
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_customers->Total_Active}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline" 
                data-sparkline-type="compositeline" 
                data-sparkline-spotradius-top="5" 
                data-sparkline-color-top="#3a6965" 
                data-sparkline-line-width-top="3" 
                data-sparkline-color-bottom="#2b5c59" 
                data-sparkline-spot-color="#2b5c59" 
                data-sparkline-minspot-color-top="#97bfbf" 
                data-sparkline-maxspot-color-top="#c2cccc" 
                data-sparkline-highlightline-color-top="#cce8e4" 
                data-sparkline-highlightspot-color-top="#9dbdb9" 
                data-sparkline-width="96%" 
                data-sparkline-height="78px" 
                data-sparkline-line-val="[6,4,7,8,4,3,2,2,5,6,7,4,1,5,7,9,9,8,7,6]" 
                data-sparkline-bar-val="[4,1,5,7,9,9,8,7,6,6,4,7,8,4,3,2,2,5,6,7]">
                </div> 	
            </div>
        </div>-->

        <!-- Non Active Customer 
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Non Active</span> Customer
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs pull-right"
                        id="nonActiveCustomer" data-breadcrumb="home" 
                        onclick="my_form.open(this.id)" data-module="home" 
                        data-url="customernonactive" data-original-title="View Active Non Customer" 
                        rel="tooltip" data-placement="left">
                    <i class="fa fa-sign-in"></i></a>
                    <br /><br />
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_customers->Total_Non_Active}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blueLight text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-height="80px">
                    4,3,5,7,9,9,8,7,6,6,4,7,8,4
                </div>
            </div>
        </div>-->

    <!-- </div>
    <div class="row dashboard-empty text-center"> -->

        <!-- Priority Customer 
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Priority</span> Customer       
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs pull-right"
                        id="priorityCustomer" data-breadcrumb="home" 
                        onclick="my_form.open(this.id)" data-module="home" 
                        data-url="prioritycustomer" data-original-title="View Priority Customer" 
                        rel="tooltip" data-placement="left">
                    <i class="fa fa-sign-in"></i></a>
                    <br /><br />
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_customers->Total_Prior}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blue text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-barstacked-color='["#92A2A8", "#4493B1"]' 
                data-sparkline-height="80px">4:5,3:4,5:7,6:3,4:6,6:5,8:2,4:3,6:4,6:2,4:4,7:2,8:5,4:2</div>
            </div>
        </div>
        -->

        <!-- Total Project -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blue text-left">
                    <span class="semi-bold">Total </span> Project
                    <a href="#project" class="pull-right"><i class="fa fa-sign-in"></i></a>
                    <br />
                    <!-- <a href="#project?download=all" class="pull-right" style="color:#00ff1f" ><i class="fa fa-file-excel-o" ></i> <b style="font-size:12px">Download<b></a> -->
                    <a href="{{route('download_dashboard', 'all')}}" class="pull-right" style="color:#00ff1f" ><i class="fa fa-file-excel-o" ></i> <b style="font-size:12px">Download<b></a>
                </h4>
                <br>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_Project}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="text-center">
                    <div class="sparkline txt-color-blue display-inline" 
                    data-sparkline-type="pie" 
                    data-sparkline-offset="90" 
                    data-sparkline-piesize="75px">30,20,15,35</div>
                </div>

            </div>
        </div>

        <!-- Active Project -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light padding-10">
                <h4 class="txt-color-green text-left">
                    <span class="semi-bold">Active </span> Project
                    <a href="#project?status=active" class="pull-right"><i class="fa fa-sign-in"></i></a>
                    <br />
                    <a href="{{route('download_dashboard', 'active')}}" class="pull-right" style="color:#00ff1f" ><i class="fa fa-file-excel-o" ></i> <b style="font-size:12px">Download<b></a>
                </h4>
                <br>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_Active_Project}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline" 
                data-sparkline-type="compositeline" 
                data-sparkline-spotradius-top="5" 
                data-sparkline-color-top="#3a6965" 
                data-sparkline-line-width-top="3" 
                data-sparkline-color-bottom="#2b5c59" 
                data-sparkline-spot-color="#2b5c59" 
                data-sparkline-minspot-color-top="#97bfbf" 
                data-sparkline-maxspot-color-top="#c2cccc" 
                data-sparkline-highlightline-color-top="#cce8e4" 
                data-sparkline-highlightspot-color-top="#9dbdb9" 
                data-sparkline-width="96%" 
                data-sparkline-height="78px" 
                data-sparkline-line-val="[6,4,7,8,4,3,2,2,5,6,7,4,1,5,7,9,9,8,7,6]" 
                data-sparkline-bar-val="[4,1,5,7,9,9,8,7,6,6,4,7,8,4,3,2,2,5,6,7]">
                </div> 	
            </div>
        </div>

        <!-- Waiting Project -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Waiting</span> Project
                    <a href="#project?status=waiting" class="pull-right"><i class="fa fa-sign-in"></i></a>
                </h4>
                <br>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_Active_Project_Waiting}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blueLight text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-height="80px">
                    4,3,5,7,9,9,8,7,6,6,4,7,8,4
                </div>
            </div>
        </div>

        <!-- </div>
    <div class="row dashboard-empty text-center"> -->
        <!-- Free Trial -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Free </span> Trial
                    <a href="#project?status=free" class="pull-right"><i class="fa fa-sign-in"></i></a>
                </h4>
                <br>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_Trial}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="sparkline txt-color-blue text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-barstacked-color='["#92A2A8", "#4493B1"]' 
                data-sparkline-height="80px">4:5,3:4,5:7,6:3,4:6,6:5,8:2,4:3,6:4,6:2,4:4,7:2,8:5,4:2</div>
            </div>
        </div>

        <!-- Trial Subscribe -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blue text-left">
                    Trial <span class="semi-bold">Subscribe</span> 
                    <a href="#project?status=trial" class="pull-right"><i class="fa fa-sign-in"></i></a>
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_Trial_Pay}}" data-size="180" data-pie-size="30">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">0</span>
                </div>
                <div class="text-center">
                    <div class="sparkline txt-color-blue display-inline" 
                    data-sparkline-type="pie" 
                    data-sparkline-offset="90" 
                    data-sparkline-piesize="75px">3,5,2</div>
                </div>

            </div>
        </div>

        <!-- Subscribe -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light padding-10">
                <h4 class="txt-color-green text-left">
                    <span class="semi-bold">Subscribe</span> 
                    <a href="#project?status=subscribe" class="pull-right"><i class="fa fa-sign-in"></i></a>
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_projects->Total_On_Pay}}" data-size="180" data-pie-size="50">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">49</span>
                </div>
                <div class="sparkline" 
                data-sparkline-type="compositeline" 
                data-sparkline-spotradius-top="5" 
                data-sparkline-color-top="#3a6965" 
                data-sparkline-line-width-top="3" 
                data-sparkline-color-bottom="#2b5c59" 
                data-sparkline-spot-color="#2b5c59" 
                data-sparkline-minspot-color-top="#97bfbf" 
                data-sparkline-maxspot-color-top="#c2cccc" 
                data-sparkline-highlightline-color-top="#cce8e4" 
                data-sparkline-highlightspot-color-top="#9dbdb9" 
                data-sparkline-width="96%" 
                data-sparkline-height="78px" 
                data-sparkline-line-val="[6,4,7,8,4,3,2,2,5,6,7,4,1,5,7,9,9,8,7,6]" 
                data-sparkline-bar-val="[4,1,5,7,9,9,8,7,6,6,4,7,8,4,3,2,2,5,6,7]">
                </div> 	
            </div>
        </div>

        <!-- Server -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Server</span>
                    <!-- <a href="#server" class="pull-right"><i class="fa fa-sign-in"></i></a> -->
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_servers->total}}" data-size="180" data-pie-size="50">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">49</span>
                </div>
                <div class="sparkline txt-color-blueLight text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="96%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-height="80px">
                    4,3,5,7,9,9,8,7,6,6,4,7,8,4
                </div>
            </div>
        </div>
<!--         
    </div>
    <div class="row dashboard-empty text-center"> -->
        <!-- Latest prefix -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="well well-sm well-light">
                <h4 class="txt-color-blueLight text-left">
                    <span class="semi-bold">Latest</span> Prefix
                    <!-- <a href="#prefixsupplier" class="pull-right"><i class="fa fa-sign-in"></i></a> -->
                </h4>
                <br>
                <div class="easy-pie-chart txt-color-red easyPieChart text-center" data-percent="{{$data_last_prefix}}" data-size="180" data-pie-size="50">
                    <span class="percent percent-sign txt-color-red font-xl semi-bold">49</span>
                </div>
                <div class="sparkline txt-color-blue text-center" 
                data-sparkline-type="bar" 
                data-sparkline-width="100%" 
                data-sparkline-barwidth="11" 
                data-sparkline-barspacing = "5" 
                data-sparkline-barstacked-color='["#92A2A8", "#4493B1"]' 
                data-sparkline-height="80px">4:5,3:4,5:7,6:3,4:6,6:5,8:2,4:3,6:4,6:2,4:4,7:2,8:5,4:2</div>
            </div>
        </div>

    </div>

</section>
<!-- end widget grid -->

<script type="text/javascript">
    /* DO NOT REMOVE : GLOBAL FUNCTIONS!
     *
     * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
     *
     * // activate tooltips
     * $("[rel=tooltip]").tooltip();
     *
     * // activate popovers
     * $("[rel=popover]").popover();
     *
     * // activate popovers with hover states
     * $("[rel=popover-hover]").popover({ trigger: "hover" });
     *
     * // activate inline charts
     * runAllCharts();
     *
     * // setup widgets
     * setup_widgets_desktop();
     *
     * // run form elements
     * runAllForms();
     *
     ********************************
     *
     * pageSetUp() is needed whenever you load a page.
     * It initializes and checks for all basic elements of the page
     * and makes rendering easier.
     *
     */

     var flot_updating_chart, flot_statsChart, flot_multigraph, calendar;

    pageSetUp();
    
    /*
     * PAGE RELATED SCRIPTS
     */

    // pagefunction
    
    var pagefunction = function() {
    
    };
    
    // run pagefunction on load
    pagefunction();
    
    
</script>
