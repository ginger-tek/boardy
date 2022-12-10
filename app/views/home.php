<?php

namespace App\Views;

use App\Svc\PostsSvc;
use App\Mod\PostsMod;

$posts = PostsSvc::readAllActive();

?>

<h2>Main Board</h2>
<section>
  <?= PostsMod::renderPostsList($posts) ?>
</section>