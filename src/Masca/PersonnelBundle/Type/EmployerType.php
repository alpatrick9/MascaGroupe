<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/22/16
 * Time: 3:11 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tauxCnaps', NumberType::class, [
            'label'=>'Taux Cnaps (%)',
            'required'=>false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=>'Masca\PersonnelBundle\Entity\Employer'
        ]);
    }

}