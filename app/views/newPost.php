<?php

namespace App\Views;

use App\Ctrl\PostsCtrl;

if (@$_POST['title'] && @$_POST['tags']) {
  PostsCtrl::Post();
}

?>

<h2>Create</h2>
<form method="POST">
  <div>
    <input name="title" placeholder="Title" style="width:100%" required />
  </div>
  <div>
    <textarea name="details" placeholder="Details (optional)" rows="10"></textarea>
  </div>
  <div>
    <input name="tags" placeholder="Tags (comma-separated, min 1, max 5)" style="width:100%" required />
  </div>
  <div>
    <button type="submit">Post</button>
  </div>
</form>