<?php

namespace App\Services;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ManagerRegistry;

class TagService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function list()
    {
        return $this->doctrine->getRepository(Tag::class)->getUniqueTags();
    }
}
