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
                'choices'=> [1 => 'Oui', 0 => 'Non'],
                'expanded'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class'=>'Masca\PersonnelBundle\Entity\Status',
           'trait_choices'=>null
       ]);
    }

}