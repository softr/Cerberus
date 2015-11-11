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

class CerberusService extends \mako\application\services\Service
{
    /**
     * Registers the service.
     *
     * @access  public
     */

    public function register()
    {
        $this->container->registerSingleton(['softr\cerberus\Cerberus', 'cerberus'], function($container)
        {
            return new Cerberus($container);
        });
    }
}