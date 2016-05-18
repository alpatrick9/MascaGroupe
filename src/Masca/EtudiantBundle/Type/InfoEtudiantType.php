<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/18/16
 * Time: 2:52 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse',TextType::class,array(
                'label'=>'Adresse'
            ))
            ->add('tel',TextType::class,array(
                'label'=>'Téléphone',
                'required'=>false
            ))
            ->add('email',EmailType::class,array(
                'label'=>'E-mail',
                'required'=>false
            ))
            ->add('nomMere',TextType::class,array(
                'label'=>'Nom de votre mère'
            ))
            ->add('nomPere',TextType::class,array(
                'label'=>'Nom de votre père'
            ))
            ->add('telParent',TextType::class,array(
                'label'=>'Contact de votre parent',
                'required'=>false
            ))
            ->add('emailParent',EmailType::class,array(
                'label'=>'E-mail de votre parent',
                'required'=>false
            ))
            ->add('nomTuteur',TextType::class,array(
                'label'=>'Nom de votre tuteur',
                'required'=>false
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\InfoEtudiant',
            'trait_choices' => null,
        ));
    }

}