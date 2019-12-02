<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;


class CustomerRepository implements CustomerRepositoryInterface
{

    private const ENTITY = Customer::class;
    
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

    /** @inheritDoc */
    public function find(Customer $customer): ?Customer
    {
        $this->entityManager->find(self::ENTITY, $id->toString());
    }

    /** @param string
     * @return Customer|null
     */
    public function findOneByEmail(string $email): ?Customer
    {
        return $this->objectRepository->findOneBy(['email' => $email]);
    }


}