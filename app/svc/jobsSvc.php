<?php

namespace App\Svc;

use App\Data\DB;

class JobsSvc
{
  static function purgeDeleted(?string $period = null): string
  {
    $std = [];
    $date = new \DateTime();
    if ($period) $date->sub(new \DateInterval($period));

    $conn = DB::connect();

    $stmt1 = $conn->prepare("DELETE FROM comments WHERE isDeleted = 1 AND created < :olderThan");
    $stmt1->execute([':olderThan' => $date->format('Y-m-d H:i:s')]);
    $std[] = date('Y-d-m H:i:s') . ': Deleted ' . $stmt1->rowCount() . ' comments older than ' . $date->format('Y-m-d H:i:s');

    $stmt2 = $conn->prepare("DELETE FROM posts WHERE isDeleted = 1 AND created < :olderThan");
    $stmt2->execute([':olderThan' => $date->format('Y-m-d H:i:s')]);
    $std[] = date('Y-d-m H:i:s') . ': Deleted ' . $stmt2->rowCount() . ' posts older than ' . $date->format('Y-m-d H:i:s');

    return join("\n", $std);
  }

  static function purgeAbandoned(): string
  {
    $std = [];

    $conn = DB::connect();

    $stmt = $conn->prepare("DELETE FROM comments
    WHERE parentId NOT IN (SELECT id FROM comments)
    AND parentID != 0");
    $stmt->execute();
    $std[] = date('Y-d-m H:is') . ': Deleted ' . $stmt->rowCount() . ' abandoned entities';

    return join("\n", $std);
  }
}
