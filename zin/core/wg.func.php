<?php
namespace zin\core;

/**
 * @param $definition
 * @example
 *
 * $definition = 'name,desc:string,title?:string|element,icon?:string="star"'
 * $definition = array('name', 'desc:string', 'title?:string|element', 'icon?:string="star"');
 * $definition = array('name' => 'mixed', 'desc' => 'string', 'title' => array('type' => 'string|element', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
 */
function defineProps($definition)
{
    if(is_string($definition)) $definition = explode(',', $definition);

    $props = array();
    foreach($definition as $name => $value)
    {
        $optional = false;
        $type     = 'mixed';
        $default  = NULL;

        if(is_int($name) && is_string($value))
        {
            $value = trim($value);
            if(strpos($value, ':') === false)
            {
                $name = $value;
                $value = '';
            }
            else
            {
                list($name, $value) = explode(':', $value, 2);
            }
            $name = trim($name);
            if(strpos($name, '?') === 0)
            {
                $name = substr($name, 1);
                $optional = true;
            }
        }

        if(is_array($value))
        {
            $type     = isset($value['type'])    ? $value['type']    : $type;
            $default  = isset($value['default']) ? $value['default'] : $default;
            $optional = isset($value['optional'])? $value['optional']: $optional;
        }
        else if(is_string($value))
        {
            if(strpos($value, '=') === false)
            {
                $type = $value;
                $default = NULL;
            }
            else
            {
                list($type, $default) = explode('=', $value, 2);
            }
            $type = trim($type);

            if(is_string($default)) $default = json_decode(trim($default));
        }
        $props[$name] = array('type' => explode('|', $type), 'default' => $default, 'optional' => $default !== NULL || $optional);
    }
    return $props;
}

function extractArgs()
{

}
