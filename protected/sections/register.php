<script src="/js/Register.js"></script>
<div class="container" id="register">
	<div class="row">
		<div class="col-sm bg-light" style="padding:30px">
			<h1>Register an Account</h1>
			<form method="post" action="/?s=register" id="regform">
			  <div class="form-group">
			    <label for="email">Email address</label>
			    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
			    <small id="emailHelp" class="form-text text-muted">We'll share your email with all of our corportate buddies... eh, that's not true. Ignore that.</small>
			  </div>
			  <div class="form-group">
			    <label for="username">Username</label>
			    <input type="username" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Enter username">
			    <small id="usernameHelp" class="form-text text-muted">What do you suppose we call you? I don't think you want to be called princess, now do you?</small>
			  </div>
			  <div class="form-group">
			    <label for="password">Password</label>
			    <input type="password" class="form-control error" id="password" placeholder="Input your Password">
			    <input type="password" class="form-control" id="passwordVerify" placeholder="Input your Password again">
			    <small id="passwordHelp" class="form-text text-muted">Make it more than 8 letters and good, then don't forget it. you lamer.</small>
			  </div>
			  <div class="form-check">
			    <input type="checkbox" class="form-check-input" id="agree">
			    <label class="form-check-label" for="agree">I agree that I'm not a massive moron and going to do something that gets me banned.</label>
			    <small id="agreeHelp" class="form-text"></small>
			  </div>
			  <br>
			  <button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div>
<div class="container" id="registered" style="display: none;">
	<div class="row">
		<div class="col-sm">
			<div class="card bg-light centered" style="width:338px">
				<div class="card-body">
					<h1 class="card-title">Thank you for registering!</h1>
					<p class="card-text">To continue, please check your e-mail address, and click the link we have sent you.</p>
					<a href="/" class="btn btn-primary">Go home</a>
				</div>
			</div>
		</div>
	</div>
</div>