<?php

namespace App\Svc;

use App\Data\DB;

class SearchSvc
{
  static function create(): object
  {
    return (object)[
      'fields' => [],
      'table' => [],
      'params' => []
    ];
  }

  static function query(object $query): array
  {
    $fields = count($query->fields) > 0 ? join(',', $query->fields) : '*';
    $sql = "SELECT $fields FROM $query->table";
    if (count($query->params) > 0) $sql .= " WHERE " . join(" OR ", array_map(fn ($k) => substr($k, 1) . " LIKE $k", array_keys($query->params)));
    $conn = DB::connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute($query->params);
    return $stmt->fetchAll();
  }
}
