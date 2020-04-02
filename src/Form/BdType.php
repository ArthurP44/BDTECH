<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Bd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('edition')
            ->add('collected_works')
            ->add('creation_date')
            ->add('owned_bd_date')
            ->add('value')
            ->add('price')
            ->add('comment')
            ->add('isbn')
            ->add('on_lend')
            ->add('filename')
            ->add('created_at')
            ->add('category', null, ['choice_label' => 'name'])
            ->add('collection', null, ['choice_label' => 'name'])
            ->add('authors', EntityType::class, [
                'class' =>Author::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bd::class,
        ]);
    }
}
