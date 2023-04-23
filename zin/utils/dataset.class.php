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

namespace zin\utils;

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
        return $this->has($name);
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
        $this->remove($name);
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
     * @return dataset
     */
    protected function setVal($prop, $value)
    {
        $this->data[$prop] = $value;
        return $this;
    }

    protected function getVal($prop)
    {
        return isset($this->data[$prop]) ? $this->data[$prop] : NULL;
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
        return json_encode($this->toJsonData());
    }

    public function toJsonData() {
        return $this->data;
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
    public function set($prop, $value = NULL)
    {
        if(is_array($prop))
        {
            foreach($prop as $name => $val) $this->set($name, $val);
            return $this;
        }

        $value = $this->setVal($prop, $value);
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
        $val = $this->getVal($prop);
        return $val === NULL ? $defaultValue : $val;
    }

    public function addToList($prop, $values)
    {
        if(!is_array($values)) $values = array($values);

        $list = $this->getList($prop);
        $this->set($prop, array_merge($list, $values));
    }

    public function getList($prop)
    {
        return $this->get($prop, array());
    }

    public function list($prop, $values = NULL)
    {
        if($values === NULL) return $this->getList($prop);
        return $this->setList($prop, $values);
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
        return $this->setVal($prop, NULL);
    }

    public function clear()
    {
        $this->data = array();
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
        return $this->getVal($prop) !== NULL;
    }

    /**
     * Clone a new instance
     *
     * @access public
     * @return object
     */
    public function clone()
    {
        $className = get_called_class();
        return new $className($this->data);
    }

    public function merge($data)
    {
        if(is_object($data) && isset($data->data)) return $this->set($data->data);
        return $this->set($data);
    }
}
