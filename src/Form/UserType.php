<?php

// src/Form/UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'choice',
                'input' => 'datetime',
                'format' => 'ddMMyyyy',
                'years' => range(date('Y') - 100, date('Y')), // Plage des années
                'months' => range(1, 12), // Mois de 1 à 12
                'days' => range(1, 31), // Jours de 1 à 31
                'attr' => ['class' => 'date-selector'],
            ])
            ->add('telephone')
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email([
                        'message' => 'L\'adresse e-mail {{ value }} n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('mdp', PasswordType::class)
            ->add('confirmMdp', PasswordType::class, [
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

