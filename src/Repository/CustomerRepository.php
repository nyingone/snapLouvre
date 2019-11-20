<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;


class CustomerRepository
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

    /** @param Customer
     * @return Customer|null
     */
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

    /**
     * @param Customer $customer
     */
    public function save(Customer $customer): void
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    /**
     * @param Customer $customer
     */
    public function remove(Customer $customer): void
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}