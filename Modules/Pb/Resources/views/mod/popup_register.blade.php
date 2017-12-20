<div class="modal modal-account fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="Register">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Join Sancoin</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('pb.register')}}" method="POST">
					<div class="clearfix form-vertical">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <span class="error"></span>
                        </div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Username" name="username">
						</div>
						<div class="form-group">
							<input type="email" class="form-control" placeholder="Your Email" name="email">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Password" name="password">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Repeat Password" name="password_confirmation">
						</div>
						<button id="pg-reg-submit"  type="submit" class="btn btn-flat-green"><span class="btn-inner">Register</span></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				Already a member? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signinModal">Sign in</a>
			</div>
		</div>
	</div>
</div>