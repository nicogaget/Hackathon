<?php

namespace App\Form;

use App\Entity\Creneau;
use App\Entity\Rdv;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RdvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'required' => true,
                'widget' => 'single_text',
                "data" => new DateTime(),
                'years' => range(date('Y'), date('Y')+100),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'html5' => false,
                ]))
            ->add('message')
            ->add('adress', null, ['label' => "Adresse de la consultation"])
            ->add('creneau', null, [
                'choice_label' => 'title',
                'placeholder' => 'Choisissez votre créneau'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }
}
