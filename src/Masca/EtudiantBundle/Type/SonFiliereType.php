<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/19/16
 * Time: 1:32 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SonFiliereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('semestre',EntityType::class,array(
                'label'=>'Semestre',
                'class'=>'Masca\EtudiantBundle\Entity\Semestre',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...'
            ))
            ->add('sonFiliere',EntityType::class,array(
                'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...'
            ))
            ->add('sonNiveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'etude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...'
            ))
            ->add('anneeEtude',IntegerType::class,array(
                'label'=>'Année d\'etude',
                'attr'=>array('min'=>1900)
            ))
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\UniversitaireSonFiliere',
            'trait_choices' => null,
        ));
    }

}