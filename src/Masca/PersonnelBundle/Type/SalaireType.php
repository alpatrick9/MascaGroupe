<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/30/16
 * Time: 4:38 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalaireType extends AbstractType
{
    private $mois;

    /**
     * SalaireType constructor.
     * @param $mois
     */
    public function __construct($mois)
    {
        $this->mois = $mois;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salaireFixe', NumberType::class, [
                'label'=>'Salaire mensuel',
                'read_only'=>true
            ])
            ->add('tauxHoraire', NumberType::class, [
                'label'=>'Taux Horaire',
                'read_only'=>true
            ])
            ->add('mois', ChoiceType::class, [
                'label' => 'Mois',
                'choices_as_values' => true,
                'choices' => $this->mois,
                'placeholder' => 'Choisissez...'
            ])
            ->add('totalAvance', NumberType::class, [
                'label'=> 'Total Avance',
                'read_only'=>true
            ])
            ->add('prime', NumberType::class, [
                'label'=>'Prime',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Masca\PersonnelBundle\Entity\Salaire'
        ]);
    }

}