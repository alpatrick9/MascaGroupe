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
            ->add('avecRepartion', CheckboxType::class, [
                'label'=> 'Note unique',
                'required'=>false
            ])
            ->add('coefficient',IntegerType::class,[
                'label'=>'Coefficient de la matière',
                'attr'=>[
                    'min'=>1
                ]
            ])
            ->add('noteEF',NumberType::class,[
                'label'=>'EF /20',
                'required'=>false
            ])
            ->add('noteFC',NumberType::class,[
                'label'=>'FC /20',
                'required'=>false
            ])
            ->add('noteNJ',NumberType::class,[
                'label'=>'NJ /20',
                'required'=>false
            ])
            ->add('note',NumberType::class,[
                'label'=>' Note unique',
                'required'=>false
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