<?php

namespace App\Svc;

use App\Data\DB;

class TagsSvc
{
  static function readAll(): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("WITH RECURSIVE split(value, rest) AS (
      SELECT '', tags||',' FROM posts
      UNION ALL SELECT
      substr(rest, 0, instr(rest, ',')),
      substr(rest, instr(rest, ',')+1)
      FROM split WHERE rest!=''
    )
    SELECT DISTINCT value as name
    FROM split
    WHERE value!=''
    ORDER BY value ASC");
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
