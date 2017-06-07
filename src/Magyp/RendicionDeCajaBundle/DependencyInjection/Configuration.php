<?php

namespace Magyp\RendicionDeCajaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('magyp_rendicion_de_caja');
        //$rootNode = $treeBuilder->root('cofra');
		
		$rootNode
				->children()
					->append($this->addLoginError())
					->append($this->addSession())
				->end()
				;

        return $treeBuilder;
    }
	
	private function addLoginError(){
		
		$arbolBuilder = new TreeBuilder();
		$nodo = $arbolBuilder->root('login_error');
		
		$nodo
			->children()
//				->scalarNode('')->isRequired()->cannotBeEmpty()->end()
				->scalarNode('bloqueo_intentos')->defaultValue(5)->end()
				->booleanNode('aviso_mail_bloqueo')->defaultTrue()->end()
				->scalarNode('logueo_fallido_aviso')->defaultValue(3)->end()
				->booleanNode('aviso_mail_intentos_fallidos')->defaultTrue()->end()
			->end()
				;
		return $nodo;
	}
	private function addSession(){
		
		$arbolBuilder = new TreeBuilder();
		$nodo = $arbolBuilder->root("session");
		
		$nodo
			->children()
				->booleanNode('multiple_session')->defaultFalse()->end()
				->booleanNode('doble_session_aviso_mail')->defaultFalse()->end()
			->end()
			;
		return $nodo;
	}
}
