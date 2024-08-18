<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Gâteau pour enfant' => 'gateau_enfant',
                    'Gâteau pour adolescent' => 'gateau_adolescent',
                    'Mignardise' => 'mignardise',
                    'Événement d\'entreprise' => 'evenement_entreprise'
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix'
            ])
            ->add('cremeNumberCake', ChoiceType::class, [
                'label' => 'Crème Number Cake',
                'choices' => [
                    'Chantilly chocolat' => 'chantilly_chocolat',
                    'Nutella' => 'nutella',
                    'Pistache' => 'pistache',
                    'Spéculos' => 'speculos'
                ],
                'required' => false,
                'placeholder' => 'Choisissez une option'
            ])
            ->add('ganacheLayerCake', ChoiceType::class, [
                'label' => 'Ganache Layer Cake',
                'choices' => [
                    'Caramel' => 'caramel',
                    'Chocolat' => 'chocolat',
                    'Chocolat caramel' => 'chocolat_caramel',
                    'Chocolat coco' => 'chocolat_coco',
                    'Chocolat framboise' => 'chocolat_framboise',
                    'Framboise' => 'framboise',
                    'Kinder' => 'kinder',
                    'Mangue' => 'mangue',
                    'Oréo' => 'oreo',
                    'Pistache' => 'pistache',
                    'Poire chocolat' => 'poire_chocolat',
                    'Pomme' => 'pomme',
                    'Smarties' => 'smarties',
                    'Snickers' => 'snickers',
                    'Spéculos' => 'speculos'
                ],
                'required' => false,
                'placeholder' => 'Choisissez une option'
            ])
            ->add('nbPart', ChoiceType::class, [
                'label' => 'Nombre de parts',
                'choices' => [
                    '10/15 parts' => '10_15',
                    '13/15 parts' => '13_15',
                    '18/20 parts' => '18_20',
                    '20/25 parts' => '20_25',
                    '25/30 parts (étage)' => '25_30',
                    '4 parts' => '4_parts',
                    '6/8 parts' => '6_8_parts'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'mapped' => false // Pour ne pas essayer de setter l'image directement dans l'entité
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter le produit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
