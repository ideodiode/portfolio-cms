<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>indeodiode Admin</title>
	
	<link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
	<link href={{ asset('css/frontend.css') }} rel="stylesheet"> 
	
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	@yield('head')

</head>
<body>
	<div id="wrap">
			<nav class="navbar navbar-default navbar-static-top" role="navigation">
				<div class = "container-fluid">
					<div class = "col-sm-10 col-sm-offset-1">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<div class="navbar-header">
							<a href={{ url('/') }} class="navbar-brand">ideodiode</a>
						</div>
						<ul class="nav navbar-nav navbar-collapse collapse">
							<li><a href={{ url('/') }}>About</a></li>
							<li><a href="{{ action('PostsController@publicIndex') }}">Blog</a></li>
							<li><a href="{{ action('WorksController@publicIndex') }}">Works</a></li>
							<li><a href="{{ url('contact') }}">Contact</a></li>
						</ul>
					</div>
				</div>
			</nav>
		
		
		<div id="content">
			@yield('content')
		</div>
	</div>	
	
	<div id="footer">
		<div class="container">
			<div id="footer-content" class="pull-right">
			<span>&copy 2014 ideodiode</span>
			<a href={{ url('admin') }} class="btn btn-default icon-btn">
				<span class="glyphicon glyphicon-cog"></span>
				<span class="btn-text"> Admin</span>
				<span class="invisible"> Admin</span>
			</a>
			</div>
		</div>
	</div>
	

</body>
</html>