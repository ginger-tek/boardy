<?php

namespace App\Views;

use App\Svc\UsersSvc;
use App\Mod\PostsMod;
use App\Svc\PostsSvc;
use App\Mod\CommentsMod;
use App\Svc\AuthSvc;
use App\Svc\CommentsSvc;

$userId = @$_GET['id'] ?? $_SESSION['user']->id;

if (@$_POST['action']) {
  match ($_POST['action']) {
    'reset-password' => AuthSvc::resetPassword($userId, $_POST['new'])
  };
}

$user = UsersSvc::read($userId);

if (!$user) {
  header('location: ?r=404');
  exit;
}

$posts = PostsSvc::readAllByUser($userId);
$comments = CommentsSvc::readAllByUser($userId);

?>

<h2><?= $user->username ?></h2>
<p>Member since <span class="d"><?= $user->created ?></span></p>
<section>
  <h3>Posts</h3>
  <?= PostsMod::renderPostsList($posts) ?>
</section>
<section>
  <h3>Comments</h3>
  <?= CommentsMod::renderCommentsList($comments) ?>
</section>
<?php if ($_SESSION['user']->id == $userId) { ?>
  <section>
    <h3>Password Reset</h3>
    <form method="POST">
      <input name="action" value="reset-password" hidden />
      <div>
        <input name="password" id="old" type="password" placeholder="New Password" />
      </div>
      <div>
        <input name="password" id="new" type="password" oninput="document.querySelector('#old').value != this.value ? this.classList.add('invalid') : this.classList.remove('invalid')" placeholder="Confirm New Password" />
      </div>
      <button type="submit">Submit</button>
    </form>
  </section>
<?php } ?>