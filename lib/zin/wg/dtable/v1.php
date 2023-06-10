<?php
namespace zin;

class dtable extends wg
{
    static $defineProps = array(
        'className?:string="shadow-sm rounded"', // 表格样式。
        'id?:string',                            // ID。
        'customCols?: bool|array',               // 是否支持自定义列。
        'cols?:array',                           // 表格列配置
        'module?:string',                        // 模块信息，主要是获取语言项
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

        $module = $this->prop('module', $app->rawModule);
        if(!isset($app->lang->$module)) $app->loadLang($module);

        $colConfigs = $this->prop('cols');
        foreach($colConfigs as $field => &$config)
        {
            if(!isset($config['name']))  $config['name'] = $field;

            if(!isset($config['title'])) $config['title'] = zget($app->lang->{$module}, $config['name'], zget($app->lang, $config['name']));
        }
        $this->setProp('cols', array_values($colConfigs));
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
