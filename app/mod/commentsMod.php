<?php

namespace App\Mod;

use App\Vendor\Parsedown;

class CommentsMod
{
  static function renderCommentsList(array $comments): string
  {
    if (count($comments) == 0) return '<div>No comments yet</div>';
    return join("\n", array_map(fn ($c) => '<div class="list-item">'
      . (@$c->postTitle ? '<h5>From "' . $c->postTitle . '"</h5>' : '')
      . '<h4><a href="?r=post&id=' . $c->postId . '#c' . $c->id . '">' . ($c->isDeleted ? '[deleted] ' : '') . (strlen($c->details) > 75 ? substr($c->details, 0, 75) . '...' : $c->details) . '</a></h4>
      <div class="author">
        <a href="?r=user&id=' . $c->author . '">' . $c->authorName . '</a> @
        <span class="d t">' . $c->created . '</span>
      </div>
    </div>', $comments));
  }

  static function renderCommentsNested(array $comments): string
  {
    if (count($comments) == 0) return 'No comments yet';
    function render($comments, $pid = 0)
    {
      $str = '';
      for ($i = 0; $i < count($comments); $i++) {
        if ($comments[$i]->parentId == $pid) {
          $comment = $comments[$i];
          $showReply = !$comment->isDeleted;
          $showDelete = $comment->author == $_SESSION['user']->id && !$comment->isDeleted;
          $showEdit = $showDelete;
          $detailsText = $comment->isDeleted ? '[deleted]' : (new Parsedown())->text($comment->details);
          $link = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/bb?r=post&id=' . $comment->postId . '#c' . $comment->id;
          $children = render($comments, $comment->id);
          $str .= '<div class="comment" id="c' . $comment->id . '">
            <div class="body">
              <div class="author">
                <a href="?r=user&id=' . $comment->author . '">' . $comment->authorName . '</a> @ 
                <span class="d t">' . $comment->created . '</span>'
            . ($comment->created != $comment->updated && !$comment->isDeleted ? ' <span title="' . $comment->updated . ' UTC">[edited]</span>' : '')
            . '</div>
              <div class="details">' . $detailsText . '</div>
              <div class="tools">'
            . ($showReply ? '<details>
                  <summary>Reply</summary>
                  <form method="POST">
                    <input hidden name="action" value="reply"/>
                    <input hidden name="postId" value="' . $comment->postId . '"/>
                    <input hidden name="parentId" value="' . $comment->id . '"/>
                    <textarea name="details" placeholder="Write reply here..."></textarea><br>
                    <button type="submit">Submit</button>
                  </form>
                </details>' : '')
            . ($showEdit ? '<details>
                  <summary>Edit</summary>
                  <form method="POST">
                    <input hidden name="action" value="edit-comment"/>
                    <input hidden name="commentId" value="' . $comment->id . '"/>
                    <input hidden name="postId" value="' . $comment->postId . '"/>
                    <input hidden name="parentId" value="' . $comment->id . '"/>
                    <textarea name="details">' . $comment->details . '</textarea><br>
                    <button type="submit">Submit</button>
                  </form>
                </details>' : '')
            . ($showDelete ? '<div>
                  <div class="summary" onclick="deleteComment(' . $comment->id . ')">▶ Delete</div>
                  <form method="POST" id="delete-comment-' . $comment->id . '" hidden>
                    <input hidden name="action" value="delete-comment"/>
                    <input hidden name="commentId" value="' . $comment->id . '"/>
                  </form>
                </div>' : '')
            . '<div>
                  <div class="summary" onclick="navigator.share({title:\'BB\',text:\'' . $comment->postTitle . '\',url:\'' . $link . '\'})">▶ Share</div>
                </div>
              </div>
            </div>'
            . ($children ? '<div class="children">' . $children . '</div>' : '')
            . '</div>' . "\n";
        }
      }
      return $str;
    }
    return '<div>' . count($comments) . ' Comments</div>
    <br>
    <div class="comments">' . render($comments) . '</div>';
  }
}
