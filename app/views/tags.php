<?php

namespace App\Views;

use App\Svc\TagsSvc;
use App\Mod\TagsMod;

$tags = TagsSvc::readAll();

?>

<h2>Tags</h2>
<section>
  <?= TagsMod::renderTagsList($tags) ?>
</section>