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
                <p>Developed by <a href="http://omnitech.co.ug/?q=contact-us-information" target="_blank">OmniTech</a><br></p>
                <p class="pull-right"><a href="http://localhost:8080/chai-crm/home/index#">Back to top</a></p>
            </div>
        </footer>
    </div>
    

    <!-- Enable to insert additional components (e.g., modals, javascript, etc.) by any individual page -->
    
        <!-- Insert a modal dialog for registering (for an open site registering is possible on any page) -->
        <div class="modal fade" id="RegisterModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
	    	<form action="http://localhost:8080/chai-crm/login/register" method="post" class="form-horizontal" name="register_form" id="register_form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h3>Please state your information for registering</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-lg-2 control-label" for="firstname">Firstname</label>
						<div class="col-lg-10">
							<input class="form-control" name="firstname" id="firstname" type="text" placeholder="Firstname" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label" for="lastname">Lastname</label>
						<div class="col-lg-10">
							<input class="form-control" name="lastname" id="lastname" type="text" placeholder="Lastname">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label" for="email">Email</label>
						<div class="col-lg-10">
							<input class="form-control" name="email" id="email" type="text" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label" for="password">Password</label>
						<div class="col-lg-10">
							<input class="form-control" name="password" id="password" type="password" placeholder="Password" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label" for="confirmpasswd">Confirm Password</label>
						<div class="col-lg-10">
							<input class="form-control" name="confirmpasswd" id="confirmpasswd" type="password" placeholder="Confirm Password" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-10 checkbox" for="agreement">
							<input class="col-lg-3" type="checkbox" value="" name="agreement" id="agreement">
							I have read and agree with the Terms of Use.
						</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Register</button>
				</div>
			</form>
		</div>
	</div>
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


<div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">June 2015</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day  old">31</td><td class="day ">1</td><td class="day ">2</td><td class="day ">3</td><td class="day ">4</td><td class="day ">5</td><td class="day ">6</td></tr><tr><td class="day ">7</td><td class="day ">8</td><td class="day ">9</td><td class="day ">10</td><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td></tr><tr><td class="day  active">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td></tr><tr><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td></tr><tr><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td></tr><tr><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td><td class="day  new">8</td><td class="day  new">9</td><td class="day  new">10</td><td class="day  new">11</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2015</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month active">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year active">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">July 2015</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day  old">28</td><td class="day  old">29</td><td class="day  old">30</td><td class="day ">1</td><td class="day ">2</td><td class="day ">3</td><td class="day ">4</td></tr><tr><td class="day ">5</td><td class="day ">6</td><td class="day ">7</td><td class="day ">8</td><td class="day ">9</td><td class="day ">10</td><td class="day ">11</td></tr><tr><td class="day ">12</td><td class="day ">13</td><td class="day  active">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td><td class="day ">18</td></tr><tr><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td><td class="day ">25</td></tr><tr><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td><td class="day  new">1</td></tr><tr><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td><td class="day  new">8</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2015</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month active">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year active">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><script id="hiddenlpsubmitdiv" style="display: none;"></script><script>try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; if (typeof(lastpass_f.lpsubmitorig2)=='object'){ continue;}lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); if (d) {for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ if (typeof(d.innerText) != 'undefined') { d.innerText=i.toString(); } else { d.textContent=i.toString(); } } } d.dispatchEvent(customEvent); }form.lpsubmitorig2(); } } }}catch(e){}</script></body></html>