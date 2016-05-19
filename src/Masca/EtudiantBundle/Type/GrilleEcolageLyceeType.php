<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/19/16
 * Time: 1:15 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrilleEcolageLyceeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe correspondant',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez..'
            ))
            ->add('montant',NumberType::class,array(
                'label'=>'Montant en (Ar)'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee',
            'trait_choices' => null,
        ));
    }

}