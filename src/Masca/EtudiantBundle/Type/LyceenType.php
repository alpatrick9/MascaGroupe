<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/18/16
 * Time: 2:56 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LyceenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ))
            ->add('numeros',IntegerType::class,array(
                'label'=>'Numéros en classe',
                'required'=>true,
                'attr'=>array('min'=>1)
            ))
            ->add('anneeScolaire',TextType::class,array(
                'label'=>'Année scolaire',
                'required'=>true,
                'attr'=>array('placeholder'=>'2015-2016')
            ))
            ->add('sonClasse',EntityType::class,array(
                'label'=>'Classe',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'required'=>true,
                'placeholder'=>'choississez ...',
                'empty_data'=>null
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\Lyceen',
            'trait_choices' => null,
        ));
    }

}