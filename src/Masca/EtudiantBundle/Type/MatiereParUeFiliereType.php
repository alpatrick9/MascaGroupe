<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/31/16
 * Time: 2:24 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereParUeFiliereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matiere',EntityType::class,[
                'label'=>'Matiere',
                'class'=>'Masca\EtudiantBundle\Entity\Matiere',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\MatiereParUeFiliere',
            'trait_choices'=>null
        ]);
    }

}