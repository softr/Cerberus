<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus;


use \mako\syringe\Container;

use \softr\cerberus\Managers\ManagerProvider;
use \softr\cerberus\Managers\ManagerInterface;
use \softr\cerberus\Restrictions\Rules;


/**
* Access Control List Class
*
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class Cerberus
{
    //---------------------------------------------
    // Class properties
    //---------------------------------------------

    /**
     * Request instance.
     *
     * @var \mako\syringe\Container
     */

    protected $container;

    /**
     * Declared rules collection.
     *
     * @var \softr\cerberus\Restrictions\Rules
     */

    private $rules;

    //---------------------------------------------
    // Class constructor, destructor etc ...
    //---------------------------------------------

    /**
     * Constructor.
     *
     * @access  public
     * @param   \mako\syringe\Container  $container  Container instance
     * @return  void
     */

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->rules = $this->loadRules();
    }

    //---------------------------------------------
    // Class methods
    //---------------------------------------------

    /**
     * Return caller provider.
     *
     * @access  public
     * @param   softr\cerberus\Managers\ManagerInterface  $callerInterface  Manager entity
     * @return  softr\cerberus\Managers\ManagerProvider
     */

    public function getManager(ManagerInterface $callerInterface)
    {
        $superUsers = (array) $this->container->get('config')->get('cerberus::config.superusers');

        return new ManagerProvider($callerInterface, $this->rules, $superUsers);
    }

    /**
     * Return avaliable groups list.
     *
     * @access  public
     * @return  array
     */

    public function getGroups()
    {
        return $this->rules->getGroups();
    }

    /**
     * Return avaliable rules list.
     *
     * @access  public
     * @return  array
     */

    public function getRules()
    {
        return $this->rules->getRules();
    }

    /**
     * Return avaliable grouped rules list.
     *
     * @access  public
     * @param   string  $group  Group identifier
     * @return  array
     */
    public function getGroupedRules($group)
    {
        return $this->rules->getGroupedRules($group);
    }

    /**
     * Return avaliable ungrouped rules list.
     *
     * @access  public
     * @return  array
     */

    public function getUngroupedRules()
    {
        return $this->rules->getUngroupedRules();
    }

    /**
     * Loads rules.
     *
     * @access  protected
     * @return  \mako\http\routing\Rules
     */

    protected function loadRules()
    {
        $loader = function($container, $rules)
        {
            if(file_exists($this->getRulesFile()))
            {
                include $this->getRulesFile();
            }

            return $rules;
        };

        // Rules collection

        $rules = new Rules();

        return $loader($this->container, $rules);
    }

    /**
     * Get rules file path.
     *
     * @access  private
     * @return  string
     */

    private function getRulesFile()
    {
        $defaultFile = $this->container->get('app')->getPath() . '/rules.php';

        $configFile = $this->container->get('config')->get('cerberus::config.rules_file');

        return empty($configFile) ? $defaultFile : $configFile;
    }
}