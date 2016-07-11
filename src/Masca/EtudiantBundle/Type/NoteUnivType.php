<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/2/16
 * Time: 2:51 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteUnivType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coefficient',IntegerType::class,[
                'label'=>'Coefficient de la matière',
                'attr'=>[
                    'min'=>1
                ]
            ])
            ->add('noteEF',NumberType::class,[
                'label'=>'EF /20'
            ])
            ->add('noteFC',NumberType::class,[
                'label'=>'FC /20'
            ])
            ->add('noteNJ',NumberType::class,[
                'label'=>'NJ /20'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\NoteUniv',
            'trait_choices'=>null
        ]);
    }

}