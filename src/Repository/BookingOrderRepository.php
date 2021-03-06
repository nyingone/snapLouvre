<?php

namespace App\Repository;

use App\Entity\BookingOrder;
use App\Services\ClosingPeriodService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\BookingOrderRepositoryInterface;


final class BookingOrderRepository implements BookingOrderRepositoryInterface
{

    private const ENTITY = BookingOrder::class;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $objectRepository;

    /**
     * Repository by composition not inheritance
     *
     * @param EntityManagerInterface $entityManager
     * @param ClosingPeriodService $closingPeriodService
     */
    public function __construct(EntityManagerInterface $entityManager, ClosingPeriodService $closingPeriodService)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
    }

    /**
     * @param BookingOrder
     * @return BookingOrder
     */
    public function find(BookingOrder $bookingOrder): ?BookingOrder
    {
        $bookingOrder = $this->objectRepository->find($bookingOrder);

        return $bookingOrder;
    }

    /**
     * @param BookingOrder
     * @return void
     */
    public function save(BookingOrder $bookingOrder): BookingOrder
    {

        if (!$this->entityManager->contains($bookingOrder)) {
                   $this->entityManager->persist($bookingOrder);
        }

        $this->entityManager->flush();

        return $bookingOrder;
    }

    /**
     * @param BookingOrder
     * @return void
     */
    public function remove(BookingOrder $bookingOrder)
    {
        $this->entityManager->remove($bookingOrder);
        $this->entityManager->flush();
    }

    /**
     * Returns an array of days and visitor count
     * @param \DateTime $start
     * @param \DateTime $end
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findDaysEntriesFromTo(\DateTime $start, \DateTime $end): ?array
    {
        $fromDate = $start->format('Y-m-d');
        $toDate = $end->format('Y-m-d');
        $conn = $this->entityManager->getConnection();

        $sql = "
            SELECT p.expected_date, count(q.id) FROM booking_order p, visitor q 
            WHERE p.id = q.booking_order_id
            AND p.expected_date >= :from_date
            AND p.expected_date <= :to_date
            AND p.settled_at IS NOT NULL
            GROUP BY p.expected_date
            ORDER BY p.expected_date ASC
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'from_date' => $fromDate,
            'to_date' => $toDate
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findFullBooked(int $maxVisitors)
    {
        $today = new \DateTime();
        $fromDate = $today->format('Y-m-d');
        $conn = $this->entityManager->getConnection();
        $sql = "
            SELECT p.expected_date  FROM booking_order p, visitor q 
            WHERE p.id = q.booking_order_id     
            AND p.expected_date >= :from_date
            AND p.settled_at IS NOT NULL
            GROUP BY p.expected_date
            HAVING  count(q.id) >= :max_visitors
            ORDER BY p.expected_date ASC
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'from_date' => $fromDate,
            'max_visitors' => $maxVisitors
        ]);
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @return BookingOrder[] Returns an array of BookingOrder objects
     */

    public function findByBookingRef($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.bookingOrder = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

}
