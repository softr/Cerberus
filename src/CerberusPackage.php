<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus;


// Cerberus package

use \softr\cerberus\Cerberus;


/**
 * Cerberus package.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class CerberusPackage extends \mako\application\Package
{
    /**
     * Package name.
     *
     * @var string
     */

    protected $packageName = 'softr/cerberus';

    /**
     * Package namespace.
     *
     * @var string
     */

    protected $fileNamespace = 'cerberus';

    /**
     * Register the service.
     *
     * @access  protected
     */

    protected function bootstrap()
    {
        $this->container->registerSingleton(['softr\cerberus\Cerberus', 'cerberus'], function($container)
        {
            return new Cerberus($container);
        });
    }
}