<?php

namespace App\Form;

use App\Entity\WishList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, ['label' => 'Auteur :'])
            ->add('title', null, ['label' => 'Titre :'])
            ->add('details', TextareaType::class, ['label' => 'Détails :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WishList::class,
        ]);
    }
}
