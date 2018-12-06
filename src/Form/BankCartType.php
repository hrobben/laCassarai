<?php

namespace App\Form;

use App\Entity\BankCart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class BankCartType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accountnr')
            ->add('bank')
            ->add('cardnr');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('userid');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankCart::class,
        ]);
    }
}
