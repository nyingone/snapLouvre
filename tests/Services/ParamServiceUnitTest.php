<?php

namespace App\Tests\Services;


use App\Entity\Param;
use App\Services\ParamService;
use App\Services\Tools\DatComparator;
use PHPUnit\Framework\TestCase;
use App\Repository\ParamRepository;
use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\ParamRepositoryInterface;


class ParamServiceUnitTest extends TestCase 
{

    /**
     * @var ParamRepositoryInterface
     */
    private $repository;

    /**
     * @var ParamServiceInterface
     */
    private $paramService;
    /**
     * @var Param
     */
    private $param0;
    /**
     * @var Param
     */
    private $param1;
    private $param2;
    private $datComparator;
    private $partTimeArray;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ParamRepository::class);
        $this->datComparator= $this->createMock(DatComparator::class);
        
    }

    /** @test
     * @throws \Exception
     */
    public function can_find_and_save_Max_bookingVisitor_parameters()
    {
        $paramX = new Param();
        $paramX->setRefCode('MaxVisitors');
        $paramX->setLabel('visitor maximum per day for indicated period');
        $paramX->setNumber(1500); 

        
        $paramY = new Param();
        $paramY->setRefCode('MaxBookingVisitors');
        $paramY->setLabel('maximum of visitors per bookingorder');

        
        $paramY->setNumber(10); 

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([$paramX, $paramY]);

       
        $paramService = new ParamService($this->repository,  $this->datComparator);

        $this->assertEquals([$paramX], $paramService->findMaxDayEntries(new \DateTime()));
        $this->assertEquals(10, $paramService->findMaxAllowedGuests()());


    }



    /** @test
     * @throws \Exception
     */
    public function can_find_and_return_partTimeCode_array()
    {
        $this->setParamPartTimeCode();

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([
                $this->param0,
                $this->param1,
                $this->param2
                ]);

        $paramService = new ParamService($this->repository,$this->datComparator);
        $this->assertEquals($this->partTimeArray, $paramService->findPartTimeArray());

    }


    private function setParamPartTimeCode()
    {
        $this->param0 = new Param();
        $this->param0->setRefCode('PartTimeCode');
        $this->param0->setLabel('Fullday     -Full tarif');
        $this->param0->setNumber(0);

        $this->param1 = new Param(); 
        $this->param1->setRefCode('PartTimeCode');
        $this->param1->setLabel('Halfday     -Full tarif');
        $this->param1->setNumber(1);

        $this->param2 = new Param(); 
        $this->param2->setRefCode('PartTimeCode');
        $this->param2->setLabel('Halfday     -Half tarif');
        $this->param2->setNumber(2);

        $this->partTimeArray = [
            'Fullday     -Full tarif' => 0,
            'Halfday     -Full tarif' => 1,
            'Halfday     -Half tarif' =>2
        ];
    }
}