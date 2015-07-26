<!DOCTYPE html>
<!-- saved from url=(0041)http://localhost:8080/chai-crm/home/index -->
<html lang=""><script id="tinyhippos-injected">if (window.top.ripple) { window.top.ripple("bootstrap").inject(window, document); }</script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Welcome to chai-crm</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="http://localhost:8080/chai-crm/static/images/favicon.ico" type="image/x-icon">

    <link rel="apple-touch-icon" href="http://localhost:8080/chai-crm/home/assets/ico/apple-touch-icon.png">
    <link rel="apple-touch-icon" href="http://localhost:8080/chai-crm/home/assets/ico/apple-touch-icon-72x72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="http://localhost:8080/chai-crm/home/assets/ico/apple-touch-icon-114x114.png" sizes="114x114">
    <link rel="stylesheet" href="/css/omnitech.css" type="text/css">
    <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="/js/globalize/globalize.min.js"></script>
    <script src="/js/underscore/underscore-min.js"></script>
    <script src="/js/moment/moment.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <script src="/js/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

    <script src="/js/DevExpressChartJS/dx.chartjs.js"></script>

<link href="/css/bundle-bundle_bootstrap_head.css" type="text/css" rel="stylesheet" media="screen, projection">
<link href="/css/bundle-bundle_bootstrap_utils_head.css" type="text/css" rel="stylesheet" media="screen, projection">


<link href="/css/bundle-bundle_dataTable_head.css" type="text/css" rel="stylesheet" media="screen, projection">
    <meta name="layout" content="kickstart">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
		<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

    
</head>
    
<body>
<div class="container" style=" max-width: 1170px; padding: 0; background: fff; box-shadow: none;">
    <script>
        omnitechBase = '/chai-crm';
    </script>
    <div class="container" id="header" style="margin-top: 10px; max-width: 1170px; padding: 0;">
    	<div class="server-status" style="background: red">
           DEVELOPMENT SERVER
        </div>
        <div class="clear"></div>
        <div class="logo">
            <a href="http://localhost:8080/chai-crm/">
            	<img src="img/Clinton-Health.png" absolute="true">
            </a>
        </div>
	    <!-- Main menu in one row (e.g., controller entry points -->
        <nav role="navigation" class="navbar navbar-inverse" style="border-radius: 0px;">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- Collection of nav links, forms, and other content for toggling -->
            <div id="navbarCollapse-sticky-wrapper" class="sticky-wrapper" style="height: 50px;"><div id="navbarCollapse" class="collapse navbar-collapse" style="width: 748px;">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="./template_files/template.html"><i class="glyphicon glyphicon-home"></i>Home</a></li>
                        <li>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="http://localhost:8080/chai-crm/home/index#"><i class="glyphicon glyphicon-briefcase"></i>Products <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu multi-level" aria-labelledby="dropdownMenu">
                                <li><a href="http://localhost:8080/chai-crm/product/index">Products</a></li>
                                <li><a href="http://localhost:8080/chai-crm/productGroup/index">Product Groups</a></li>

                            </ul>
                        </li>
                    <li><a href="http://localhost:8080/chai-crm/customer/index"><i class="glyphicon glyphicon-home"></i>Customers</a>
                    </li>
                    
                    <li>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="http://localhost:8080/chai-crm/home/index#">
                            <i class="glyphicon glyphicon-tasks"></i>Tasks <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu multi-level" aria-labelledby="dropdownMenu">
                            <li>
                                <a href="http://localhost:8080/chai-crm/detailerTask/malaria">Malaria Details</a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/detailerTask/index?status=new">Detailer</a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/call/index?status=new">Orders</a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/sale/index?status=complete">Visits</a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/taskSetting/generationDetailer">Generate Detailing Tasks</a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/taskSetting/generationOrder">Generate Calls</a>
                            </li>
                        </ul>

                    </li>

                
                    
                        <li>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="http://localhost:8080/chai-crm/home/index#"><i class="glyphicon glyphicon-dashboard"></i>Reports <b class="caret"></b></a>

                            <ul role="menu" class="dropdown-menu multi-level" aria-labelledby="dropdownMenu">
                                <li><a href="http://localhost:8080/chai-crm/report/index">Reports</a></li>
                                <li><a href="http://localhost:8080/chai-crm/reportGroup/index">Report Groups</a></li>
                            </ul>

                        </li>
                    

                
                    
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="http://localhost:8080/chai-crm/home/index#"><i class="glyphicon glyphicon-wrench"></i>Settings <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu multi-level" aria-labelledby="dropdownMenu">
                                <li><a href="http://localhost:8080/chai-crm/region/index">Regions</a></li>
                                <li><a href="http://localhost:8080/chai-crm/district/index">Districts</a></li>
                                <li>
                                    <a href="http://localhost:8080/chai-crm/subCounty/index">
                                        Subcounties
                                    </a>
                                </li>
                                <li>
                                    <a href="http://localhost:8080/chai-crm/parish/index">
                                        Parishes
                                    </a>
                                </li>
                                <li>
                                    <a href="http://localhost:8080/chai-crm/village/index">Village</a>
                                </li>
                                <li>
                                    <a href="http://localhost:8080/chai-crm/customerSegment/index">Customer Segments</a>
                                </li>
                                <li>
                                    <a href="http://localhost:8080/chai-crm/wholeSaler/index">Whole Salers</a>
                                </li>
                                
                                    <li>
                                        <a href="http://localhost:8080/chai-crm/setting/index">Advanced Settings</a>
                                    </li>
                                
                            </ul>
                        </li>
                    
                </ul>

                
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle glyphicon glyphicon-user" href="http://localhost:8080/chai-crm/home/index#">Users(root) <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu multi-level" aria-labelledby="dropdownMenu">
                            <li>
                                <a href="http://localhost:8080/chai-crm/user/index">
                                    <i class="glyphicon glyphicon-user"></i>Users
                                </a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/territory/index">
                                    <i class="glyphicon glyphicon-globe"></i>
                                    Territories
                                </a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/role/index">
                                    <i class="glyphicon glyphicon-tags"></i>Roles
                                </a>
                            </li>
                            <li>
                                <a href="http://localhost:8080/chai-crm/device/index">
                                    <i class="glyphicon glyphicon-phone"></i>Devices</a>
                            </li>

                            <li>
                                <a href="http://localhost:8080/chai-crm/requestMap/index">
                                    <i class="glyphicon glyphicon-tags"></i>Access Levels
                                </a>
                            </li>

                            <li>
                                <a href="http://localhost:8080/chai-crm/logout/index">
                                    <i class="glyphicon glyphicon-off"></i>Log out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div></div>
        </nav>
	</div>

    <div ng-app="omnitechApp">
        <div class="container" style="max-width: 1170px; padding: 0; margin-bottom: 3px;">
        <!-- Secondary menu in one row (e.g., actions for current controller) -->
        
            <!-- 
        This menu is used to show function that can be triggered on the content (an object or list of objects).
        -->
        </div>

        <div id="Content" class="container">
            <?php echo $content_for_layout; ?>
        </div>
    </div>

    <!-- Enable to overwrite Footer by individual page -->
    
    <div class="container" style="width: 100%; padding: 0;">
        <footer class="footer">
            <div class="container">
                <p>CHAI 2015<br></p>
            </div>
        </footer>
    </div>

    <!-- Included Javascript files and other resources -->
    <script src="/js/jquery.sticky.js" type="text/javascript"></script>
    <script src="/js/utils.js" type="text/javascript"></script>
    <script src="/js/bundle-bundle_bootstrap_defer.js" type="text/javascript"></script>
    <script src="/js/bundle-bundle_dataTable_defer.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.pageableTable').DataTable({
            "pagingType": "full_numbers"
        });
    </script>
</div>

</body></html>