<html>

<head>
  <title>BB</title>
  <link rel="stylesheet" href="/bb/themes/default/assets/css/styles.css" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
</head>

<body>
  <header>
    <nav>
      <?php if (@$_SESSION['user']) { ?>
        <a href="?r=home">Home</a>
        <a href="?r=tags">Tags</a>
        <a href="?r=new-post">New Post</a>
        <a href="?r=search">Search</a>
        <?php if ($_SESSION['user']->role == 'admin') { ?>
          <a href="?r=admin">Admin</a>
        <?php } ?>
        <a href="?r=user">Me</a>
        <a href="?r=logout">Logout</a>
      <?php } else { ?>
        <a href="?r=login">Login</a>
        <a href="?r=register">Register</a>
      <?php } ?>
    </nav>
  </header>
  <main>
    <?= $page ?>
  </main>
  <script src="/bb/themes/default/assets/js/scripts.js"></script>
</body>

</html>