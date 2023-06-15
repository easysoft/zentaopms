<?php
namespace zin;

class dtable extends wg
{
    static $defineProps = array(
        'className?:string="shadow-sm rounded"', // 表格样式。
        'id?:string',                            // ID。
        'customCols?: bool|array',               // 是否支持自定义列。
        'cols?:array',                           // 表格列配置
        'date?:array',                           // 表格数据源
        'module?:string',                        // 模块信息，主要是获取语言项
    );

    static $dtableID = 0;

    protected function created()
    {
        $this->setDefaultProps(array('id' => static::$dtableID ? ('dtable' . static::$dtableID) : 'dtable'));
        static::$dtableID++;

        global $app;
        if($this->prop('customCols') === true)
        {
            $app->loadLang('datatable');
            $this->setProp('customCols', array('url' => createLink('datatable', 'ajaxcustom', "module=$app->moduleName&method=$app->methodName"), 'hint' => $app->lang->datatable->custom));
        }

        $module = $this->prop('module', $app->rawModule);
        if(!isset($app->lang->$module)) $app->loadLang($module);

        /* Set col default name and title. */
        $colConfigs = $this->prop('cols');
        foreach($colConfigs as $field => &$config)
        {
            if(is_object($config)) $config = (array)$config;

            if(!isset($config['name']))  $config['name'] = $field;
            if(!isset($config['title'])) $config['title'] = zget($app->lang->{$module}, $config['name'], zget($app->lang, $config['name']));
            if(isset($config['link']) && is_array($config['link'])) $config['link'] = $this->getLink($config['link']);
            if(isset($config['assignLink']) && is_array($config['assignLink'])) $config['assignLink'] = $this->getLink($config['assignLink']);

            if(!empty($config['ajaxSubmit']))
            {
                if(empty($config['class']))         $config['class']        = 'ajax-form';
                if(!isset($config['data-confirm'])) $config['data-confirm'] = zget($app->lang->$module, 'confirmDelete');
            }
        }
        $this->setProp('cols', array_values($colConfigs));

        $tableData = $this->prop('data', array());
        $this->setProp('data', array_values($tableData));
    }

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * 获取字段链接。
     * Get link to the field.
     *
     * @param  array        $setting
     * @access protected
     * @return array|string
     */
    protected function getLink(array $setting): array|string
    {
        if(!empty($setting['url']))
        {
            $url = $setting['url'];
            if(!empty($url['module']) && !empty($url['method']))
            {
                $setting['url'] = '';
                if(hasPriv($url['module'], $url['method'])) $setting['url'] = createLink($url['module'], $url['method'], zget($url, 'params', ''), '', !empty($setting['onlybody']));
            }
            return $setting;
        }
        else if(!empty($setting['module']) && !empty($setting['method']))
        {
            if(!hasPriv($setting['module'], $setting['method'])) return '';
            return createLink($setting['module'], $setting['method'], zget($setting, 'params', ''), '', !empty($setting['onlybody']));
        }
        return $setting;
    }

    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
