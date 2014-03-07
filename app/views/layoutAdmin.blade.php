<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>indeodiode Admin</title>
	
	<link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	@yield('head')
	<link href={{ asset('css/bootedit.min.css') }} rel="stylesheet"> 

</head>
<body>

	<nav class="navbar navbar-default" role="navigation">
		<div class = "container-fluid">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="navbar-header">
				<a href={{ url('admin') }} class="navbar-brand">ideodiode Admin</a>
			</div>
			<ul class="nav navbar-nav navbar-right navbar-collapse collapse">
				<li class="visible-xs"><a href={{ url('admin') }}>Analytics</a></li>
				<li class="visible-xs"><a href={{ action('PostsController@index') }}>Posts</a></li>
				<li class="visible-xs"><a href={{ action('WorksController@index') }}>Works</a></li>
				<li class="visible-xs"><a href={{ action('TagsController@index') }}>Tags</a></li>
				<li class="visible-xs"><a href={{ action('ImagesController@index') }}>Images</a></li>
				<li><a href="#">Settings</a></li>
				<li id="logoutLink"><a href="{{ action('AuthController@getLogout') }}">Sign Out</a></li>
			</ul>
			{{ Form::open(array('action'=>'PostsController@index', 'class'=>'nav-form navbar-right navbar-collapse collapse','method'=>'GET')) }}
				<div class="form-group has-feedback-nav">
					<span id="searchIcon" class="glyphicon glyphicon-search form-control-feedback-nav"></span>
					<input type="text" class="form-control search-query" placeholder="Search..">
					
				</div>
			{{ Form::close() }}
		</div>
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<div id="sideBar" class="col-sm-2 col-md-2 sidebar collapse">
				<nav class="nav nav-sidebar">

					<li id="analyticsLink"><a href={{ url('admin') }}>Analytics</a></li>
					<li id="postsIndexLink"><a href={{ action('PostsController@index') }}>Posts</a></li>
					<li id="worksIndexLink"><a href={{ action('WorksController@index') }}>Works</a></li>
					<li id="tagsIndexLink"><a href={{ action('TagsController@index') }}>Tags</a></li>
					<li id="imagesIndexLink"><a href={{ action('ImagesController@index') }}>Images</a></li>
				</nav>
			</div>
			<div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main">
				@yield('content')
			</div>
		</div>
	</div>
	
	<script>
		$("document").ready( function(){
			$("#searchIcon").click( function(){
				$(this).parents("form").submit();
			});
		
			pageTitle = $(".page-header>h1").text().toLowerCase();
			var titles = ["analytic", "post", "work", "tag", "login", "image"];
			for (i = 0; i < titles.length; i++) 
			{
				if(pageTitle.indexOf(titles[i]) != -1)
				{
					switch (titles[i])
					{
						case "analytic":	$("#analyticsLink").addClass("active");
							break;
						case "post":		$("#postsIndexLink").addClass("active");
							break;
						case "work":		$("#worksIndexLink").addClass("active");
							break;
						case "tag":			$("#tagsIndexLink").addClass("active");
							break;
						case "login":		$("#logoutLink").addClass("hidden");
							break;
						case "image":		$("#imagesIndexLink").addClass("active");
							break;
					}
				}
			}
		});
		
		$(function() {
			var $window    = $(window),
			topPadding = 20;
			maxPadding = 58;

			$window.scroll(function() {
				if ($window.scrollTop() < maxPadding) {
					$("#sideBar nav").offset({ top: 70, left: 0 });
				}
				else{
					currentPadding = $window.scrollTop()+12;
					$("#sideBar nav").offset({ top: currentPadding, left: 0 });
				}		
			});
		});
		
	</script>
</body>
</html>