<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Visitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VisitorType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'label_firstname',
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'label_lastname',
                ]
            ])
            ->add('birthDate', BirthdayType::class, [
            ])
            ->add('country', CountryType::class, array(
                'preferred_choices' => array('FR'),
            ))
            ->add('discounted', CheckboxType::class, [
                'label' => 'Eligible to discount?',
                'required' => false,
            ]);
        //  $builder->add('delete', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visitor::class,
            'validation_groups' => ['default'],
        ]);
    }


}



