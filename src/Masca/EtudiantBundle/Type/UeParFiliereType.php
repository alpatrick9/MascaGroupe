<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/31/16
 * Time: 1:51 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UeParFiliereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ue',EntityType::class,[
                'label'=>'Unité d\'ensignement',
                'class'=>'Masca\EtudiantBundle\Entity\Ue',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez'
            ])
            ->add('filiere',EntityType::class,[
               'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez'
            ])
            ->add('niveau',EntityType::class,[
            'label'=>'Niveau d\'etude',
            'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
            'choice_label'=>'intitule',
            'placeholder'=>'choisissez'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\UeParFiliere',
            'trait_choices'=>null
        ]);
    }

}