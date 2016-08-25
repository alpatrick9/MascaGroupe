<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/25/16
 * Time: 4:03 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateAbsent',DateType::class,[
                'label'=>'Date d\'absence',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y'),date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ])
            ->add('partieJournee', ChoiceType::class, [
                'label'=> 'Partie de la journée',
                'choices_as_values'=>true,
                'choices'=>['Matiné'=>'Matiné','Après-midi'=>'Après-midi'],
                'expanded'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\PersonnelBundle\Entity\AbsenceEmploye'
        ]);
    }

}