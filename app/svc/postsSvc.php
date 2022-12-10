<?php

namespace App\Svc;

use App\Data\DB;

class PostsSvc
{
  static function create(object $obj): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("INSERT INTO posts(
      author,
      title,
      details,
      tags
    ) VALUES(
      :author,
      :title,
      :details,
      :tags
    )");
    $stmt->execute([
      ':author' => $obj->author,
      ':title' => $obj->title,
      ':details' => $obj->details,
      ':tags' => $obj->tags
    ]);
    return PostsSvc::read($conn->lastInsertId());
  }

  static function read(int $id): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_posts p
    WHERE p.id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
  }

  static function readAll(?int $limit = 100): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_posts p
    ORDER BY p.created DESC" . ($limit ? " LIMIT $limit" : ''));
    $stmt->execute();
    return $stmt->fetchAll();
  }

  static function readAllActive(): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_posts p
    WHERE p.isDeleted = 0
    ORDER BY p.created DESC");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  static function readAllByUser(int $userId): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_posts p
    WHERE p.author = :userId
    ORDER BY p.created DESC");
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll();
  }

  static function readAllByTag(string $tag): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_posts p
    WHERE p.tags LIKE :tag
    ORDER BY p.created DESC");
    $stmt->execute([':tag' => "%$tag%"]);
    return $stmt->fetchAll();
  }

  static function update(object $obj): bool
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("UPDATE posts SET
      details = :details,
      tags = :tags,
      isDeleted = :isDeleted,
      isArchived = :isArchived,
      updated = CURRENT_TIMESTAMP
    WHERE id = :id");
    $stmt->execute([
      ':details' => $obj->details,
      ':tags' => $obj->tags,
      ':isDeleted' => $obj->isDeleted,
      ':isArchived' => $obj->isArchived,
      ':id' => $obj->id
    ]);
    return $stmt->rowCount() == 1 ? true : false;
  }
}
