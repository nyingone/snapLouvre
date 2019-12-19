<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\BookingOrder;
use App\Form\Type\PartTimeCodeType;
use App\Form\Type\VisitorType;
use App\Validator\Constraints\BookingDateIsOpen;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BookingOrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)    
    {
        $builder
            ->add('expectedDate', DateType::class, [
                'widget' => 'single_text',
                 // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                 // adds a class that can be selected in JavaScript
                'attr' => [
                    'class' => 'js-datepicker',
                ],
            ])
            ->add('partTimeCode', PartTimeCodeType::class, [
                'attr' => ['class' => 'form-control',  'required' => false,],
            ])
            ->add('wishes', IntegerType::class, [
                'attr' => [ 'type' => 'number', 'step' => 1, 'mapped' => false],

            ])
            ;
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookingOrder::class,
            'empty_data' => function(FormInterface $form){
                return new BookingOrder(
                    $form->get('expectedDate')->getData(),
                    $form->get('partTimeCode')->getData(),
                    $form->get('wishes')->getData()
                );
            },
            'validation_groups' => ['pre_booking'],
        ]);
    }
}
