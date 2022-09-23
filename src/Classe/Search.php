<?php 

namespace App\Classe;

use App\Entity\User;
use App\Entity\Partner;

class Search
{
  /**
  * @var string
  */
  public $string;

  /**
  * @var User[]
  */
  public $users = [];

  public $active;
  public $inactive;

}