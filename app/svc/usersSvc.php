<?php

namespace App\Svc;

use App\Data\DB;

class UsersSvc
{
  static function create(object $obj): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("INSERT INTO users(
      username,
      password
    ) VALUES(
      :username,
      :password
    )");
    $stmt->execute([
      'username' => $obj->username,
      'password' => $obj->password
    ]);
    return UsersSvc::read($conn->lastInsertId());
  }

  static function read(int $id): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
  }

  static function readByUsername(string $username): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    return $stmt->fetch();
  }

  static function readAll(?int $limit = null): array
  {
    $conn =  DB::connect();
    $stmt = $conn->prepare("SELECT id, username, role, created, updated
    FROM users" . ($limit ? " LIMIT $limit" : ''));
    $stmt->execute();
    return $stmt->fetchAll();
  }

  static function update(object $obj): bool
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("UPDATE users SET
      username = :username,
      password = :password
    WHERE id = :id");
    $stmt->execute([
      ':username' => $obj->username,
      ':password' => $obj->password,
      ':id' => $obj->id
    ]);
    return $stmt->rowCount() == 1 ? true : false;
  }
}
