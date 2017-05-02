<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/19/16
 * Time: 1:01 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule',TextType::class,array(
                'label'=> 'Nom du classe',
                'attr'=>array('placeholder'=>'2nd B')
            ))
            ->add('niveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'étude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>"choisissez...",
                'empty_data'=>null
            ))
            ->add('droitInscription', NumberType::class, [
                'label'=>'Droit d\'inscription'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\Classe',
            'trait_choices' => null,
        ));
    }

}