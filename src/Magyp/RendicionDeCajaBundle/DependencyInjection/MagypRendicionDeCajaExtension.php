<?php

namespace Magyp\RendicionDeCajaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MagypRendicionDeCajaExtension extends Extension
{
	private $config;
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
		
		$this->config = $config;
		$container->setParameter('cofra.login_error.bloqueo_intentos', $config['login_error']['bloqueo_intentos']);
		$container->setParameter('cofra.arg_config', $config);
		//echo $container->getParameter('cofra.login_error.bloqueo_intentos');
		
		
		//var_dump($config['login_error']['bloqueo_intentos']);
	//	var_dump($container->getParameter('kernel.bundles'));
		//var_dump($this->getConfig());
	//	var_dump($configs);
		

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
	//	var_dump($loader);
		//var_dump($container);

		
		//var_dump($cofraconfig);  
		//die;	
	
    }
	
	public function getConfig() {
		return $this->config;
	}


}
