<?php namespace Adnduweb\Ci4_diaporama\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class DiaporamaException extends FrameworkException implements ExceptionInterface
{
  public static function forNoTrackingMethod()
  {
    return new static(lang('Diaporama.noTrackingMethod'));
  }

  public static function forInvalidResetMinutes()
  {
    return new static(lang('Diaporama.invalidResetMinutes'));
  }

  public static function forMissingDatabaseTable(string $table)
  {
    return new static(lang('Diaporama.missingDatabaseTable', [$table]));
  }
}
