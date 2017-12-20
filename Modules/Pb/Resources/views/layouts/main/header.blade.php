<header class="site-header">
	<nav class="navbar navbar-site-main">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Menu</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><img src="{{url_sync('assets/pb/images/logo.png')}}" alt=""></a>
			</div>
			<div class="navbar-visible">
				<div class="profile-languages">
					@if(Auth::check())
					<div class="profile dropdown">
						<a href="#" class="profile-image dropdown-toggle" data-toggle="dropdown">
							<span class="image"><img src="{{url_sync('assets/pb/images/icon-user-default.png')}}" alt=""></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<div class="clearfix user-logged">
									<div class="clearfix user-head">
										<div class="clearfix user-info">
											<strong class="user-name">{{ Auth::user()->username }}</strong>
										</div>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li><a class="user-link-profile" href="#">Profile</a></li>
							<li><a class="user-link-my-items" href="#">My items</a></li>
							<li><a class="user-link-logout" href="{{ route('pb.logout') }}">Logout</a></li>
						</ul>
					</div>
					@endif
					<div class="clearfix dropdown change-languages">
						<a href="#" class="current-language dropdown-toggle" data-toggle="dropdown">En</a>
						<ul class="dropdown-menu">
							<li><a class="lang-en-US" href="#">English</a></li>
							<li><a class="lang-vi-VN" href="#">Tiếng Việt</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="exchange-link active"><a href="#">Exchange</a></li>
					<li class="balances-link"><a href="#">Balances</a></li>
					<li class="trade-view-link"><a href="#">Trade View</a></li>
					<li class="my-funds-link"><a href="#">My Funds</a></li>
					@if(!Auth::check())
					<li class="signin-link"><a href="#" data-toggle="modal" data-target="#signinModal">Sign In</a></li>
					<li class="register-link button-type"><a href="#" data-toggle="modal" data-target="#registerModal">Register</a></li>
					@else
					<li class="invisible-link"><a href="#"></a></li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	<nav class="navbar sub-navbar-main">
		<div class="container-fluid">
			<div class="clearfix current-market">
				<ul class="clearfix nav nav-pills">
					<li>
						<div class="btn-group show-markets" role="group" aria-label="Show Markets">
							<button type="button" class="btn btn-default">
								<strong>VND/BTC</strong>
								<span class="btn-group-addon"><span class="caret"></span></span>
							</button>
						</div>
					</li>
				</ul>
				<ul class="clearfix tradestats">
					<li><strong>Latest</strong>: <span class="last">535,000.00</span></li>
					<li><span class="change"><span class="fa fa-arrow-circle-o-up"></span> 2.2%</span></li>
					<li><strong>High</strong>: <span class="high">550,000.00</span></li>
					<li><strong>Low</strong>: <span class="low">445,000.00</span></li>
					<li><strong>24 Hours Vol</strong>: <span class="volume">1,267.72628328 BTC</span></li>
					<li><strong>Avg</strong>: <span class="avg">492,346.64</span></li>
				</ul>
			</div>
			<div id="select-market">
			</div>
		</div>
	</nav>
</header>