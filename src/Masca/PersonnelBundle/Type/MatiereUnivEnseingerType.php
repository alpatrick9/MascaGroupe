<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/22/16
 * Time: 2:07 PM
 */

namespace Masca\PersonnelBundle\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereUnivEnseingerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matiere', EntityType::class, [
                'label'=>'Matière',
                'class'=>'Masca\EtudiantBundle\Entity\Matiere',
                'choice_label'=>'intitule',
                'placeholder'=>"choisissez...",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\PersonnelBundle\Entity\MatiereUnivEnseigner'
        ]);
    }

}