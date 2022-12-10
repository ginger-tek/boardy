<?php

namespace App\Views;

use App\Svc\AuthSvc;

if (@$_SESSION['user']) header('location: ?r=home');

$err = null;

if (@$_POST['username'] && @$_POST['password']) {
  $user = AuthSvc::register($_POST['username'], $_POST['password']);
  if ($user == false) $err = 'Username already taken';
  elseif ($user == null) $err = 'Failed to register new user';
  else header('location: ?=login');
}

?>

<h2>Register</h2>
<form method="POST">
  <p><?= $err ?></p>
  <div>
    <input name="username" autocapitalize="false" placeholder="Username" required />
  </div>
  <div>
    <input name="password" type="password" placeholder="Password" required />
  </div>
  <div>
    <button type="submit">Register</button>
  </div>
</form>