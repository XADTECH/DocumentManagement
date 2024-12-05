<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  // Define constants for roles
  const ROLE_FINANCE_MANAGER = 'Finance Manager';
  const ROLE_OPERATION_MANAGER = 'Operation Manager';
  const ROLE_PROJECT_MANAGER = 'Project Manager';
  const ROLE_CLIENT_MANAGER = 'Client Manager';
  const ROLE_ADMIN = 'Admin';
  const ROLE_CEO = 'CEO';
  const ROLE_SUBADMIN = 'SubAdmin';
  const ROLE_USER = 'User';
  const ROLE_LOGISTICS = 'Logistics';
  const ROLE_CASHIER = 'Cashier';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'organization_unit',
    'phone_number',
    'password',
    'role',
    'permissions',
    'profile_image',
    'nationality',
    'xad_id'
  ];

  // Add this method to your User model
  public function hasRole($role)
  {
    return $this->role === $role;
  }

  /**
   * The attributes that should be cast to native types.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'permissions' => 'array',
  ];

  /**
   * Automatically hash the password when it's set.
   *
   * @param string $password
   */

  public static function roles()
  {
    return [
      self::ROLE_FINANCE_MANAGER,
      self::ROLE_OPERATION_MANAGER,
      self::ROLE_PROJECT_MANAGER,
      self::ROLE_CLIENT_MANAGER,
      self::ROLE_ADMIN,
      self::ROLE_CEO,
      self::ROLE_SUBADMIN,
      self::ROLE_USER,
      self::ROLE_LOGISTICS,
      self::ROLE_CASHIER,
    ];
  }
}
