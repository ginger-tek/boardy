<?php

namespace App\Ctrl;

use App\Svc\CommentsSvc;

class CommentsCtrl
{
  static function Post()
  {
    if (@$_POST['postId'] && @$_POST['details']) {
      $comment = CommentsSvc::create((object)[
        'postId' => $_POST['postId'],
        'parentId' => @$_POST['parentId'] ?? null,
        'author' => $_SESSION['user']->id,
        'details' => $_POST['details']
      ]);
      header('location: ?r=post&id=' . $_POST['postId'] . '#c' . $comment->id);
      exit;
    } else {
      echo 'Failed to create comment';
    }
  }

  static function Put()
  {
    if (@$_POST['commentId'] && @$_POST['details']) {
      $comment = CommentsSvc::read($_POST['commentId']);
      $comment->details = $_POST['details'];
      if (CommentsSvc::update($comment)) {
        header('location: ?r=post&id=' . $comment->postId . '#c' . $comment->id);
      } else {
        echo 'Failed to create comment';
      }
    }
  }

  static function Delete()
  {
    if (@$_POST['commentId']) {
      $comment = CommentsSvc::read($_POST['commentId']);
      $comment->isDeleted = 1;
      if (CommentsSvc::update($comment)) {
        header('location: ?r=post&id=' . $comment->postId . '#c' . $comment->id);
        exit;
      } else {
        echo 'Failed to delete comment';
      }
    }
  }
}
