<!DOCTYPE html>
<html>
	<head>
		<?php 
			use App\dataBase;
			use App\base;
			use App\region;
			$bs = new base();
			$r = new region();
			$db = new dataBase();
			$default = $db->defaultConnection();
			$con = $db->openConnection($default);

			date_default_timezone_set('America/Sao_Paulo');
			$userName = Request::session()->get('userName'); 
			$userLevel = Request::session()->get('userLevel');
			$userRegion = Request::session()->get('userRegionID');

			$userRegionName = $r->getRegion($con,array($userRegion))[0]['name'];
		?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title> D|Ad Sales Portal - @yield('title') </title>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" 
				integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
		</script>

		<link href="/css/app.css" rel="stylesheet">
		<link href="/css/root.css" rel="stylesheet">
		<link href="/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<script src="/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="/js/base.js"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<!-- David Stutz bootstrap-multiselect -->
		<script src="/bootstrap-select/dist/js/bootstrap-select.js"></script>
		<link rel="stylesheet" href="/bootstrap-select/dist/css/bootstrap-select.css">
		<!-- Include the plugin's CSS and JS:-->
		<style type="text/css">
			.bootstrap-select{
				border-style: solid;
				border-color: #d9d9d9;
				border-width: 1px;
				border-radius: 3px;
			}

			select{
				background: #fafafa !important;
			}
		</style>
		@yield('head')
		
	</head>
	<body>       
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<!-- Image and text -->
			<nav class="navbar navbar-light bg-light">
				<a class="navbar-brand" href="{{ route('home')}}">
					<img src="/portalLogo.png" width="225" height="60">
					<!--<span class="navbar-text"> |ADS - DLA </span>-->
				</a>
			</nav>

			<a class="navbar-brand" ></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<?php $userName = Request::session()->get('userName'); ?>

			@if($userName != null)
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Results </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{ route('resultsResumeGet') }}"> Summary </a>
								@if( ( $userLevel == "SU" ) )
									<a class="dropdown-item" href="{{ route('resultsLATAMGet') }}"> LATAM </a>
								@endif
								<a class="dropdown-item" href="{{ route('resultsMonthlyGet') }}"> Month </a>
								<a class="dropdown-item" href="{{ route('resultsQuarterGet') }}"> Quarter </a>
								<a class="dropdown-item" href="{{ route('resultsShareGet') }}"> Share </a>
								<a class="dropdown-item" href="{{ route('resultsYoYGet') }}"> YoY - Brand </a>
								<a class="dropdown-item" href="{{ route('resultsMonthlyYoYGet') }}"> YoY - Month </a>
							</div>
						</li>
						@if($userLevel == 'SU' || $userLevel == 'L0' || $userLevel == 'L1' || $userLevel == 'L3' || $userLevel == 'L4' )						
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Performance </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{route('executivePerformanceGet')}}"> Individual </a>
								<a class="dropdown-item" href="{{route('corePerformanceGet')}}"> Core </a>
								<a class="dropdown-item" href="{{route('quarterPerformanceGet')}}"> Office </a>
							</div>
						</li>
						@endif
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Dashboards </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{ route('overviewGet') }}"> Overview </a>
								@if($userRegionName == "Brazil")
									<a class="dropdown-item" href="{{ route('dashboardBVGet') }}"> BV </a>
								@endif
							</div>
						</li>

                       <li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Rankings </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">								
	                        				<a class="nav-link" href="{{ route('brandGet') }}"> Brand </a>
								<a class="nav-link" href="{{ route('marketGet') }}"> Market </a>                                
								<a class="nav-link" href="{{ route('churnGet') }}"> Churn </a>
	                         			   	<a class="nav-link" href="{{ route('rankingGet') }}"> Ranking <span class="sr-only">(current)</span></a>
	                         	<a class="nav-link" href="{{route('newGet')}}"> New </a>
	                        </div>
                        </li>

                        @if( ( $userLevel == "SU" ) || $userRegionName == "Brazil")
	                        <li class="nav-item dropdown">
								<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Viewer </a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('baseGet') }}"> CMAPS </a>
									<a class="dropdown-item" href="{{ route('insightsGet') }}"> Insights </a>
								</div>
							</li>
						@endif

					@if( ( $userLevel == "SU" ) )
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> P&R </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{ route('AEGet') }}"> AE </a>
								@if($userLevel == 'SU' || $userLevel == 'L0' || $userLevel == 'L1' )
									<a class="dropdown-item" href="{{ route('VPGet') }}"> Advertisers Adjust </a>
									<a class="dropdown-item" href="{{ route('VPMonthGet') }}"> Month Adjust </a>
									<a class="dropdown-item" href="{{ route('pacingReportGet') }}"> Pacing </a>
								@endif
							</div>
						</li>	
					
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Analytics </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{ route('analyticsPanel') }}"> Panel </a>
								<!--<a class="dropdown-item" href="#"> Insights </a>-->
							</div>
						</li>	
					

						<!--<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Teste Performance </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="{{ route('individualGet')}}"> Individual </a>
								<a class="dropdown-item"> Core </a>
								<a class="dropdown-item"> Office </a>
							</div>
						</li>-->
					@endif		
					</ul>    

					<ul class="navbar-nav mr-right" style="margin-right: 1.5%;">
						<li class="right nav-item dropdown dropleft">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-flip="true" aria-haspopup="true" aria-expanded="false"> Settings </a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<form method="GET" action="{{ route('logoutGet') }}">
								@csrf
									<input type="submit" class="dropdown-item" value="Logout">
								</form>
								<a class="dropdown-item" href="{{ route('relationshipGet') }}"> RelationShip </a>
								@if($userLevel == "SU")
									<a class="dropdown-item" href="{{ route('dataManagementHomeGet') }}"> Data Management </a>
									<a class="dropdown-item" href="{{ route('planningHome') }}"> Planning </a>
								@endif
								
							</div>
						</li>
					</ul>

					<ul class="navbar-nav mr-right" style="margin-right: 3%;">
						<div class="container-fluid" style="height: auto;">
                            <div class="row">
                            	<div class="col">                            		
	                            	<span style="font-size: 10px;"> Bem-vindo {{$userName}}.</span>
	                            </div>
	                        </div>
	                        <div class="row">
                            	<div class="col" style="margin-top: -5px !important;">                            		
	                            	<span style="width: 100%; font-size: 10px; font-weight: bold; padding: 0px;"> Ad Sales Portal | Data Current Throught: </span>
	                            </div>	                            
                        	</div>
                        	
                        	{{ $bs->sources() }}
                        	
                        </div>     
					</ul>    
				</div>
			@else
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-right" style="margin-left: 92.5%;">
						<a class="nav-link" href="{{ route('autenticate') }}" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false"> Login </a>                            
					</ul>
				</div>
			@endif
		</nav>
		<div id="app"></div>
		
		@if(! is_null($userName))
			@yield('content')
		@else
			@yield('contentLogout')
		@endif

		<div id="troll"></div>

		<script type="text/javascript">
            var userName = "<?php echo Request::session()->get('userName') ;?>";
            var userRegion = "<?php echo Request::session()->get('userRegion') ;?>";
            var userEmail= "<?php echo Request::session()->get('userEmail') ;?>";
            var date = "<?php echo date("Y-m-d");?>";
            var hour = "<?php echo date("G:s");?>";
            var url = "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";  ?>";
            var shortUrl = "<?php echo $_SERVER['REQUEST_URI'] ;?>";
            console.log(url);
            console.log(shortUrl);
            var ipV1 = "<?php echo $_SERVER['REMOTE_ADDR'] ;?>";
            ajaxSetup();
            analytics(userName,userRegion,userEmail,date,hour,url,shortUrl,ipV1);
        </script>

		<script type="text/javascript">
			function ShowLoading(e) {
				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading.gif';
				div.innerHTML = "Processing Request...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);
				return true;
				// These 2 lines cancel form submission, so only use if needed.
				//window.event.cancelBubble = true;
				//e.stopPropagation();
			}
		</script>

	</body>

</html>
