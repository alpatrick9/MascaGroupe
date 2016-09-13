<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/26/16
 * Time: 3:04 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointageEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heureDebut', TimeType::class, [
                'label'=> 'Debut du cours',
                'placeholder'=>['hour' => 'Heure', 'minute' => 'Minute']
            ])
            ->add('heureFin', TimeType::class, [
                'label'=> 'Fin du cours',
                'placeholder'=>['hour' => 'Heure', 'minute' => 'Minute']
            ])
            ->add('matiere', TextType::class, [
                'label'=>'Matière',
                'attr'=>['placeholder'=>'Ex: Anglais']
            ])
            ->add('volumeHoraire', IntegerType::class, [
                'label'=> 'Heure total du cours',
                'attr'=>['min'=>1]
            ])
            ->add('etablissement', ChoiceType::class,[
                'label'=>'Etablissement',
                'placeholder'=>'Choisissez',
                'choices_as_values'=>true,
                'choices'=>["Université"=>"universite", "Lycée"=>"lycee"]
            ])
            ->add('autre', TextType::class, [
                'label'=> 'Autre information',
                'attr'=>['placeholder'=>'Ex: Titre du cours'],
                'required'=>false
            ])
            ->add('infoTauxHoraire', EntityType::class, [
                'label'=>'Le taux horaire correspondant',
                'class'=>'Masca\PersonnelBundle\Entity\InfoVolumeHoraire',
                'property'=>'tauxHoraire',
                'placeholder'=>'Choisissez'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\PersonnelBundle\Entity\PointageEnseignant'
        ]);
    }

}