<?php

namespace App\Mod;

use App\Vendor\Parsedown;

class PostsMod
{
  static function renderPostsList(array $posts): string
  {
    if (count($posts) == 0) return '<div>No posts yet</div>';
    return join("\n", array_map(fn ($p) => '<div class="list-item">
      <h4><a href="?r=post&id=' . $p->id . '">' . ($p->isDeleted ? '[deleted] ' : '') . $p->title . '</a></h4>
      <div class="author">
        <a href="?r=user&id=' . $p->author . '">' . $p->authorName . '</a> @ 
        <span class="d t">' . $p->created . '</span> |
        <span>' . $p->numComments . ' comments</span> | 
        <span>tags: ' . join('', array_map(fn ($t) => '<a class="tag-pill" href="?r=tag&t=' . $t . '">#' . $t . '</a>', explode(',', $p->tags))) . '</span>
      </div>
    </div>', $posts));
  }

  static function renderPost(object $post)
  {
    $showReply = !$post->isDeleted;
    $showDelete = $post->author == $_SESSION['user']->id && !$post->isDeleted;
    $showEdit = $showDelete;
    $detailsText = $post->isDeleted ? '[deleted]' : (new Parsedown())->text($post->details);
    $link = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/bb?r=post&id=' . $post->id;
    $str = '<div class="post" id="p' . $post->id . '">
      <h2 class="header">' . $post->title . '</h2>
      <div class="body">
        <div class="author">
          <a href="?r=user&id=' . $post->author . '">' . $post->authorName . '</a> @ 
          <span class="d t">' . $post->created . '</span>'
      . ($post->created != $post->updated && !$post->isDeleted ? ' <span title="' . $post->updated . ' UTC">[edited]</span>' : '') . ' | '
      . '<span>tags:' . join('', array_map(fn ($t) => '<a class="tag-pill" href="?r=tag&t=' . $t . '">#' . $t . '</a>', explode(',', $post->tags))) . '</span>'
      . '</div>'
      . ($detailsText ? '<div class="details">' . $detailsText . '</div>' : '')
      . '<div class="tools">'
      . ($showReply ? '<details>
          <summary>Reply</summary>
          <form method="POST">
            <input hidden name="action" value="reply"/>
            <input hidden name="postId" value="' . $post->id . '"/>
            <textarea name="details" placeholder="Write reply here..."></textarea><br>
            <button type="submit">Submit</button>
          </form>
        </details>' : '')
      . ($showEdit ? '<details>
          <summary>Edit</summary>
          <form method="POST">
            <input hidden name="action" value="edit-post"/>
            <input hidden name="postId" value="' . $post->id . '"/>
            <textarea name="details" placeholder="Details" required>' . $post->details . '</textarea><br>
            <input name="tags" placeholder="Tags (comma-separated, min 1, max 5)" value="' . $post->tags . '" style="width:100%" required />
            <button type="submit">Submit</button>
          </form>
        </details>' : '')
      . ($showDelete ? '<div>
            <div class="summary" onclick="deletePost(' . $post->id . ')">▶ Delete</div>
            <form method="POST" id="delete-post-' . $post->id . '" hidden>
              <input hidden name="action" value="delete-post"/>
              <input hidden name="postId" value="' . $post->id . '"/>
            </form>
          </div>' : '')
      . '<div>
            <div class="summary" onclick="navigator.share({title:\'BB\',text:\'' . $post->title . '\',url:\'' . $link . '\'})">▶ Share</div>
          </div>
        </div>
      </div>
    </div>';
    return $str;
  }
}
