<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/22/16
 * Time: 3:28 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoVolumeHoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tauxHoraire', NumberType::class, [
                'label'=>'Taux horaire (Ar)'
            ])
            ->add('titrePoste', TextType::class, [
                'label'=>'Titre du poste',
                'attr'=>['placeholder'=>'ex: Enseignant du lycée']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\PersonnelBundle\Entity\InfoVolumeHoraire'
        ]);
    }

}