<?php
class wg
{
    public $hxProperties;

    public function __construct()
    {
        $this->hxProperties = new stdClass();
    }

    public function get($url, $trigger = '', $target = '')
    {
        $this->hxProperties->method  = __FUNCTION__ ;
        $this->hxProperties->url     = $url;
        $this->hxProperties->trigger = $trigger;
        $this->hxProperties->target  = $target;

        return $this;
    }

    public function post($url, $trigger = '', $target = '')
    {
        $this->hxProperties->method  = __FUNCTION__ ;
        $this->hxProperties->url     = $url;
        $this->hxProperties->trigger = $trigger;
        $this->hxProperties->target  = $target;

        return $this;
    }

    public function trigger($trigger)
    {
        $this->hxProperties->trigger = $trigger;
        return $this;
    }

    public function targetHx($target)
    {
        $this->hxProperties->target = $target;
        return $this;
    }

    public function swap($swap)
    {
        $this->hxProperties->swap = $swap;
        return $this;
    }

    public function include($include)
    {
        $this->hxProperties->include = $include;
        return $this;
    }

    public function params($params = '*')
    {
        $this->hxProperties->params = $params;
        return $this;
    }

    public function toHx()
    {
        $properties = $this->hxProperties;
        $prefix     = 'hx-';

        if(empty($properties->method) || empty($properties->url)) return '';

        $html = "{$prefix}{$properties->method}='{$properties->url}'";

        unset($properties->method, $properties->url);

        foreach($properties as $property => $item)
        {
            if(empty($item)) continue;
            $html .= "{$prefix}{$property}='{$item}' ";
        }

        return $html;
    }
}
