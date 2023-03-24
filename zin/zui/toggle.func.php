<?php

namespace zin;

function toggle($name, $options = NULL)
{
    $props = array('data-toggle' => $name);
    if (is_array($options))
    {
        foreach ($options as $key => $value)
        {
            $props["data-$key"] = $value;
        }
    }
    return set($props);
}
