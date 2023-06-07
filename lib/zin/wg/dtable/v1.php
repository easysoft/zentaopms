<?php
namespace zin;

class dtable extends wg
{
    static $defineProps = array(
        'className?:string="shadow-sm rounded"', // 表格样式。
        'id?:string',                            // ID。
        'customCols?: bool|array'                // 是否支持自定义列。
    );

    static $dtableID = 0;

    protected function created()
    {
        $this->setDefaultProps(array('id' => static::$dtableID ? static::$dtableID : 'dtable'));
        static::$dtableID++;

        if($this->prop('customCols') === true)
        {
            global $app;
            $app->loadLang('datatable');
            $this->setProp('customCols', array('url' => createLink('datatable', 'ajaxcustom', "module=$app->rawModule&method=$app->rawMethod"), 'hint' => $app->lang->datatable->custom));
        }
    }

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
