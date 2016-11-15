<?php

namespace Masca\EtudiantBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/18/16
 * Time: 2:14 PM
 */
class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numMatricule',TextType::class,array(
                'label'=>'Numeros matricule'
            ))
            ->add('nom',TextType::class,array(
                'label'=>'Nom'
            ))
            ->add('prenom',TextType::class,array(
                'label'=>'Prénom',
                'required'=>false
            ))
            ->add('dateNaissance',DateType::class,array(
                'label'=>'Date de naissance',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-80,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')

            ))
            ->add('lieuNaissance',TextType::class,array(
                'label'=>'Lieu de Naissance'
            ))
            ->add('numCin',TextType::class,array(
                'label'=>'Numeros CIN',
                'attr'=>array(
                    'placeholder'=>112991012188)
            ))
            ->add('dateDelivranceCin',DateType::class,array(
                'label'=>'Fait le',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-50,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ))
            ->add('lieuDelivranceCin',TextType::class, array(
                'label'=>'à'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\Person',
            'trait_choices' => null,
        ));
    }

}