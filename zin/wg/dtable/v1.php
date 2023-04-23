<?php
namespace zin;

class dtable extends wg
{
    static $defineProps = 'className?:string="shadow-sm rounded", id?:string';

    static $dtableID = 0;

    protected function created()
    {
        $this->setDefaultProps(['id' => static::$dtableID ? static::$dtableID : 'dtable']);
        static::$dtableID++;
    }

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
