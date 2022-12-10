<?php

namespace App\Views;

use App\Svc\PostsSvc;
use App\Mod\PostsMod;

$tag = @$_GET['t'];
$posts = PostsSvc::readAllByTag($tag);

?>

<h2>#<?= $tag ?></h2>
<section>
  <?= PostsMod::renderPostsList($posts) ?>
</section>