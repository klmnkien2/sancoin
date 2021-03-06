<div class="modal modal-account fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="Sign In">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Sign In</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('pb.postLogin')}}" method="POST">
					<div class="clearfix form-vertical">
						{{ csrf_field() }}
						<div class="form-group">
							<span class="error"></span>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="username" placeholder="Username">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="password" placeholder="Password">
						</div>
						<button id="pg-login-submit" type="submit" class="btn btn-flat-green"><span class="btn-inner">Sign In</span></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				Not registered? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#registerModal">Register</a> or <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#resetPasswordModal">Reset Password</a>
			</div>
		</div>
	</div>
</div>