<?php
declare(strict_types=1);
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
    public array $data = array();

    /**
     * Create an instance, the initialed data can be passed
     *
     * @access public
     * @param array $data - Properties list array
     */
    public function __construct(?array $data = null)
    {
        if($data !== null) $this->set($data);
    }

    /**
     * Override __set
     *
     * @access public
     * @param string $prop  - Property name
     * @param mixed  $value - Property value
     * @return void
     */
    public function __set(string $name, mixed $value)
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
    public function __get(string $name)
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
    public function __isset(string $name): bool
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
    public function __unset(string $name)
    {
        $this->remove($name);
    }

    /**
     * Convert dataset to json string
     *
     * @access public
     * @return string
     */
    public function __toString(): string
    {
        return $this->toStr();
    }

    /**
     * Override __invoke
     *
     * @access public
     * @return string
     */
    public function __invoke(string|array $name = null, mixed $value = null): string
    {
        if($value !== null || is_array($name)) return $this->set($name, $value);
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
    public function __call(string $name, array $args): mixed
    {
        if(count($args)) return $this->set($name, $args[0]);

        return $this->get($name);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access protected
     * @param string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @return dataset
     */
    protected function setVal(string $prop, mixed $value): dataset
    {
        $this->data[$prop] = $value;
        return $this;
    }

    protected function getVal(string $prop): mixed
    {
        return isset($this->data[$prop]) ? $this->data[$prop] : null;
    }

    /**
     * Get properties count
     *
     * @access public
     * @param bool $skipEmpty - Whether to skip to count empty value
     * @return int
     */
    public function count($skipEmpty = false): int
    {
        if(!$skipEmpty) return count($this->data);

        $count = 0;
        foreach($this->data as $value)
        {
            if($value !== null) $count++;
        }
        return $count;
    }

    /**
     * Convert dataset to json string
     *
     * @access public
     * @return string
     */
    public function toStr(): string
    {
        return json_encode($this->toJsonData());
    }

    public function toJsonData(): array
    {
        return $this->data;
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @return dataset
     */
    public function set(array|string $prop, mixed $value = null): dataset
    {
        if(is_array($prop))
        {
            foreach($prop as $name => $val) $this->set($name, $val);
            return $this;
        }

        return $this->setVal($prop, $value);
    }

    /**
     * Get property value by name
     *
     * @access public
     * @param string $prop         - Property name
     * @param mixed  $defaultValue - Optional default value if actual value is null
     * @return mixed
     */
    public function get($prop, $defaultValue = null)
    {
        $val = $this->getVal($prop);
        return $val === null ? $defaultValue : $val;
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

    public function list($prop, $values = null)
    {
        if($values === null) return $this->getList($prop);
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
        return $this->setVal($prop, null);
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
        return $this->getVal($prop) !== null;
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
