<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/14/17
 * Time: 9:14 AM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsSchoolYearType extends AbstractType
{
    private $years;
    private $months;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->years = $options['years'];
        $this->months = $options['months'];
        $builder
            ->add('startMonth', ChoiceType::class, [
                'label' => 'à partir du :',
                'choices' => $this->months,
                'choices_as_values' => true,
                'placeholder' => 'du mois'
            ])
            ->add('startYear', ChoiceType::class, [
                'label' => '',
                'choices' => $this->years,
                'choices_as_values' => true,
                'placeholder' => 'année'
            ])
            ->add('endMonth', ChoiceType::class, [
                'label' => '',
                'choices' => $this->months,
                'choices_as_values' => true,
                'placeholder'=>'au mois'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Model\DetailsSchoolYear'
        ));
        $resolver->setRequired('years');
        $resolver->setRequired('months');
    }
}