<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/17
 * Time: 2:04 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatePayementEcoLyceeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('datePayement',DateType::class,[
            'label'=>'Date de versement',
            'format'=>'dd MMMM yyyy',
            'years'=>range(date('Y')-1,date('Y')),
            'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Entity\DatePayementEcolageLycee',
            'trait_choices' => null,
        ));
    }

}