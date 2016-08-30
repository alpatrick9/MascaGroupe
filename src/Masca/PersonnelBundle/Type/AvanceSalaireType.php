<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/30/16
 * Time: 3:38 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvanceSalaireType extends AbstractType
{
    private $mois;

    /**
     * AvanceSalaireType constructor.
     * @param $mois
     */
    public function __construct($mois)
    {
        $this->mois = $mois;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mois',ChoiceType::class,[
                    'label'=>'Mois',
                    'choices_as_values'=>true,
                    'choices'=> $this->mois,
                    'placeholder'=>'Choisissez...'
                ])
            ->add('somme', NumberType::class, [
                'label'=>'Somme à retrait'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=>'Masca\PersonnelBundle\Entity\AvanceSalaire'
        ]);
    }

}