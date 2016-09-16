<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/16/16
 * Time: 12:42 PM
 */

namespace Masca\TresorBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MvmtLyceeType extends AbstractType
{
    private $typeOperations;

    /**
     * MvmtUniversiteType constructor.
     * @param $typeOperations
     */
    public function __construct($typeOperations)
    {
        $this->typeOperations = $typeOperations;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [
                'label'=>'Déscription bref de l\'operation'
            ])
            ->add('somme', NumberType::class, [
                'label'=>'Valeur de l\'operation (Ar)'
            ])
            ->add('typeOperation', ChoiceType::class, [
                'label'=>'Type de l\'operation',
                'choices'=>$this->typeOperations,
                'choices_as_values'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\TresorBundle\Entity\MvmtLycee'
        ]);
    }
}