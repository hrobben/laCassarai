<?php

namespace App\Form;

use App\Entity\Tijden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TijdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dag')
            ->add('begintijd', TimeType::class, array(
                'input'  => 'datetime',
                'widget' => 'choice',
            ))
            ->add('eindtijd', TimeType::class, array(
                'input'  => 'datetime',
                'widget' => 'choice',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tijden::class,
        ]);
    }
}
