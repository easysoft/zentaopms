<?php
class wg
{
    private $v;
    private $classFile;
    private $inited = false;

    static public function factory($wgType, $text)
    {
        include 'wg' . DS . $wgType;
        return $this;
    }
}
