<?php

namespace App\Entity\File;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class File
 * @package App\Entity\File
 * @ORM\Table(name="file_manager")
 * @ORM\Entity
 */
class File implements FileBaseInterface
{
  use FileTrait;
}
