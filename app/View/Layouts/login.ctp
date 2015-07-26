
<!-- saved from url=(0044)http://23.239.27.196:8080/web-crm/login/auth -->
<html><script id="tinyhippos-injected">if (window.top.ripple) { window.top.ripple("bootstrap").inject(window, document); }</script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Login</title>
    <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<link href="/css/jquery-ui-1.10.3.custom.css" type="text/css" rel="stylesheet" media="screen, projection">
<script src="/js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<link href="/css/bundle-bundle_bootstrap_head.css" type="text/css" rel="stylesheet" media="screen, projection">
<link href="/css/bundle-bundle_bootstrap_utils_head.css" type="text/css" rel="stylesheet" media="screen, projection">



    <link rel="stylesheet" href="/css/omnitech.css" <="" head="">

</head><body>
<div class=" col-lg-12 header-wrapper">
    <div class="col-lg-12 col-lg-offset-5" style="top: 30px;">
       <h3 style="color: #ffffff;font-weight: bold;">CHAI</h3>
    </div>
</div>

<div class="col-lg-12 col-lg-offset-4" style="padding: 0px;top:40px;">

    <div class="col-lg-4" style="background: none repeat scroll 0 0 #F7F7F4;border: 1px solid #EAEAEA;padding: 40px;">
        <div class="col-lg-12" style="height: 10%;">
            <?php
            echo $this->Session->flash('auth');
            echo $this->Session->flash();
            ?>
        </div>

        <div class="col-lg-12" style="padding: 0px;">

            <div class="col-lg-12" style="padding: 10px;">

                <form role="form" id="loginForm" action="/users/login" method="POST" autocomplete="off" class="form-horizontal" _lpchecked="1">

                    <div class="input-group input-group-lg" style="padding: 10px;">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>

                        <input type="text" name="data[User][username]" id="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QsPDhss3LcOZQAAAU5JREFUOMvdkzFLA0EQhd/bO7iIYmklaCUopLAQA6KNaawt9BeIgnUwLHPJRchfEBR7CyGWgiDY2SlIQBT/gDaCoGDudiy8SLwkBiwz1c7y+GZ25i0wnFEqlSZFZKGdi8iiiOR7aU32QkR2c7ncPcljAARAkgckb8IwrGf1fg/oJ8lRAHkR2VDVmOQ8AKjqY1bMHgCGYXhFchnAg6omJGcBXEZRtNoXYK2dMsaMt1qtD9/3p40x5yS9tHICYF1Vn0mOxXH8Uq/Xb389wff9PQDbQRB0t/QNOiPZ1h4B2MoO0fxnYz8dOOcOVbWhqq8kJzzPa3RAXZIkawCenHMjJN/+GiIqlcoFgKKq3pEMAMwAuCa5VK1W3SAfbAIopum+cy5KzwXn3M5AI6XVYlVt1mq1U8/zTlS1CeC9j2+6o1wuz1lrVzpWXLDWTg3pz/0CQnd2Jos49xUAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
                    </div>

                    <div class="input-group input-group-lg" style="padding: 10px;">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="data[User][password]" id="password" class="form-control" placeholder="Password" required="" autocomplete="off" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QsPDhss3LcOZQAAAU5JREFUOMvdkzFLA0EQhd/bO7iIYmklaCUopLAQA6KNaawt9BeIgnUwLHPJRchfEBR7CyGWgiDY2SlIQBT/gDaCoGDudiy8SLwkBiwz1c7y+GZ25i0wnFEqlSZFZKGdi8iiiOR7aU32QkR2c7ncPcljAARAkgckb8IwrGf1fg/oJ8lRAHkR2VDVmOQ8AKjqY1bMHgCGYXhFchnAg6omJGcBXEZRtNoXYK2dMsaMt1qtD9/3p40x5yS9tHICYF1Vn0mOxXH8Uq/Xb389wff9PQDbQRB0t/QNOiPZ1h4B2MoO0fxnYz8dOOcOVbWhqq8kJzzPa3RAXZIkawCenHMjJN/+GiIqlcoFgKKq3pEMAMwAuCa5VK1W3SAfbAIopum+cy5KzwXn3M5AI6XVYlVt1mq1U8/zTlS1CeC9j2+6o1wuz1lrVzpWXLDWTg3pz/0CQnd2Jos49xUAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
                    </div>

                    <div class="form-group">
                        <div class="col-xs-10" style="margin-left: 11px;">
                            <div class="checkbox">
                                <input type="checkbox" class="chk" name="_spring_security_remember_me" id="remember_me">
                                <label for="remember_me">Remember me</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">

                        <input type="submit" id="submit" value="Login" class="btn btn-lg btn-primary btn-block">
                    </div>

                </form>

            </div>
            
        </div>
    </div>

</div>
<div class="col-lg-12" style="padding: 0px;margin: 0px;">
    <div class="container" style="width: 100%; padding: 0;">
    <footer class="footer">
        <div class="container">
            <p>Developed by <a href="http://omnitech.co.ug/?q=contact-us-information" target="_blank">OmniTech</a><br></p>
            <p class="pull-right"><a href="http://23.239.27.196:8080/web-crm/login/auth#">Back to top</a></p>
        </div>
    </footer>
</div>
</div>

<script type="text/javascript">
	<!--
	(function() {
		document.forms['loginForm'].elements['j_username'].focus();
	})();
	// -->
</script>

<script src="/js/bundle-bundle_bootstrap_defer.js" type="text/javascript"></script>

<script src="/js/bundle-bundle_bootstrap_utils_defer.js" type="text/javascript"></script>





<script id="hiddenlpsubmitdiv" style="display: none;"></script><script>try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; if (typeof(lastpass_f.lpsubmitorig2)=='object'){ continue;}lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); if (d) {for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ if (typeof(d.innerText) != 'undefined') { d.innerText=i.toString(); } else { d.textContent=i.toString(); } } } d.dispatchEvent(customEvent); }form.lpsubmitorig2(); } } }}catch(e){}</script></body></html>