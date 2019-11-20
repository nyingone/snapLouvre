<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\ScheduleRepositoryInterface;


class ScheduleRepository implements ScheduleRepositoryInterface
{

    private const ENTITY= Schedule::class;
    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var \Doctrine\Common\Persistence\ObjectRepository  */
    private $objectRepository;

/** @param EntityManagerInterface */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
    }
/** @inheritDoc*/
    public function findAll()
    {
        return $this->objectRepository->findAll();
    }
}
