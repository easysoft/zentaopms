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

    public function field(string $name): field
    {
        $field = $this->get($name);
        if(is_null($field))
        {
            $field = new field($name, $this);
            $this->add($field);
        }
        return $field;
    }

    public function get(string $name): field|null
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    public function add(field $field): fieldList
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    public function merge(string|array|field|fieldList|null $info): fieldList
    {
        if(is_null($info)) return $this;

        if(is_string($info))
        {
            $list = array();
            foreach(explode(',', $info) as $name)
            {
                $list[] = static::getByName($name);
            }
            $info = $list;
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
        return $this->fields;
    }

    public function toArray(): array
    {
        $list = array();
        foreach($this->toList() as $field)
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

    public static function getList(string $name): ?fieldList
    {
        return isset(static::$map[$name]) ? static::$map[$name] : null;
    }

    public static function getListField(string $listName, string $name = null): ?field
    {
        if(is_null($name)) list($listName, $name) = explode('/', $listName);
        if(is_null($name)) return null;

        $fieldList = static::getList($listName);
        return is_null($fieldList) ? null : $fieldList->get($name);
    }

    public static function getByName(string $name): field|fieldList|null
    {
        if(str_ends_with($name, '/')) return static::getList(substr($name, 0, -1));
        if(str_contains($name, '/')) return static::getListField($name);
        return static::getList($name);
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
