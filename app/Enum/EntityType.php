<?php

namespace App\Enum;

use App\Models\Challenges;
use App\Models\Companies;
use App\Models\Programs;
use App\Models\User;

/**
 * Enum for all entity types awolled in the system
 */
enum EntityType: string
{
  /** Unidades de negocio de MOBILE */
  case USER = 'user';
  case USERS = 'users';
  case CHALLENGE = 'challenge';
  case CHALLENGES = 'challenges';
  case COMPANY = 'company';
  case COMPANIES = 'companies';
  case PROGRAM = 'program';
  case PROGRAMS = 'programs';

  /**
   * Function to get the class of the entity type
   *
   * @param string $id
   * @return User|Challenges|Companies|Programs
   */
  public static function getParent(string $id): User|Challenges|Companies|Programs
  {
    return EntityType::from($id)->id();
  }

  /**
   * Function to mathc entity type with class
   *
   * @return User|Challenges|Companies|Programs
   */
  public function id(): User|Challenges|Companies|Programs
  {
    $value = $this->value;
    return match ($this) {
      self::USER, self::USERS => new User(),
      self::CHALLENGE, self::CHALLENGES => new Challenges(),
      self::COMPANY, self::COMPANIES => new Companies(),
      self::PROGRAM, self::PROGRAMS => new Programs(),
    };
  }
}
