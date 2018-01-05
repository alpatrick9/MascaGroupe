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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvanceSalaireType extends AbstractType
{
    private $mois;
    private $years = [];

    /**
     * AvanceSalaireType constructor.
     * @param $mois
     */
    public function __construct($mois)
    {
        $this->mois = $mois;

        foreach (range(date('Y')-4,date('Y')+1) as $item) {
            $this->years[$item] = $item;
        }
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label'=>'Date d\'enregistrement',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-2,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ])
            ->add('caisse', ChoiceType::class, [
                'label'=>'Caisse',
                'choices_as_values'=>true,
                'choices'=>['Université'=>'cu', 'Lycée'=>'cl'],
                'placeholder'=>'Choisissez...'
            ])
            ->add('mois',ChoiceType::class,[
                    'label'=>'Mois de l\'avance',
                    'choices_as_values'=>true,
                    'choices'=> $this->mois,
                    'placeholder'=>'Choisissez...'
                ])
            ->add('annee', ChoiceType::class, [
                'label' => "Année de l'avance",
                'choices_as_values'=>true,
                'choices'=> $this->years,
                'placeholder'=>'Choisissez...'
            ])
            ->add('somme', NumberType::class, [
                'label'=>'Somme à retrait'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=>'Masca\PersonnelBundle\Entity\AvanceSalaire'
        ]);
    }

}