<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 1:54 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetardUnivType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRetard',DateType::class,[
                'label'=>'Date de retard',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y'),date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ])
            ->add('heure', TimeType::class, [
                'label'=> 'Heure d\'arriver en cours',
                'placeholder'=>['hour' => 'Heure', 'minute' => 'Minute']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'Masca\EtudiantBundle\Entity\RetardUniv',
            'trait_choices'=>null
        ]);
    }

}