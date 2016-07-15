<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/18/16
 * Time: 4:40 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LyceenNoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matiere',EntityType::class,[
                'label'=>'Matière',
                'class'=>'Masca\EtudiantBundle\Entity\MatiereLycee',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...'
            ])
            ->add('coefficient',IntegerType::class,[
                'label'=>'Coeficient',
                'attr'=>[
                    'min'=>1,
                    'max'=>10
                ],
            ])
            ->add('noteTrimestre1',NumberType::class,[
                'label'=>'Note 1èr trimestre (/20)'
            ])
            ->add('noteTrimestre2',NumberType::class,[
                'label'=>'Note 2nd trimestre (/20)',
                'required'=>false
            ])
            ->add('noteTrimestre3',NumberType::class,[
                'label'=>'Note 3ème trimestre(/20)',
                'required'=>false
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\LyceenNote',
            'trait_choices' => null,
        ));
    }

}