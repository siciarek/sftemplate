<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\MainBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use \Application\MainBundle\Doctrine\DBAL as DBAL;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("username", null, array("label" => "form.register.username", "required" => true));
        $builder->add("first_name", null, array("label" => "form.register.firstname", "required" => false));
        $builder->add("last_name", null, array("label" => "form.register.lastname", "required" => false));
        $builder->add("email", "email", array("label" => "E-mail * "));
        $builder->add("plainPassword", "repeated", array(
            "type"            => "password",
            "first_options"   => array("label" => "form.register.password"),
            "second_options"  => array("label" => "form.register.repeatpass"),
            "invalid_message" => "fos_user.password.mismatch",
        ));

        $builder->add("enabled", "hidden", array("data" => 0));
    }

    public function getName()
    {
        return "user_registration";
    }
}