<?php

namespace App\Ctrl;

use App\Svc\PostsSvc;

class PostsCtrl
{
  static function Post()
  {
    if (@$_POST['title'] && @$_POST['tags']) {
      $post = PostsSvc::create((object)[
        'author' => $_SESSION['user']->id,
        'title' => $_POST['title'],
        'details' => @$_POST['details'],
        'tags' => join(',', array_slice(explode(',', $_POST['tags']), 0, 5))
      ]);
      header('location: ?r=post&id=' . $post->id);
      exit;
    } else {
      echo 'Failed to create post';
    }
  }

  static function Put()
  {
    if (@$_POST['postId'] && @$_POST['details'] && @$_POST['tags']) {
      $post = PostsSvc::read($_POST['postId']);
      $post->details = $_POST['details'];
      $post->tags = join(',', array_slice(explode(',', $_POST['tags']), 0, 5));
      if (PostsSvc::update($post)) {
        header('location: ?r=post&id=' . $post->id);
        exit;
      } else {
        echo 'Failed to update post';
      }
    }
  }

  static function Delete()
  {
    if (@$_POST['postId']) {
      $post = PostsSvc::read($_POST['postId']);
      $post->isDeleted = 1;
      if (PostsSvc::update($post)) {
        header('location: ?r=home');
        exit;
      } else {
        echo 'Failed to delete post';
      }
    }
  }
}
