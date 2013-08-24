<?php
date_default_timezone_set('Europe/Warsaw');
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(

            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),


            new Uran1980\FancyBoxBundle\Uran1980FancyBoxBundle(),

            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),

            // CAPTCHA PROSTA:
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            // CAPTCHA GOOGLE:
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new EWZ\Bundle\TextBundle\EWZTextBundle(),

            // MAIN CUSTOM BUNDLE:
            new Application\MainBundle\ApplicationMainBundle(),

            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Mopa\Bundle\WSSEAuthenticationBundle\MopaWSSEAuthenticationBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),

            # frontend_bundles_start
            new Sonata\SeoBundle\SonataSeoBundle(),
            # frontend_bundles_end

            # backend_bundles_start
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\UserBundle\SonataUserBundle("FOSUserBundle"),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            # backend_bundles_end

            # custom_bundles_start
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            # custom_bundles_end

            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),

            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Siciarek\SymfonyUtilsBundle\SiciarekSymfonyUtilsBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
