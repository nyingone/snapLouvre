<?php
namespace App\Form\Type;

use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PartTimeCodeType extends AbstractType
{
    
    /** @var ParamServiceInterface */
    private $paramService;
    protected $partTimeArray= [];
    
    /** @param ParamServiceInterface */
    public function __construct(ParamServiceInterface $paramService )
    {
        $this->paramService = $paramService;
        $this->partTimeArray = $this->paramService->findPartTimeArray();  
    }

    /** @param OptionsResolver */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->partTimeArray,
        ]);
    }


    public function getParent()
    {
        return ChoiceType::class;
    }
}