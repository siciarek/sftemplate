<?php

namespace Application\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatePickerType extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr'   => array(
                'autocomplete' => 'off',
                'class'        => 'date_picker',
            ),
        );

        $resolver->setDefaults($defaults);
    }


    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'date_picker';
    }
}
