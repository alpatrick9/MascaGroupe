<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/20/16
 * Time: 3:47 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiliereParNiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filiere',EntityType::class,[
                'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisisser'
            ])
            ->add('niveau',EntityType::class,[
                'label'=>'Niveau d\'etude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisisser'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=>'Masca\EtudiantBundle\Entity\FiliereParNiveau',
            'trait_choices'=>null
        ]);
    }

}