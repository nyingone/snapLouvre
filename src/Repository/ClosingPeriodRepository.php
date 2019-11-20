<?php

namespace App\Repository;

use App\Entity\ClosingPeriod;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\ClosingPeriodRepositoryInterface;


final class ClosingPeriodRepository implements ClosingPeriodRepositoryInterface
{
    private const ENTITY = ClosingPeriod::class;

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

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->objectRepository->findAll();
       
    }
  
}
