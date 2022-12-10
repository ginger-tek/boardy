<?php

namespace App\Svc;

use App\Data\DB;

class CommentsSvc
{
  static function create(object $obj): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("INSERT INTO comments(
      postId,
      parentId,
      author,
      details
    ) VALUES(
      :postId,
      :parentId,
      :author,
      :details
    )");
    $stmt->execute([
      ':postId' => $obj->postId,
      ':parentId' => $obj->parentId ?? 0,
      ':author' => $obj->author,
      ':details' => $obj->details
    ]);
    return CommentsSvc::read($conn->lastInsertId());
  }

  static function read(int $id): object|false
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_comments c
    WHERE c.id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
  }

  static function readAll(): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_comments c
    ORDER BY c.created ASC");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  static function readAllByPost(int $postId): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_comments c
    WHERE c.postId = :postId
    ORDER BY c.created ASC");
    $stmt->execute([':postId' => $postId]);
    return $stmt->fetchAll();
  }

  static function readAllByUser(int $userId): array
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("SELECT *
    FROM v_comments c
    WHERE c.author = :userId
    ORDER BY c.created ASC");
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll();
  }

  static function update(object $obj): bool
  {
    $conn = DB::connect();
    $stmt = $conn->prepare("UPDATE comments SET
      details = :details,
      isDeleted = :isDeleted,
      updated = CURRENT_TIMESTAMP
    WHERE id = :id");
    $stmt->execute([
      ':details' => $obj->details,
      ':isDeleted' => $obj->isDeleted,
      ':id' => $obj->id
    ]);
    return $stmt->rowCount() == 1 ? true : false;
  }
}
