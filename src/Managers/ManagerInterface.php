<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Managers;


/**
 * Permission Manager Interface.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

interface ManagerInterface
{
    /**
     * The unique ID to identify the caller with
     *
     * @return int
     */

    public function getManagerId();

    /**
     * The caller's permissions
     *
     * @return array
     */

    public function getManagerPermissions();

    /**
     * Set caller's permissions
     *
     * @return array
     */

    public function setManagerPermissions(array $permissions = []);
}
