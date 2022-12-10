<?php

session_start();

spl_autoload_register(fn ($c) => include $c . '.php');

$r = @$_GET['r'];

if (!@$_SESSION['user'] && $r != 'login' && $r != 'register') header('location: ?r=login');

ob_start();
include match ($r) {
  'home', null => 'app/views/home.php',
  'register' => 'app/views/register.php',
  'login' => 'app/views/login.php',
  'logout' => 'app/views/logout.php',
  'new-post' => 'app/views/newPost.php',
  'search' => 'app/views/search.php',
  'post' => 'app/views/post.php',
  'tags' => 'app/views/tags.php',
  'tag' => 'app/views/tag.php',
  'user' => 'app/views/user.php',
  'admin' => 'app/views/admin.php',
  '400' => 'app/views/400.php',
  default => 'app/views/404.php'
};
$page = ob_get_clean();
include 'themes/default/index.php';
