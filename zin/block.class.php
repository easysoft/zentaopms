<?php
class block
{
    public $children = array();

    private $type = 'v';

    public function __contruct($type)
    {
        global $app, $config, $lang;

        $this->lang   = $lang;
        $this->config = $config;
        $this->app    = $app;
        $this->type   = $type;
    }

    public function __set($attr, $value)
    {
        $this->children[$attr] = $value;
    }

    public function x()
    {
        foreach($this->children as $key => $child)
        {
            echo $child->toString();
        }
    }
}

function block($type)
{
    return new block($type);
}
