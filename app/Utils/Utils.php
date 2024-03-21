<?php

namespace App\Utils;

use App\Enum\EntityType;
use Exception;

class Utils {

  /**
   * Function that load model and create info for respective table
   *
   * @param string $model
   * @param array $data
   * @return void
   */
  public static function processInformationTable(string $model, array $data): int {
    $class = EntityType::getParent($model);
    $count_save = 0;
    foreach ($data as $keyD => $info_model) {
      foreach ($info_model as $key => $info) {
        try {
          $result = $class::create($info);
          if ($result) {
            $count_save++;
          }
        } catch (Exception $e) {
          continue;
        }
      }
    }
    return $count_save;
  }
}
