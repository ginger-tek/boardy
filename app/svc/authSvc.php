<?php

namespace App\Svc;

use App\Svc\UsersSvc;

class AuthSvc
{
  static function login(string $username, string $password): object|false|null
  {
    $user = UsersSvc::readByUsername($username);
    if (!$user) return null;
    if (!password_verify($password, $user->password)) return false;
    return $user;
  }

  static function register(string $username, string $password): object|false|null
  {
    $user = UsersSvc::readByUsername($username);
    if ($user) return false;
    try {
      return UsersSvc::create((object)[
        'username' => $username,
        'password' => password_hash($password, PASSWORD_BCRYPT)
      ]);
    } catch (\Exception $err) {
      return null;
    }
  }

  static function resetPassword(int $userId, string $password): bool|null
  {
    $user = UsersSvc::read($userId);
    if (!$user) return null;
    $user->password = password_hash($password, PASSWORD_BCRYPT);
    return UsersSvc::update($user);
  }
}
