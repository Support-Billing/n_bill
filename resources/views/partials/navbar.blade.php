
        <aside id="left-panel">

            <!-- User info -->
            <div class="login-info">
                <span>
                    <!-- User image size is adjusted inside CSS, it should stay as is -->
                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <img src="img/avatars/male.png" alt="me" /> 
                        <span>{{Auth::user()->username}} </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 
                    
                </span>
            </div>
            <!-- end user info -->

            <!-- NAVIGATION : This navigation is also responsive

            To make this navigation dynamic please make sure to link the node
            (the reference to the nav > ul) after page load. Or the navigation
            will not initialize.
            -->
            <nav>
                <!-- 
                NOTE: Notice the gaps after each icon usage <i></i>..
                Please note that these links work a bit different than
                traditional href="" links. See documentation for details.
                -->
                <?php echo!empty($data_nav) ? $data_nav : ''; ?>
                <!-- 
                <ul>
                    <li class="">
                        <a href="dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
                    </li>
                    <! -- 
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-puzzle-piece"></i> <span class="menu-item-parent">App Views</span></a>
                        <ul>
                            <li>
                                <a href="#"><i class="fa fa-comments"></i> Forum Layout</a>
                                <ul>
                                    <li><a href="ajax/forum.html">General View</a></li>
                                    <li><a href="ajax/forum-topic.html">Topic View</a></li>
                                    <li><a href="ajax/forum-post.html">Post View</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="ajax/profile.html"><i class="fa fa-group"></i> Profile</a>
                            </li>
                            <li>
                                <a href="ajax/timeline.html"><i class="fa fa-clock-o"></i> Timeline</a>
                            </li>
                        </ul>       
                    </li>
                    - ->
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-sitemap "></i> <span class="menu-item-parent">Master Data</span></a>
                        <ul>

                            <li>
                                <a href="#"><i class="fa fa-group"></i> User Management</a>
                                <ul>
                                    <li><a href="employee">Employee</a></li>
                                    <li><a href="worklocation">Work Location</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-cog "></i> <span class="menu-item-parent">Settings</span></a>
                        <ul>

                            <li>
                                <a href="profile"></i> Profile</a>
                            </li>
                            <li>
                                <a href="sysseting">System Setting</a>
                            </li>
                            <li>
                                <a href="menu">Menu</a>
                            </li>
                            <li>
                                <a href="role">Role</a>
                            </li>
                            <li>
                                <a href="user">User</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Report</span></a>
                        <ul>
                            <li>
                                <a href="report1">Report 1</a>
                            </li>
                            <li>
                                <a href="report2">Report 2</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-puzzle-piece"></i> <span class="menu-item-parent">Logs</span></a>
                        <ul>
                            <li>
                                <a href="activity"><i class="fa fa-picture-o"></i> Activity</a>
                            </li>
                            <li>
                                <a href="sistem"><i class="fa fa-group"></i> Sistem</a>
                            </li>
                        </ul>
                    </li>

                    <!--
                    <li class="top-menu-invisible">
                        <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">SmartAdmin Intel</span></a>
                        <ul>
                            <li>
                                <a href="ajax/difver.html"><i class="fa fa-stack-overflow"></i> Different Versions</a>
                            </li>
                            <li>
                                <a href="ajax/applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                            </li>
                            <li>
                                <a href="http://192.241.236.31/smartadmin/BUGTRACK/track_/documentation/index.html" target="_blank"><i class="fa fa-book"></i> Documentation</a>
                            </li>
                            <li>
                                <a href="http://192.241.236.31/smartadmin/BUGTRACK/track_/" target="_blank"><i class="fa fa-bug"></i> Bug Tracker</a>
                            </li>
                            <li>
                                <a href="http://myorange.ca/supportforum/" target="_blank"><i class="fa fa-wechat"></i> SmartAdmin Support</a>
                            </li>
                        </ul>
                    </li>

                    <li class="chat-users top-menu-invisible">
                        <a href="#"><i class="fa fa-lg fa-fw fa-comment-o"><em class="bg-color-pink flash animated">!</em></i> <span class="menu-item-parent">Smart Chat API <sup>beta</sup></span></a>
                        <ul>
                            <li>
                                < !-- DISPLAY USERS ->
                                <div class="display-users">

                                    <input class="form-control chat-user-filter" placeholder="Filter" type="text">
                                    
                                    <a href="#" class="usr" 
                                        data-chat-id="cha1" 
                                        data-chat-fname="Sadi" 
                                        data-chat-lname="Orlaf" 
                                        data-chat-status="busy" 
                                        data-chat-alertmsg="Sadi Orlaf is in a meeting. Please do not disturb!" 
                                        data-chat-alertshow="true" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/5.png' alt='Sadi Orlaf'>
                                                <div class='usr-card-content'>
                                                    <h3>Sadi Orlaf</h3>
                                                    <p>Marketing Executive</p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Sadi Orlaf
                                    </a>
                                  
                                    <a href="#" class="usr" 
                                        data-chat-id="cha2" 
                                        data-chat-fname="Jessica" 
                                        data-chat-lname="Dolof" 
                                        data-chat-status="online" 
                                        data-chat-alertmsg="" 
                                        data-chat-alertshow="false" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/1.png' alt='Jessica Dolof'>
                                                <div class='usr-card-content'>
                                                    <h3>Jessica Dolof</h3>
                                                    <p>Sales Administrator</p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Jessica Dolof
                                    </a>
                                  
                                    <a href="#" class="usr" 
                                        data-chat-id="cha3" 
                                        data-chat-fname="Zekarburg" 
                                        data-chat-lname="Almandalie" 
                                        data-chat-status="online" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/3.png' alt='Zekarburg Almandalie'>
                                                <div class='usr-card-content'>
                                                    <h3>Zekarburg Almandalie</h3>
                                                    <p>Sales Admin</p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Zekarburg Almandalie
                                    </a>
                                 
                                    <a href="#" class="usr" 
                                        data-chat-id="cha4" 
                                        data-chat-fname="Barley" 
                                        data-chat-lname="Krazurkth" 
                                        data-chat-status="away" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/4.png' alt='Barley Krazurkth'>
                                                <div class='usr-card-content'>
                                                    <h3>Barley Krazurkth</h3>
                                                    <p>Sales Director</p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Barley Krazurkth
                                    </a>
                                  
                                    <a href="#" class="usr offline" 
                                        data-chat-id="cha5" 
                                        data-chat-fname="Farhana" 
                                        data-chat-lname="Amrin" 
                                        data-chat-status="incognito" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/female.png' alt='Farhana Amrin'>
                                                <div class='usr-card-content'>
                                                    <h3>Farhana Amrin</h3>
                                                    <p>Support Admin <small><i class='fa fa-music'></i> Playing Beethoven Classics</small></p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Farhana Amrin (offline)
                                    </a>
                                  
                                    <a href="#" class="usr offline" 
                                        data-chat-id="cha6" 
                                        data-chat-fname="Lezley" 
                                        data-chat-lname="Jacob" 
                                        data-chat-status="incognito" 
                                        data-rel="popover-hover" 
                                        data-placement="right" 
                                        data-html="true" 
                                        data-content="
                                            <div class='usr-card'>
                                                <img src='img/avatars/male.png' alt='Lezley Jacob'>
                                                <div class='usr-card-content'>
                                                    <h3>Lezley Jacob</h3>
                                                    <p>Sales Director</p>
                                                </div>
                                            </div>
                                        "> 
                                        <i></i>Lezley Jacob (offline)
                                    </a>
                                    
                                    <a href="ajax/chat.html" class="btn btn-xs btn-default btn-block sa-chat-learnmore-btn">About the API</a>

                                </div>
                                < !-- END DISPLAY USERS - - >
                            </li>
                        </ul>   
                    </li>

                    - ->
                </ul>
                //-->
            </nav>

            
            <!-- <a href="#ajax/difver.html" class="btn btn-primary nav-demo-btn">AngularJS, PHP and .Net Versions</a> -->
            
            

            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

        </aside>