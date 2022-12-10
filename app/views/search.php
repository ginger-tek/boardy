<?php

use App\Svc\SearchSvc;
use App\Mod\TablesMod;

if (@$_POST['query'] && @$_POST['type']) {
  header('location: ?r=search&t=' . $_POST['type'] . '&q=' . $_POST['query']);
  exit;
}

$queryText = @$_GET['q'];
$type = @$_GET['t'];
$fields = null;

if ($queryText && $type) {
  $query = SearchSvc::create();
  switch ($type) {
    case 'posts':
      $query->table = 'v_posts';
      $query->params = [':title' => "%$queryText%", ':details' => "%$queryText%", ':tags' => "%$queryText%"];
      $query->fields = ['id', 'title', 'author', 'authorName', 'numComments', 'created'];
      $fields = [
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
        'created'
      ];
      break;
    case 'comments':
      $query->table = 'v_comments';
      $query->params = [':details' => "%$queryText%"];
      $query->fields = ['id', 'postId', 'postTitle', 'substr(details,0,50) || \'...\' as details', 'author', 'authorName', 'created'];
      $fields = [
        [
          'name' => 'details',
          'format' => fn ($r) => '<a href="?r=post&id=' . $r->postId . '#c' . $r->id . '">' . $r->details . '</a>'
        ],
        [
          'name' => 'authorName',
          'format' => fn ($r) => '<a href="?r=user&id=' . $r->author . '">' . $r->authorName . '</a>'
        ],
        'created'
      ];
      break;
    case 'users':
      $query->table = 'users';
      $query->params = [':username' => "%$queryText%"];
      $query->fields = ['id', 'username', 'created'];
      $fields = [
        [
          'name' => 'username',
          'format' => fn ($r) => '<a href="?r=user&id=' . $r->id . '">' . $r->username . '</a>'
        ],
        'created'
      ];
      break;
  };
  $data = SearchSvc::query($query);
}

?>

<h2>Search</h2>
<section>
  <form method="POST">
    <input name="query" type="search" value="<?= $queryText ?>" placeholder="Search..." />
    <select name="type">
      <option value="posts" <?= $type == 'posts' ? 'selected' : '' ?>>Posts</option>
      <option value="comments" <?= $type == 'comments' ? 'selected' : '' ?>>Comments</option>
      <option value="users" <?= $type == 'users' ? 'selected' : '' ?>>Users</option>
    </select>
    <button type="submit">Submit</button>
  </form>
  <?= TablesMod::render(@$data ?? [], $fields) ?>
</section>