<?php

/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Managers;

use \softr\cerberus\Managers\ManagerInterface;
use \softr\cerberus\Restrictions\Rules;


/**
 * Manager Provider
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class ManagerProvider
{
    //---------------------------------------------
    // Class properties
    //---------------------------------------------

    /**
     * Manager instance.
     *
     * @var \softr\cerberus\Managers\ManagerInterface
     */

    protected $manager;

    /**
     * Declared rules collection.
     *
     * @var \softr\cerberus\Restrictions\Rules
     */

    private $rules;

    /**
     * Manager stored permissions.
     *
     * @var array
     */

    private $permissions = [];

    /**
     * Array of Super User ids.
     *
     * @var array
     */

    private $superUsers = [];

    //---------------------------------------------
    // Class constructor, destructor etc ...
    //---------------------------------------------

    /**
     * Constructor.
     *
     * @access  public
     * @param   \softr\cerberus\Managers\ManagerInterface  $manager      Manager instance.
     * @param   \softr\cerberus\Restrictions\Rules       $rules       Declared rules collection.
     * @param   array                                    $superUsers  Array of Super User ids.
     * @return  void
     */

    public function __construct(ManagerInterface $manager, Rules $rules, array $superUsers = [])
    {
        $this->manager = $manager;

        $this->permissions = $this->getPermissions();

        $this->rules = $rules;

        $this->superUsers = $superUsers;
    }

    //---------------------------------------------
    // Class methods
    //---------------------------------------------

    /**
     * Determine if one or more rules are allowed
     *
     * @param   string   $rule        Rule identifier.
     * @param   string   $resource    (optional) Resource identifier.
     * @param   string   $resourceId  (optional) Resource id.
     * @return  boolean
     */

    public function can($rule, $resource = null, $resourceId = null)
    {
        // Validate is super user

        if($this->isSuperUser())
        {
            return true;
        }

        // Permissions array

        $managerPermissions = $this->getPermissions();

        // Check rule exists

        if($this->rules->hasRule($rule))
        {
            $rule = $this->rules->getRule($rule);

            if($resource && $rule->hasResource($resource))
            {
                $resource = $rule->getResource($resource);

                if($resource->hasIds() && $resourceId)
                {
                    return isset($managerPermissions[$rule->getRule()][$resource->getType()])
                           && in_array($resourceId, $managerPermissions[$rule->getRule()][$resource->getType()]);
                }

                return isset($managerPermissions[$rule->getRule()][$resource->getType()])
                       && !empty($managerPermissions[$rule->getRule()][$resource->getType()]);
            }

            return isset($managerPermissions[$rule->getRule()]) && !empty($managerPermissions[$rule->getRule()]);
        }

        return false;
    }

    /**
     * Determine all parsed rules are allowed
     *
     * @param   array    $rules  Rules array
     * @return  boolean
     */

    public function canMany(array $rules = [])
    {
        // Validate is super user

        if($this->isSuperUser())
        {
            return true;
        }

        $result = false;

        foreach($rules as $args)
        {
            $result = call_user_func_array(array($this, 'can'), $args);

            if($result === false)
            {
                return false;

                break;
            }
        }

        return $result;
    }

    /**
     * Determine if one or more rules are allowed
     *
     * @param   array    $rules  Rules array
     * @return  boolean
     */

    public function canOneOrMany(array $rules = [])
    {
        // Validate is super user

        if($this->isSuperUser())
        {
            return true;
        }

        $result = false;

        foreach($rules as $args)
        {
            $result = call_user_func_array(array($this, 'can'), $args);

            if($result === true)
            {
                return true;

                break;
            }
        }

        return $result;
    }

    /**
     * Determine if an rule isn't allowed
     *
     * @param   string   $rule        Rule identifier.
     * @param   string   $resource    (optional) Resource identifier.
     * @param   string   $resourceId  (optional) Resource id.
     * @return  boolean
     */

    public function cannot($rule, $resource = null, $resourceId = null)
    {
        return !$this->can($rule, $resource, $resourceId);
    }

    /**
     * Determine if an rule isn't allowed
     *
     * @param   array    $rules  Rules array
     * @return  boolean
     */

    public function cannotMany(array $rules = [])
    {
        return !$this->canMany($rules);
    }

    /**
     * Determine if an rule isn't allowed
     *
     * @param   array    $rules  Rules array
     * @return  boolean
     */

    public function cannotOneOrMany(array $rules = [])
    {
        return !$this->canOneOrMany($rules);
    }

    /**
     * Give the manager permission to do something.
     *
     * @param   string   $rule  Rule identifier.
     * @param   mixed    $data  Permission data.
     * @return  void
     */

    public function allow($rule, $data)
    {
        $this->permissions[$rule] = $data;
    }

    /**
     * Deny the manager from doing something.
     *
     * @param   string   $rule  Rule identifier.
     * @return  void
     */

    public function deny($rule)
    {
        $this->permissions[$rule] = null;
    }

    /**
     * Clear a given permission from manager
     *
     * @param   string    $rule  Permission to be cleared
     * @return  void
     */

    public function clearPermission($rule)
    {
        if(isset($this->permissions[$rule]))
        {
            unset($this->permissions[$rule]);
        }
    }

    /**
     * Return manager stored permissions
     *
     * @access  public
     * @return  array
     */

    public function storePermissions(array $permissions = [])
    {
        // Reload permissions

        $this->permissions = $this->getPermissions();

        foreach($this->rules->getRules() as $rule)
        {
            $this->clearPermission($rule->getRule());

            if(isset($permissions[$rule->getRule()]) && $permissions[$rule->getRule()] == true)
            {
                $this->allow($rule->getRule(), $permissions[$rule->getRule()]);
            }
            else
            {
                $this->deny($rule->getRule());
            }
        }

        // Persist permission

        $this->manager->setManagerPermissions($this->permissions);
    }

    /**
     * Return manager stored permissions
     *
     * @access  private
     * @return  array
     */

    private function getPermissions()
    {
        return $this->manager->getManagerPermissions();
    }

    /**
     * Determine if manager is a super user
     *
     * @access  private
     * @return  boolean
     */

    private function isSuperUser()
    {
        return in_array($this->manager->getManagerId(), $this->superUsers);
    }
}