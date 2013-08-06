<?php

namespace Application\MainBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RecaptchaTransType
 *
 * https://www.google.com/recaptcha/admin/create
 *
 * @package Application\MainBundle\Form\Type
 */
class RecaptchaTransType extends AbstractType implements ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $recaptcha_keys = array(
            'audio_challenge',
            'cant_hear_this',
            'help_btn',
            'image_alt_text',
            'incorrect_try_again',
            'instructions_audio',
            'instructions_context',
            'instructions_visual',
            'play_again',
            'privacy_and_terms',
            'refresh_btn',
            'visual_challenge',
        );

        $custom_translations = array();

        foreach ($recaptcha_keys as $key) {
            $custom_translations[$key] = $this->container->get('translator')->trans('recaptcha.' . $key);
        }

        $defaults = array(
            'mapped'      => false,
            'label'       => false,
            'constraints' => array(
                new \EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True()
            ),
            'attr'        => array(
                'options' => array(
                    'theme'               => 'red',
                    'custom_translations' => $custom_translations,
                ),
            )
        );

        $resolver->setDefaults($defaults);
    }

    public function getParent()
    {
        return 'ewz_recaptcha';
    }

    public function getName()
    {
        return 'recaptcha';
    }
}
