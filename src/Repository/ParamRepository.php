<?php

namespace App\Repository;

use App\Entity\Param;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\ParamRepositoryInterface;


class ParamRepository implements ParamRepositoryInterface
{

    private const ENTITY = Param::class;

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $objectRepository;

    /** @param EntityManagerInterface */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
    }

    /**
     * * return Param[] Returns an array of Param objects
     * @inheritDoc
     */
    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

}


