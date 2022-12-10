<?php

namespace App\Views;

use App\Svc\PostsSvc;
use App\Mod\PostsMod;
use App\Svc\CommentsSvc;
use App\Mod\CommentsMod;
use App\Ctrl\CommentsCtrl;
use App\Ctrl\PostsCtrl;

if (!@$_GET['id']) {
  header('location: ?r=400');
  exit;
}

if (@$_POST['action']) {
  match ($_POST['action']) {
    'reply' => CommentsCtrl::Post(),
    'edit-post' => PostsCtrl::Put(),
    'edit-comment' => CommentsCtrl::Put(),
    'delete-post' => PostsCtrl::Delete(),
    'delete-comment' => CommentsCtrl::Delete()
  };
}

$post = PostsSvc::read($_GET['id']);

if (!$post) {
  header('location: ?r=404');
  exit;
}

$comments = CommentsSvc::readAllByPost($post->id);

?>
<section>
  <?= PostsMod::renderPost($post) . '<hr>' ?>
  <?= CommentsMod::renderCommentsNested($comments) ?>
</section>