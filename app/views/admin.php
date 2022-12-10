<?php

namespace App\Views;

use App\Svc\JobsSvc;
use App\Mod\TablesMod;
use App\Svc\PostsSvc;
use App\Svc\UsersSvc;

if (@$_POST['action']) {
  $log = match ($_POST['action']) {
    'purge-deleted' => JobsSvc::purgeDeleted(@$_POST['period']),
    'purge-abandoned' => JobsSvc::purgeAbandoned()
  };
}

$postsFields = [
  [
    'name' => 'title',
    'format' => fn ($r) => '<a href="?r=post&id=' . $r->id . '">' . $r->title . '</a>'
  ],
  [
    'name' => 'authorName',
    'format' => fn ($r) => '<a href="?r=user&id=' . $r->author . '">' . $r->authorName . '</a>'
  ],
  [
    'name' => 'numComments',
    'label' => '# of Comments'
  ],
  [
    'name' => 'tags',
    'format' => fn ($r) => join('', array_map(fn ($t) => '<a href="?r=tag&t=' . $t . '">#' . $t . '</a>', explode(',', $r->tags)))
  ],
  [
    'name' => 'isDeleted',
    'label' => 'Marked For Deletion',
    'format' => fn ($r) => $r->isDeleted ? 'Yes' : 'No'
  ],
  'created',
  'updated'
];

$usersFields = [
  [
    'name' => 'username',
    'format' => fn ($r) => '<a href="?r=user&id=' . $r->id . '">' . $r->username . '</a>'
  ],
  'role',
  [
    'name' => 'isDeleted',
    'label' => 'Marked For Deletion',
    'format' => fn ($r) => $r->isDeleted ? 'Yes' : 'No'
  ],
  'created',
  'updated'
];

?>

<h2>Admin</h2>
<section>
  <h3>Last 10 Posts</h3>
  <?= TablesMod::render(PostsSvc::readAll(10), $postsFields) ?>
</section>
<section>
  <h3>Users</h3>
  <?= TablesMod::render(UsersSvc::readAll(), $usersFields) ?>
</section>
<section>
  <h3>Jobs</h3>
  <form method="POST">
    <select name="action" required>
      <option value="purge-deleted">Purge Deleted</option>
      <option value="purge-abandoned">Purge Abandoned</option>
    </select>
    <select name="period">
      <option value="">Now</option>
      <option value="PT1H">1 hour ago</option>
      <option value="P1D">1 day ago</option>
    </select>
    <button type="submit">Run</button>
  </form>
  <h4>Std Log</h4>
  <textarea rows="8" readonly><?= @$log ?></textarea>
</section>