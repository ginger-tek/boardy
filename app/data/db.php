<?php

namespace App\Data;

use PDO;

class DB
{
  static function connect()
  {
    $conn = new PDO('sqlite:app/data/bb.sqlite', null, null, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ]);
    $conn->exec("PRAGMA foreign_keys = ON");
    return $conn;
  }
}

DB::connect()->exec(file_get_contents('app/data/schema.sql'));
