<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 3:23 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcolageUnivType extends AbstractType
{
    private $traitChoices;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];
        $builder
            ->add('mois',ChoiceType::class,array(
                'label'=>'Mois',
                'choices_as_values'=>true,
                'choices'=> $this->traitChoices['mois'],
                'placeholder'=>'Choisissez...'
            ))
            ->add('annee',ChoiceType::class,array(
                'label'=>'Annee',
                'choices_as_values'=>true,
                'choices'=> $this->traitChoices['annees'],
                'placeholder'=>'Choisissez...',
            ))
            ->add('montant',IntegerType::class,array(
                'label'=>'Montant',
                'attr'=>array(
                    'placeholder'=>'la somme',
                    'min'=>0,
                    'max'=>$this->traitChoices['max']
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\FraisScolariteUniv',
            'trait_choices'=>null
        ]);
    }

}