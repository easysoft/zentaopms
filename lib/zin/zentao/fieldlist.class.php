<?php

declare(strict_types=1);
/**
 * The field list class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'field.class.php';

class fieldList
{
    public string $name;

    public array $fields = array();

    public ?array $labelData = null;

    public ?array $valueData = null;

    public function __construct(?string $name = null, ?array $fields = null)
    {
        if(is_null($name))
        {
            $this->name = '';
        }
        else
        {
            $this->name = $name;

            if(isset(static::$map[$name]))
            {
                trigger_error("[ZIN] The fieldList named \"$name\" already exist.", E_USER_ERROR);
            }

            static::$map[$name] = $this;
        }

        if(is_array($fields))
        {
            foreach($fields as $field)
            {
                $this->add($field);
            }
        }
    }

    /**
     * Magic method for using field.
     *
     * @access public
     * @param  string $name  - field name.
     * @return field
     */
    public function __call(string $name, array $args): field
    {
        return $this->field($name);
    }

    public function field(string $name, array|object|null $fieldProps = null): field
    {
        $field = $this->get($name);
        if(is_null($field))
        {
            $field = new field($name, $this);
            $this->add($field);
        }
        if(!is_null($fieldProps)) $field->set($fieldProps);
        return $field;
    }

    public function get(string $name): field|null
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    public function add(field|array|stdClass $field): fieldList
    {
        if(!($field instanceof field)) $field = get_object_vars($field);
        if(is_array($field))           $field = new field($field);

        $this->fields[$field->getName()] = $field;
        return $this;
    }

    public function merge(string|array|field|fieldList|null $info): fieldList
    {
        if(is_null($info)) return $this;

        if(is_string($info))
        {
            if(str_starts_with($info, '!'))
            {
                return $this->remove(substr($info, 1));
            }

            if(str_contains($info, '/'))
            {
                $info = static::getListFields($info);
            }
            else
            {
                $listNames = explode(',', $info);
                $info = array();
                foreach($listNames as $listName)
                {
                    $fieldList = static::getList($listName);
                    if($fieldList) $info[] = $fieldList;
                }
            }
        }

        if(is_array($info))
        {
            foreach($info as $field)
            {
                $this->merge($field);
            }
            return $this;
        }

        if($info instanceof field)     return $this->mergeField($info);
        if($info instanceof fieldList) return $this->mergeList($info);
        return $this;
    }

    public function mergeField(field $field): fieldList
    {
        $oldField = $this->get($field->getName());
        if(is_null($oldField)) return $this->add($field);

        $oldField->merge($field);
        return $this;
    }

    public function mergeList(fieldList $fieldList): fieldList
    {
        foreach($fieldList->fields as $field)
        {
            if(is_null($field)) continue;
            $this->mergeField($field);
        }
        return $this;
    }

    public function remove(string|array $names): fieldList
    {
        if(is_string($names)) $names = explode(',', $names);
        foreach($names as $name) unset($this->fields[$name]);
        return $this;
    }

    public function moveBefore(string|array $names, string $beforeName): fieldList
    {
        return $this;
    }

    public function moveAfter(string|array $names, string $beforeName): fieldList
    {
        return $this;
    }

    public function insertBefore(string|array $names, string $beforeName): fieldList
    {
        return $this;
    }

    public function insertAfter(string|array $names, string $beforeName): fieldList
    {
        return $this;
    }

    public function sort(string|array $names): fieldList
    {
        return $this;
    }

    public function batchSet(string|array $names, string|array $prop, mixed $value = null): fieldList
    {
        return $this;
    }

    public function setLabelData(array|object $labelData): fieldList
    {
        if(is_object($labelData)) $labelData = get_object_vars($labelData);
        $this->labelData = $labelData;
        return $this;
    }

    public function setValueData(array|object $valueData): fieldList
    {
        if(is_object($valueData)) $valueData = get_object_vars($valueData);
        $this->valueData = $valueData;
        return $this;
    }

    public function toList(string|array|null $names = null): array
    {
        if(is_null($names)) return $this->fields;

        if(is_string($names)) $names = explode(',', $names);

        $list = array();
        foreach($names as $name)
        {
            $field = $this->get($name);
            if($field) $list[$name] = $field;
        }
        return $list;
    }

    public function toArray(string|array|null $names = null): array
    {
        $list = array();
        foreach($this->toList($names) as $field)
        {
            $list[$field->getName()] = $field->toArray();
        }
        return $list;
    }

    protected static array $map = array();

    public static ?string $currentName = null;

    public static function define(string $currentName, string|array|field|fieldList|null ...$args): fieldList
    {
        static::$currentName = $currentName;
        $fieldList = static::ensure($currentName);

        if(!empty($args)) static::extend($fieldList, ...$args);
        return $fieldList;
    }

    public static function getList(string $listName): ?fieldList
    {
        return isset(static::$map[$listName]) ? static::$map[$listName] : null;
    }

    public static function getListFields(string $listName, string $fieldNames = null): array
    {
        if(is_null($fieldNames)) list($listName, $fieldNames) = explode('/', $listName);
        if(is_null($fieldNames)) return null;

        $fieldList = static::getList($listName);
        return is_null($fieldList) ? array() : $fieldList->toList($fieldNames);
    }

    public static function ensure(string $name): fieldList
    {
        if(isset(static::$map[$name])) return static::$map[$name];
        return new fieldList($name);
    }

    public static function current(): fieldList
    {
        if(is_null(static::$currentName))
        {
            trigger_error("[ZIN] The current fieldList name is not defined.", E_USER_ERROR);
        }
        return static::ensure(static::$currentName);
    }

    public static function extend(fieldList $fieldList, string|array|field|fieldList|null ...$args): fieldList
    {
        foreach($args as $arg)
        {
            if(is_null($arg)) continue;
            $fieldList->merge($arg);
        }
        return $fieldList;
    }

    public static function build(string|array|field|fieldList|null ...$args): fieldList
    {
        $fieldList = new fieldList();
        return static::extend($fieldList, ...$args);
    }
}
