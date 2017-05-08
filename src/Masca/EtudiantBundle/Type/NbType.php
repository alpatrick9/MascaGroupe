<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/8/17
 * Time: 2:41 PM
 */

namespace Masca\EtudiantBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nb', TextareaType::class, [
                'label' => 'Remarques: ',
                'attr' => ['rows'=>10],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Masca\EtudiantBundle\Model\Nb'
        ));
    }

}