<?php namespace Adnduweb\Ci4_diaporama\Diaporama;

/***
* Name: Visits
* Author: Matthew Gatner
* Contact: mgatner@tattersoftware.com
* Created: 2019-02-12
*
* Description:  Lightweight traffic tracking for CodeIgniter 4
*
* Requirements:
*   >= PHP 7.1
*   >= CodeIgniter 4.0
* Preconfigured, autoloaded Database
* CodeIgniter's URL helper (loaded automatically)
* Visits table (run migrations)
*
* Configuration:
*   Use app/Config/Visits.php to override default behavior
*   Run migrations to update database tables:
*     > php spark migrate:latest -n "Tatter\Visits"
*
* @package CodeIgniter4-Visits
* @author Matthew Gatner
* @link https://github.com/tattersoftware/codeigniter4-visits
*
***/

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Models\DiaporamaModel;
use  Adnduweb\Ci4_diaporama\Exceptions\VisitsException;

/*** CLASS ***/
class Diaporama
{

}
