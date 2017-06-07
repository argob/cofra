<?php

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
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
	    //----
	    new Framework\SeguridadBundle\FrameworkSeguridadBundle(),
	    new Framework\GeneralBundle\FrameworkGeneralBundle(),			
	    new Magyp\RendicionDeCajaBundle\MagypRendicionDeCajaBundle(),
	//    new Liuggio\ExcelBundle\LiuggioExcelBundle(),
	 // la saco del local por que no deja usar ventana symfony2   
	    new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
	    new Magyp\MensajeBundle\MagypMensajeBundle(), 
	    new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
	    
        );  

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
	    $bundles[] = new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle();
          //  $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
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
