<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/22/16
 * Time: 3:25 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoSalaireFixeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salaire', NumberType::class, [
                'label'=>'Salaire mensuel (Ar)'
            ])
            ->add('titrePoste',TextType::class, [
                'label'=>'Libellé du poste',
                'attr'=>['placeholder'=>'ex: Surveillant général']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\PersonnelBundle\Entity\InfoSalaireFixe'
        ]);
    }

}