<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/31/16
 * Time: 1:37 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule',TextType::class,[
                'label'=>'Intitulé du filière'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\Ue',
            'trait_choices'=>null
        ]);
    }

}