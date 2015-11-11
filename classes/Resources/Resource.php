<?php
/**
 * @copyright  Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

namespace softr\cerberus\Resources;


/**
 * Resource.
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2015
 */

class Resource implements \softr\cerberus\Resources\ResourceInterface
{
    /**
     * Resource type
     *
     * @var array
     */

    private $type;

    /**
     * Resource name
     *
     * @var array
     */

    private $name;

    /**
     * Allowed Ids
     *
     * @var array
     */

    private $ids;

    /**
     * Constructor.
     *
     * @param   string   $type  Resource Type
     * @param   string   $name  Resource Name
     * @param   string   $ids   Resource Ids
     */

    public function __construct($type, $name, $ids = [])
    {
        $this->type = $type;

        $this->name = $name;

        $this->ids = $ids;
    }

    /**
     * The string value for the type of resource
     *
     * @return string
     */

    public function getType()
    {
        return $this->type;
    }

    /**
     * The string value for the name of resource
     *
     * @return string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Return TRUE if resource has ids and FALSE if not.
     *
     * @return boolean
     */

    public function hasIds()
    {
        return !empty($this->getIds());
    }

    /**
     * The allowed ids of resources
     *
     * @return array|null
     */

    public function getIds()
    {
        if($this->ids instanceof \Closure)
        {
            $ids = $this->ids;

            return $ids($this);
        }

        return (array)$this->ids;
    }
}