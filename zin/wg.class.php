<?php
class wg
{
    public $url;
    public $method;
    public $trigger = '';
    public $target  = '';
    public $swap    = '';
    public $include = '';
    public $params  = '';

    public function get($url, $trigger = '', $target = '')
    {
        $this->method  = __FUNCTION__;
        $this->url     = $url;
        $this->trigger = $trigger;
        $this->target  = $target;

        return $this;
    }

    public function post($url, $trigger = '', $target = '')
    {
        $this->method  = __FUNCTION__;
        $this->url     = $url;
        $this->trigger = $trigger;
        $this->target  = $target;

        return $this;
    }

    public function trigger($trigger)
    {
        $this->trigger = $trigger;
        return $this;
    }

    public function target($target)
    {
        $this->target = $target;
        return $this;
    }

    public function swap($swap)
    {
        $this->swap = $swap;
        return $this;
    }

    public function include($include)
    {
        $this->include = $include;
        return $this;
    }

    public function params($params = '*')
    {
        $this->params = $params;
        return $this;
    }

    public function toHx()
    {
        $html   = '';
        $prefix = 'hx-';

        $properties = array_keys(get_class_vars(__CLASS__));

        foreach($properties as $property)
        {
            if(empty($this->$property)) continue;
            $html .= "{$prefix}{$property}='{$this->$property}' ";
        }

        return $html;
    }
}
