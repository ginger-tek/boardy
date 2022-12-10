<?php

namespace App\Mod;

class TagsMod
{
  static function renderTagsList(array $tags): string
  {
    if (count($tags) == 0) return '<div>No tags yet</div>';
    return join("\n", array_map(fn ($t) => '<div class="list-item">
      <h4><a href="?r=tag&t=' . $t->name . '">#' . $t->name . '</a></h4>
    </div>', $tags));
  }
}
