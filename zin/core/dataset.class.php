<?php
/**
 * The dataset class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

/**
 * Manage dataset properties for html element and widgets
 */
class dataset
{
    /**
     * Store dataset properties list in an array
     *
     * @var    array
     * @access public
     */
    public $data = array();

    /**
     * Create an instance, the initialed data can be passed
     *
     * @access public
     * @param array $data - Properties list array
     */
    public function __construct($data = NULL)
    {
        if($data !== NULL) $this->set($data);
    }

    /**
     * Override __set
     *
     * @access public
     * @param string $prop  - Property name
     * @param mixed  $value - Property value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Override __get
     *
     * @access public
     * @param string $prop - Property name
     * @return mixed
     */
    public function __get($name)
    {
        $this->get($name);
    }

    /**
     * Override __isset
     *
     * @access public
     * @param string $prop - Property name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Override __unset
     *
     * @access public
     * @param string $prop - Property name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * Convert dataset to json string
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->toStr();
    }

    /**
     * Override __invoke
     *
     * @access public
     * @return string
     */
    public function __invoke($name = NULL, $value = NULL)
    {
        if($value !== NULL || is_array($name)) return $this->set($name, $value);
        if(is_string($name)) return $this->get($name);

        return $this->toStr();
    }

    /**
     * Override __call for setting property conveniently
     *
     * Example:
     *
     *     $dataset = dataset::new();
     *
     *     // Set color property
     *     $dataset->color('red');
     *
     *     // Get color property
     *     echo $dataset->color(); // Output "Red"
     *
     * @access public
     * @return mixed
     */
    public function __call($name, $args)
    {
        if(count($args)) return $this->set($name, $args[0]);

        return $this->get($name);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return dataset
     */
    protected function setVal($prop, $value, $removeEmpty = false)
    {

        if($value === NULL || ($removeEmpty && empty($value))) return $this->remove($prop);

        $this->data[$prop] = $value;
        return $this;
    }

    /**
     * Get properties count
     *
     * @access public
     * @param bool $skipEmpty - Whether to skip to count empty value
     * @return int
     */
    public function count($skipEmpty = false)
    {
        if(!$skipEmpty) return count($this->data);

        $count = 0;
        foreach($this->data as $value)
        {
            if(!empty($value)) $count++;
        }
        return $count;
    }

    /**
     * Convert dataset to json string
     *
     * @access public
     * @return string
     */
    public function toStr()
    {
        return json_encode($this->data);
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return dataset
     */
    public function set($prop, $value = NULL, $removeEmpty = false)
    {
        if(is_array($prop))
        {
            foreach($prop as $name => $value) $this->set($name, $value, $removeEmpty);
            return $this;
        }

        $value = $this->setVal($prop, $value, $removeEmpty);
        return $this;
    }

    /**
     * Set property by condition
     *
     * @access public
     * @param mixed          $condition - Condition value
     * @param array|string   $prop      - Property name or properties list
     * @param mixed          $value     - Property value
     * @return dataset
     */
    public function setIf($condition, $prop, $value)
    {
        if($condition) $this->set($prop, $value);
        return $this;
    }

    /**
     * Get property value by name
     *
     * @access public
     * @param string $prop         - Property name
     * @param mixed  $defaultValue - Optional default value if actual value is null
     * @return mixed
     */
    public function get($prop, $defaultValue = NULL)
    {
        $value = isset($this->data[$prop]) ? $this->data[$prop] : NULL;
        return $value === NULL ? $defaultValue : $value;
    }

    /**
     * Delete property by name
     *
     * @access public
     * @param string $prop - Property name
     * @return dataset
     */
    public function remove($prop)
    {
        if(isset($this->data[$prop])) unset($this->data[$prop]);
        return $this;
    }

    /**
     * Delete property by name
     *
     * @access public
     * @param mixed  $condition - Condition value
     * @param string $prop      - Property name
     * @return dataset
     */
    public function removeIf($condition, $prop)
    {
        if($condition) $this->remove($prop);
        return $this;
    }

    /**
     * Check whether has specified property
     *
     * @access public
     * @param string $prop - Property name
     * @return boolean
     */
    public function has($prop)
    {
        return isset($this->data[$prop]) && $this->data[$prop] !== NULL;
    }

    /**
     * Clone a new instance
     *
     * @access public
     * @return object
     */
    public function clone()
    {
        return new dataset($this->data);
    }

    public function merge($data)
    {
        if(is_object($data) && isset($data->data)) return $this->set($data->data);
        return $this->set($data);
    }
}
