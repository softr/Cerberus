<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Restrictions;

use Closure;
use RuntimeException;

use softr\cerberus\Restrictions\Rule;

/**
 * Rule collection.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class Rules
{
    /**
     * Rule groups.
     *
     * @var array
     */

    protected $groups = [];

    /**
     * Registered rules.
     *
     * @var array
     */

    protected $rules = [];

    /**
     * Registered grouped rules.
     *
     * @var array
     */

    protected $groupedRules = [];

    /**
     * Registered ungrouped rules.
     *
     * @var array
     */

    protected $ungroupedRules = [];

    /**
     * Return rule by identifier.
     *
     * @access  public
     * @param   string   $rule  Rule identifier
     * @return  array
     */
    public function getRule($rule)
    {
        if(!isset($this->rules[$rule]))
        {
            throw new RuntimeException(vsprintf("%s(): No rule named [ %s ] has been defined.", [__METHOD__, $rule]));
        }

        return $this->rules[$rule];
    }

    /**
     * Return avaliable groups list.
     *
     * @access  public
     * @return  array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Return avaliable rules list.
     *
     * @access  public
     * @return  array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Returns TRUE if the named rule exists and FALSE if not.
     *
     * @access  public
     * @param   string   $rule  Rule identifier.
     * @return  boolean
     */

    public function hasRule($rule)
    {
        return isset($this->rules[$rule]);
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
        $arr = [];

        foreach($this->groupedRules as $rule)
        {
            if($this->rules[$rule]->belongsToGroup($group))
            {
                $arr[$rule] = $this->rules[$rule];
            }
        }

        return $arr;
    }

    /**
     * Return avaliable ungrouped rules list.
     *
     * @access  public
     * @return  array
     */
    public function getUngroupedRules()
    {
        $arr = [];

        foreach($this->ungroupedRules as $rule)
        {
            $arr[$rule] = $this->rules[$rule];
        }

        return $arr;
    }

    /**
     * Adds a grouped set of rules to the colleciton.
     *
     * @access  public
     * @param   string     $options  Group
     * @param   string     $options  Group name
     * @param   \Closure   $rules    Rule closure
     */

    public function group($group, $name, Closure $rules)
    {
        $groups = $this->groups;

        array_pop($this->groups);

        $this->groups[$group] = $name;

        $rules($this);

        $this->groups = $groups;
        $this->groups[$group] = $name;
    }

    /**
     * Adds a rule.
     *
     * @access  public
     * @param   string          $rule       Rule
     * @param   string          $name       Rule name
     * @param   string|Closure  $resources  (optional) Rule resources
     */

    public function add($rule, $name, Closure $resources = null)
    {
        $rule = new Rule($rule, $name, $resources);

        if(!empty($this->groups))
        {
            foreach($this->groups as $group => $name)
            {
                $rule->setGroup($group);
            }
        }

        $this->rules[$rule->getRule()] = $rule;

        if($rule->isGrouped())
        {
            $this->groupedRules[] = $rule->getRule();
        }
        else
        {
            $this->ungroupedRules[] = $rule->getRule();
        }

        return $rule;
    }
}