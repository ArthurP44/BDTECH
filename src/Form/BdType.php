<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Bd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class BdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', null, [
                'label' => 'Genre :',
                'placeholder' => 'Choisissez un Genre',
                'choice_label' => 'name'
            ])
            ->add('collection', null, [
                'label' => 'Série :',
                'placeholder' => 'Choisissez une Série',
                'choice_label' => 'name'
            ])
            ->add('authors', EntityType::class, [
                'label' => 'Auteur(s) :',
                'placeholder' => 'Choisissez un Auteur',
                'class' => Author::class,
                'expanded' => false,
                'multiple' => true,
                'by_reference' => false,
                'choice_label' => 'name'
            ])
            ->add('title', TextType::class, ['label' => 'Titre :'])
            ->add('edition', TextType::class, [
                'label' => 'Édition :',
                'required' => false
            ])
            ->add('collected_works', TextType::class, [
                'label' => 'Collection :',
                'required' => false
            ])
            ->add('creation_date', DateType::class, [
                'label' => 'Année de publication originale :',
                'format' => 'dMy',
                'years' => range(2020, 1940, 1),
                'required' => false,
            ])
            ->add('owned_bd_date', DateType::class, [
                'label' => 'Année de mon exemplaire :',
                'years' => range(2020, 1940, 1),
                'format' => 'dMy',
                'required' => false,
            ])
            ->add('value', IntegerType::class, [
                'label' => 'Cote :',
                'required' => false
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix :',
                'required' => false,
                'help' => 'Ne pas rentrer la devise',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire :',
                'required' => false,
                'help' => 'Prêtée à Jean, doublons (...)',
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN :',
                'required' => false
            ])
            ->add('on_lend', CheckboxType::class, [
                'label' => 'En prêt',
                'required' => false
            ])
            ->add('coverFile', VichFileType::class, [
                'label' => 'Couverture',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bd::class,
        ]);
    }
}
