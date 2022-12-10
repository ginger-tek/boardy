<?php

namespace App\Views;

use App\Svc\AuthSvc;

if (@$_SESSION['user']) header('location: ?r=home');

$err = null;

if (@$_POST['username'] && @$_POST['password']) {
  $user = AuthSvc::login($_POST['username'], $_POST['password']);
  if ($user === null) $err = 'User not found';
  elseif ($user === false) $err = 'Incorrect password';
  else {
    $_SESSION['user'] = $user;
    header('location: ?r=home');
  }
}

?>

<h2>Login</h2>
<form method="POST">
  <p><?= $err ?></p>
  <div>
    <input name="username" autocapitalize="false" placeholder="Username" required />
  </div>
  <div>
    <input name="password" type="password" placeholder="Password" required />
  </div>
  <div>
    <button type="submit">Login</button>
  </div>
</form>