@extends('layoutAdmin')

@section('content')

	<div class="row" style="margin-top:20px">
		<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			
			<form action="{{ action('AuthController@postLogin') }}" method="post" role="form">
			{{ Form::token() }}
				<div class="page-header">
					<h1>Login <small>Admin Console</small></h1>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-body">
						@if ($errors->has('message'))
							<div class="alert alert-danger">{{ $errors->first('message', ':message') }}</div>
						@endif
						
						<div class="form-group">
							<label for="email">Email:</label>
							<input type="text" name="email" class="form-control" value="{{ Input::old('email') }}" placeholder="Email" />
						</div>
						
						<div class="form-group">
							<label for="password">Password:</label>
							<input type="password" name="password" class="form-control" placeholder="Password" />
						</div>

						<div class="form-group">					
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-info btn-group">
									<span class="glyphicon glyphicon-unchecked"></span>
									<input type="checkbox" name="remember" id="remember"> Remember Me
								</label>
							</div>
						</div>
						
						<button type="submit" class="btn btn-success btn-block">Log In!</button>
					</div>
				</div>
			</form>
			
		</div>
	</div>
	
	<script>
	$(":input[type=checkbox]").change(function () {
		$(this).siblings("span").toggleClass("glyphicon-unchecked glyphicon-check");
	});
	</script>

@stop