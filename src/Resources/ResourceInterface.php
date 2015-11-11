<?php
/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Resources;


/**
 * Resource Interface.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

interface ResourceInterface
{
    /**
     * The string value for the type of resource
     *
     * @return string
     */

    public function getType();

    /**
     * The string value for the name of resource
     *
     * @return string
     */

    public function getName();

    /**
     * The allowed ids of resources
     *
     * @return array|null
     */

    public function getIds();
}