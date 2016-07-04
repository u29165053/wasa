<div class="row">
  <div class="col-md-6">
	<form class="form-horizontal ajax-submit" method="POST" action="actions/register.php">
		<legend>Quiero registrarme</legend>
			<div class="errorMsgs" style="display:none"></div>
			<div class="successMsg" style="display:none"></div>
	  <div class="form-group">
	    <label for="usernme" class="col-sm-2 control-label">Username</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="username" placeholder="Nombre de usuario" name="username" />
	    </div>
	  </div> 
	  <div class="form-group">
	    <label for="email" class="col-sm-2 control-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="email" placeholder="Email" name="email" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="password" class="col-sm-2 control-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="password" placeholder="Password" name="password">
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">Registrarse</button>
	    </div>
	  </div>
	</form>

  </div>
  <div class="col-md-1"></div>
  <div class="col-md-4" style="border-left:1px solid #ccc;"></div>
</div>
<!-- <form class="ajax-submit" method="POST" action="actions/register.php">
	<div class="errorMsgs" style="display:none"></div>
	<div class="successMsg" style="display:none"></div>
	<input type="text" name="username" />
	<input type="email" name="email" />
	<input type="password" name="password" />
	<button type="submit" class="btn btn-success">Registrarse</button>
</form> -->