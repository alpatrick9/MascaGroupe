<?php
namespace OC\UserBundle\Type;

use OC\UserBundle\Entity\Roles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, [
                'label'=>'Rôle',
                'placeholder'=>'Choisissez',
                'choices'=>Roles::$roles,
                'choices_as_values'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'OC\UserBundle\Entity\Role',
            'trait_choices'=>null
        ]);
    }


}