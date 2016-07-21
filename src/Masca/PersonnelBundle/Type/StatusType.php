<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/20/16
 * Time: 3:47 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('permanent', ChoiceType::class, [
                'label'=>'Employer permanent',
                'choices_as_values'=>true,
                'choices'=> ["Oui" => true, 'Non' => false],
                'expanded'=>true
            ])
            ->add('libelePoste', ChoiceType::class, [
                'label'=>'Type de poste',
                'choices_as_values'=>true,
                'choices'=>['Enseignant'=>'prof','Autre'=>'autre']
            ])
            ->add('dateEmbauche',DateType::class,[
                'label'=>'Embaucher le',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-2,date('Y')+2),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class'=>'Masca\PersonnelBundle\Entity\Status',
           'trait_choices'=>null
       ]);
    }

}