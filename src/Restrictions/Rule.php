<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Restrictions;

use Closure;

use softr\cerberus\Resources\Resource;


/**
 * Rule.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class Rule
{
    /**
     * Rule.
     *
     * @var string
     */

    protected $rule;

    /**
     * Rule name.
     *
     * @var string
     */

    protected $name;

    /**
     * Rule resources collection.
     *
     * @var array
     */

    protected $resources = [];

    /**
     * Rule group.
     *
     * @var string
     */

    protected $group;

    /**
     * Constructor.
     *
     * @access  public
     * @param   string          $rule       Rule
     * @param   string          $name       Rule name
     * @param   string|Closure  $resources  (optional) Rule resources
     * @param   string          $group      (optional) Rule group
     */

    public function __construct($rule, $name, Closure $resources = null)
    {
        $this->rule = $rule;

        $this->name = $name;

        // Inject resources

        if($resources instanceof Closure)
        {
            $resources($this);
        }
    }

    /**
     * Returns the rule.
     *
     * @access  public
     * @return  string
     */

    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Returns the rule name.
     *
     * @access  public
     * @return  string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the resources.
     *
     * @access  public
     * @return  aray
     */

    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Return resource by identifier.
     *
     * @access  public
     * @param   string   $rule  Rule identifier
     * @return  array
     */
    public function getResource($resource)
    {
        if(!isset($this->resources[$resource]))
        {
            throw new RuntimeException(vsprintf("%s(): No resource [ %s ] has been defined.", [__METHOD__, $resource]));
        }

        return $this->resources[$resource];
    }

    /**
     * Returns TRUE if the resource exists and FALSE if not.
     *
     * @access  public
     * @param   string   $rule  Rule identifier.
     * @return  boolean
     */

    public function hasResource($resource)
    {
        return isset($this->resources[$resource]);
    }

    /**
     * Returns TRUE if the resource exists and FALSE if not.
     *
     * @access  public
     * @param   string   $rule  Rule identifier.
     * @return  boolean
     */

    public function hasResources()
    {
        return !empty($this->resources);
    }

    /**
     * Returns TRUE if the rule belongs to any group and FALSE if not.
     *
     * @access  public
     * @return  boolean
     */

    public function isGrouped()
    {
        return !empty($this->group);
    }

    /**
     * Returns TRUE if the rule belongs to a given group and FALSE if not.
     *
     * @access  public
     * @param   string  $group  Group identifier
     * @return  boolean
     */

    public function belongsToGroup($group)
    {
        return $this->group == $group;
    }

    /**
     * Sets the rule group.
     *
     * @access  public
     * @param   string                           $group  Group
     * @return  \softr\cerberus\Restrictions\Rule
     */

    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * Returns TRUE if the rule allows the specified method or FALSE if not.
     *
     * @access  public
     * @param   string   $type  Resource Type
     * @param   string   $name  Resource Name
     * @param   string   $ids   Resource Ids
     * @return  boolean
     */

    public function addResource($type, $name, $ids = null)
    {
        $this->resources[$type] = new Resource($type, $name, $ids);
    }
}