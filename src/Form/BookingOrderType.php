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
                'attr' => ['class' => 'js-datepicker', 
                'id' => 'datepicker',
                'placeholder' => 'select a date',
                ],
            ])
            ->add('partTimeCode', PartTimeCodeType::class, [
                'attr' => ['class' => 'form-control',  'required' => false,],
            ])
            ->add('wishes', IntegerType::class, [
                'attr' => [ 'type' => 'number', 'step' => 1, 'mapped' => false],
            ])
            ;
          $builder->get('expectedDate')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $form->getParent()->add('visitors', CollectionType::class, [
                        'entry_type' =>VisitorType::class,
                        'entry_options' => ['label' => false],
                        'by_reference' => false,
                        'allow_add' => true,
                        'allow_delete' => true
                    ]);
                }
            );
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookingOrder::class,
            'validation_groups' => ['registration'],
        ]);
    }
}
