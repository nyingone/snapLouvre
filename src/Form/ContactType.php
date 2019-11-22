<?php declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
    ->add('firstName', TextType::class , [
    'attr' => ['class' => 'form-control']
    ] )
    ->add('lastName', TextType::class , [
    'attr' => ['class' => 'form-control']
    ] )
    ->add('email', EmailType::class, [
    'attr' => ['class' => 'form-control'],
    'constraints' => [
    new NotBlank(),
    new Email()
    ]
    ])
    ->add('message', TextareaType::class, [
    'attr' => ['class' => 'form-control']
    ] );
    }

}