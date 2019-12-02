<?php

namespace App\Repository;

use App\Entity\Pricing;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;

/**
 * @method Pricing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pricing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pricing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingRepository implements PricingRepositoryInterface
{

    private const ENTITY= Pricing::class;
    private $entityManager;
    private $objectRepository;
    private $pricingrepository;
    private  $conn;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->conn = $this->entityManager->getConnection();
        $this->objectRepository = $this->entityManager->getRepository(self::ENTITY);

       // $this->pricingrepository = $this->entityManager->getRepository(Pricing::class);

    }


    public function findLastTariffDate($date)
    {
      $dateRef =  $date->format('Y-m-d');
   
      $sql = '
          SELECT max(term_date) FROM pricing p
          WHERE p.term_date <= :date_ref
          ORDER BY p.term_date DESC
          ';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
          'date_ref' => $dateRef
          ]);

          return $stmt->fetchAll();
    }
   
    /**
     * @param $expectedDate 
     * @param integer  $partTimeCode
     * @param boolean $discounted
     * @param integer $yearsold
     * @return []
     */
    public function findLastPricing($date, $partTimeCode, $discounted, $yearsOld): array
    {
       return $this->objectRepository->findby([
            'termDate' => $date,
            'discounted' => $discounted
       ]);
      
 
    }
}
