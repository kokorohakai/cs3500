<script src="/js/Login.js"></script>
<div class="container" id="login">
	<div class="row">
		<div class="col-sm bg-light" style="padding:30px">
			<h1>Login</h1>
			<form method="post" action="/?s=login" id="regform">
			  <div class="form-group">
			    <label for="email">Email address</label>
			    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
			  </div>
			  <div class="form-group">
			    <label for="password">Password</label>
			    <input type="password" class="form-control error" id="password" placeholder="Input your Password">
			  </div>
			  <small id="errorHelp" class="form-text is-invalid"></small>
			  <br>
			  <button type="submit" class="btn btn-primary">Login</button>
			</form>
		</div>
	</div>
</div>