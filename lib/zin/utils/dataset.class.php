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
 * Manage dataset properties for html element and widgets.
 */
class dataset
{
    /**
     * Store dataset properties list in an array.
     *
     * @var    array
     * @access protected
     */
    protected array $_data = array();

    /**
     * Create an instance, the initialed data can be passed.
     *
     * @access public
     * @param array|object|string $data  Properties list array.
     * @param mixed               $value Property value.
     */
    public function __construct(array|string $data = null, mixed $value = null)
    {
        if($data !== null) $this->set($data, $value);
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
     * Get debug info.
     *
     * @access public
     * @return string
     */
    public function __debugInfo(): array
    {
        return $this->_data;
    }

    /**
     * Method for sub class to hook on setting it.
     *
     * @access protected
     * @param string    $prop         Property name or properties list.
     * @param mixed     $value        Property value.
     * @return dataset
     */
    protected function setVal(string $prop, mixed $value): dataset
    {
        $this->_data[$prop] = $value;
        return $this;
    }

    /**
     * Method for sub class to hook on getting it.
     *
     * @access protected
     * @param string $prop  Property name.
     * @return mixed
     */
    protected function getVal(string $prop): mixed
    {
        return isset($this->_data[$prop]) ? $this->_data[$prop] : null;
    }

    /**
     * Get properties count.
     *
     * @access public
     * @param bool $skipEmpty  Whether to skip to count empty value.
     * @return int
     */
    public function getCount(bool $skipEmpty = false): int
    {
        if(!$skipEmpty) return count($this->_data);

        $count = 0;
        foreach($this->_data as $value) if($value !== null) $count++;
        return $count;
    }

    /**
     * Get properties count.
     *
     * @deprecated Use getCount instead.
     * @access public
     * @param bool $skipEmpty  Whether to skip to count empty value.
     * @return int
     */
    public function count(bool $skipEmpty = false): int
    {
        return $this->getCount($skipEmpty);
    }

    /**
     * Convert dataset to json string.
     *
     * @access public
     * @return string
     */
    public function toStr(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert dataset to array.
     *
     * @access public
     * @return array
     */
    public function toArray(): array
    {
        return $this->_data;
    }

    /**
     * Convert dataset to json array.
     *
     * @deprecated Use toArray instead.
     * @access public
     * @return array
     */
    public function toJSON(): array
    {
        return $this->_data;
    }

    /**
     * Set property, an array can be passed to set multiple properties.
     *
     * @access public
     * @param array|object|string   $prop  Property name or properties list.
     * @param mixed                 $value Property value.
     * @return dataset
     */
    public function set(array|object|string $prop, mixed $value = null): dataset
    {
        if(is_string($prop)) return $this->setVal($prop, $value);

        if(is_object($prop)) $prop = get_object_vars($prop);
        if(is_array($prop))
        {
            foreach($prop as $name => $val) $this->set($name, $val);
        }
        return $this;
    }

    /**
     * Get property value by name, if the value not exists, return defaultValue.
     * If not property name passed, return all properties.
     *
     * @access public
     * @param string|array $prop          Property name.
     * @param mixed        $defaultValue  Optional default value if actual value is null.
     * @return mixed
     */
    public function get(string|array $prop = null, mixed $defaultValue = null): mixed
    {
        if(is_null($prop)) return $this->_data;

        if(is_array($prop))
        {
            $values = array();
            foreach($prop as $name)
            {
                $value = $this->getVal($name);
                $values[$name] = (is_null($value) && is_array($defaultValue)) ? (isset($defaultValue[$name]) ? $defaultValue[$name] : null) : $value;
            }
            return $values;
        }

        $val = $this->getVal($prop);
        return $val === null ? $defaultValue : $val;
    }

    /**
     * Add data to map.
     *
     * @access public
     * @param string $prop          Property name.
     * @param mixed  $map           Map data.
     * @return dataset
     */
    public function addToMap(string $prop, object|array $map): dataset
    {
        if($map instanceof dataset) $map = $map->toArray();
        elseif(is_object($map)) $map = get_object_vars($map);
        elseif(!is_array($map)) return $this;

        $this->set($prop, array_merge($this->get($prop, array()), $map));
        return $this;
    }

    /**
     * Get map value by name, if not exists, return an empty array.
     *
     * @access public
     * @param string $prop          Property name.
     * @return array
     */
    public function getMap(string $prop): array
    {
        return $this->get($prop, array());
    }

    /**
     * Remove value from map.
     *
     * @access public
     * @param string $prop          Property name.
     * @param mixed  $keys          Map keys.
     * @return mixed
     */
    public function removeFromMap(string $prop, string|array $keys)
    {
        $map = $this->getMap($prop);
        if(is_string($keys))
        {
            unset($map[$keys]);
        }
        elseif(is_array($keys))
        {
            foreach($keys as $key) unset($map[$key]);
        }
        return $this->set($prop, $map);
    }

    /**
     * Add value to array list.
     *
     * @access public
     * @param string $prop          Property name.
     * @param mixed  $values        Array list value.
     * @return mixed
     */
    public function addToList(string $prop, mixed $values): dataset
    {
        if(!is_array($values)) $values = array($values);

        $list = $this->getList($prop);
        return $this->set($prop, array_merge($list, $values));
    }

    /**
     * Remove value from array list.
     *
     * @access public
     * @param string $prop          Property name.
     * @param mixed  $keys          Array list keys.
     * @return mixed
     */
    public function removeFromList(string $prop, string|array $keys): dataset
    {
        $list = $this->getList($prop);
        if(is_string($keys))
        {
            unset($list[$keys]);
        }
        elseif(is_array($keys))
        {
            foreach($keys as $key) unset($list[$key]);
        }
        return $this->set($prop, $list);
    }

    /**
     * Get array list value by name, if not exists, return an empty array.
     *
     * @access public
     * @param string $prop          Property name.
     * @return mixed
     */
    public function getList(string $prop): array
    {
        return $this->get($prop, array());
    }

    /**
     * Delete property by name.
     *
     * @access public
     * @param string $prop  Property name.
     * @return dataset
     */
    public function remove(string $prop): dataset
    {
        return $this->setVal($prop, null);
    }

    /**
     * Clear all properties.
     *
     * @access public
     * @return dataset
     */
    public function clear(): dataset
    {
        $this->_data = array();
        return $this;
    }

    /**
     * Check whether has specified property.
     *
     * @access public
     * @param string $prop  Property name.
     * @return bool
     */
    public function has(string $prop): bool
    {
        return $this->getVal($prop) !== null;
    }

    /**
     * Clone a new instance.
     *
     * @access public
     * @return object
     */
    public function copy(): object
    {
        $className = get_called_class();
        return new $className($this->_data);
    }

    /**
     * Merge data to current dataset.
     *
     * @access public
     * @param array|object $data  Data to merge.
     * @return dataset
     */
    public function merge(array|object $data): dataset
    {
        if($data instanceof dataset)               return $this->set($data->toArray());
        if(is_object($data) && isset($data->data)) return $this->set($data->data);

        return $this->set($data);
    }
}
