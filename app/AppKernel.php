<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Third party bundles
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Ivory\FormExtraBundle\IvoryFormExtraBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            // Darvin bundles
            new Darvin\ConfigBundle\DarvinConfigBundle(),
            new Darvin\ContentBundle\DarvinContentBundle(),
            new Darvin\ImageBundle\DarvinImageBundle(),
            new Darvin\PageBundle\DarvinPageBundle(),
            new Darvin\OrderBundle\DarvinOrderBundle(),
            new Darvin\ECommerceBundle\DarvinECommerceBundle(),
            new Darvin\PublicationsBundle\DarvinPublicationsBundle(),
            new Darvin\MenuBundle\DarvinMenuBundle(),
            new Darvin\UserBundle\DarvinUserBundle(),
            new Darvin\UtilsBundle\DarvinUtilsBundle(),
            new Darvin\WebmailLinkerBundle\DarvinWebmailLinkerBundle(),
            new Darvin\SitemapBundle\DarvinSitemapBundle(),
            // Admin bundle
            new Darvin\AdminBundle\DarvinAdminBundle(),
            new Darvin\AdminFrontendBundle\DarvinAdminFrontendBundle(),
            new AppBundle\AppBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
