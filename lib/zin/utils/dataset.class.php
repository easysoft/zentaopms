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
class dataset implements \JsonSerializable
{
    /**
     * Store dataset properties list in an array.
     *
     * @var    array
     * @access protected
     */
    protected array $storedData = array();

    /**
    * Parent node
    *
    * @access public
    * @var    node
    */
    public $parent = null;

    /**
     * Create an instance, the initialed data can be passed.
     *
     * @access public
     * @param array|object|string $data  Properties list array.
     * @param mixed               $value Property value.
     */
    public function __construct($data = null, $value = null)
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
        return $this->storedData;
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
        $this->storedData[$prop] = $value;
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
        return isset($this->storedData[$prop]) ? $this->storedData[$prop] : null;
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
        if(!$skipEmpty) return count($this->storedData);

        $count = 0;
        foreach($this->storedData as $value) if($value !== null) $count++;
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
        return $this->storedData;
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
        return $this->storedData;
    }

    /**
     * Serialized to JSON.
     * 序列化为 JSON。
     *
     * @access public
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Set property, an array can be passed to set multiple properties.
     *
     * @access public
     * @param  array|object|string|int $prop  Property name or properties list.
     * @param  mixed                   $value Property value.
     * @return dataset
     */
    public function set(array|object|string|int $prop, mixed $value = null): dataset
    {
        if(is_int($prop)) $prop = (string)$prop;
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
    public function get($prop = null, mixed $defaultValue = null): mixed
    {
        if(is_null($prop)) return $this->storedData;

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

        $this->set($prop, array_merge($this->getMap($prop), $map));
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
    public function removeFromMap(string $prop, string ...$keys)
    {
        $map = $this->getMap($prop);
        foreach($keys as $key) unset($map[$key]);
        return $this->setVal($prop, $map);
    }

    /**
     * Add value to array list.
     *
     * @access public
     * @param string    $prop          Property name.
     * @param mixed  ...$item          Item Value to add to list.
     * @return mixed
     */
    public function addToList(string $prop, mixed $item, ?string $key = null): dataset
    {
        $list = $this->getList($prop);
        if($key) $list[$key] = $item;
        else     $list[] = $item;
        return $this->set($prop, $list);
    }

    /**
     * Merge value to array list.
     *
     * @access public
     * @param string    $prop          Property name.
     * @param mixed     $newList       Values to add to list.
     * @return mixed
     */
    public function mergeToList(string $prop, $newList): dataset
    {
        $list = $this->getList($prop);
        return $this->set($prop, array_merge($list, $newList));
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
     * Get string value, if not value exists, return an empty string.
     *
     * @access public
     * @param string $prop          Property name.
     * @return string
     */
    public function getStr(string $prop): string
    {
       $val = $this->get($prop);
       if(is_null($val)) return '';
       return (string)$val;
    }

    /**
     * Append string to property.
     *
     * @access public
     * @param string $prop    Property name.
     * @param string $str     String to append.
     * @param string $joiner  Joiner string.
     * @return dataset
     */
    public function appendToStr(string $prop, string $str, string $joiner = ''): dataset
    {
        $val = $this->getStr($prop);
        if(strlen($val) > 0) $val .= $joiner . $str;
        else                 $val  = $str;
        return $this->set($prop, $val);
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
        $this->storedData = array();
        return $this;
    }

    /**
     * Check whether the specified property is not null.
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
     * Check whether has specified property.
     *
     * @access public
     * @param string $prop  Property name.
     * @return bool
     */
    public function isset(string $prop): bool
    {
        return isset($this->storedData[$prop]);
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
        return new $className($this->storedData);
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
