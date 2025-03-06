<?php
declare(strict_types=1);
namespace zin;

class dtable extends wg
{
    protected static array $defineProps = array(
        'className?:string="shadow ring rounded"', // CSS 类。
        'id?:string',                              // ID。
        'customCols?: bool|array',                 // 是否支持自定义列。
        'cols?:array',                             // 表格列配置。
        'dataModifier?:callable|array',            // 数据处理函数。
        'data?:array',                             // 表格数据源。
        'module?:string',                          // 模块信息，主要是获取语言项。
        'moduleName?:string',                      // 模块名，不传入则使用$app->moduleName。
        'methodName?:string',                      // 方法名，不传入则使用$app->methodName。
        'emptyTip?:string',                        // 表格数据源为空时显示的文本。
        'createTip?:string',                       // 表格数据源为空时的创建文本。
        'createLink?:array|string',                // 表格数据源为空时的创建链接。
        'createAttr?:string',                      // 表格数据源为空时的创建链接属性。
        'sortLink?:array|string',                  // 排序链接。
        'orderBy?:string',                         // 排序字段。
        'loadPartial?: bool',                      // 启用部分加载，不更新浏览器地址栏 URL。
        'loadOptions?: array',                     // 分页和排序加载选项。
        'userMap?: array',                         // 用户账号姓名对应列表
        'unassignedText?: string',                 // 未指派文本
        'extensible?: bool=true'                   // 是否获取工作流扩展字段
    );

    static $dtableID = 0;

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function created()
    {
        global $app;

        if(!$this->hasProp('id'))
        {
            $defaultID = "table-{$app->rawModule}-{$app->rawMethod}";
            $this->setProp('id', static::$dtableID ? ($defaultID . static::$dtableID) : $defaultID);
            static::$dtableID++;
        }

        $module = $this->prop('module', $app->rawModule);
        if(!isset($app->lang->$module)) $app->loadLang($module);

        /* Set col default name and title. */
        $this->initCustomCols();
        $this->initCols($module);
        $this->initSortLink();
        $this->initFooterBar();

        $tableData = $this->prop('data', array());
        $dataModifier = $this->prop('dataModifier');
        if($dataModifier)
        {
            if(is_callable($dataModifier))
            {
                $tableData = array_map($dataModifier, $tableData);
            }
            elseif(is_array($dataModifier))
            {
                foreach($dataModifier as $key => $modifier)
                {
                    foreach($tableData as $index => &$item)
                    {
                        $item[$key] = $modifier($item[$key], $item, $index);
                    }
                }
            }
        }
        $this->setProp('data', array_values($tableData));
    }

    /**
     * 格式化排序链接及默认值。
     * Format sorting links and default values.
     *
     * @access public
     * @return void
     */
    public function initSortLink()
    {
        $orderBy = $this->prop('orderBy', data('orderBy'));
        if(is_string($orderBy))
        {
            list($orderByName, $orderByType) = explode('_', strpos($orderBy, '_') === false ? $orderBy . '_desc' : $orderBy);
            $this->setProp('orderBy', array($orderByName => $orderByType));
        }
    }

    /**
     * 格式化表格自定义列属性
     * Format table custom column properties
     *
     * @access public
     * @return void
     */
    public function initCustomCols()
    {
        global $app;

        $customColsProp = $this->prop('customCols');
        $moduleName     = $this->prop('moduleName') ? $this->prop('moduleName') : $app->moduleName;
        $methodName     = $this->prop('methodName') ? $this->prop('methodName') : $app->methodName;
        if($customColsProp)
        {
            $app->loadLang('datatable');
            $customUrl      = is_bool($customColsProp) || empty($customColsProp['url']) ? null : $customColsProp['url'];
            $customUrl      = $customUrl ? $customUrl : createLink('datatable', 'ajaxcustom', "module=$moduleName&method=$methodName");
            $globalUrl      = is_bool($customColsProp) || empty($customColsProp['globalUrl']) ? null : $customColsProp['globalUrl'];
            $resetUrl       = is_bool($customColsProp) || empty($customColsProp['resetUrl']) ? null : $customColsProp['resetUrl'];
            $resetGlobalUrl = is_bool($customColsProp) || empty($customColsProp['resetGlobalUrl']) ? null : $customColsProp['resetGlobalUrl'];
            $this->setProp('customCols', array(
                'custom' => array(
                    'url' => $customUrl,
                    'text' => $app->lang->datatable->custom
                ),
                'setGlobal' => array(
                    'url' => $globalUrl ? $globalUrl : createLink('datatable', 'ajaxsaveglobal', "module={$moduleName}&method={$methodName}"),
                    'text' => $app->lang->datatable->setGlobal
                ),
                'reset' => array(
                    'url' => $resetUrl ? $resetUrl : createLink('datatable', 'ajaxreset', "module={$moduleName}&method={$methodName}"),
                    'text' => $app->lang->datatable->reset
                ),
                'resetGlobal' => array(
                    'url' => $resetGlobalUrl ? $resetGlobalUrl : createLink('datatable', 'ajaxreset', "module={$moduleName}&method={$methodName}&system=1"),
                    'text' => $app->lang->datatable->resetGlobal
                ),
                'saveFieldsUrl' => str_replace('ajaxcustom', 'ajaxsavefields', $customUrl)
            ));
            $this->setProp('customCol', true);

            $fixedLeftWidth = $this->prop('fixedLeftWidth');
            if($fixedLeftWidth)
            {
                $this->triggerError('Table custom columns feature is enabled, it\'s not recommended to set the fixedLeftWidth property, because it restricts the user from resizing fixed columns, you can remove the setting code: "set::fixedLeftWidth(' . json_encode($fixedLeftWidth) . ')".', E_USER_NOTICE);
            }
        }
    }

    /**
     * 格式化表格列配置。
     * Format table column configuration.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function initCols(string $module)
    {
        global $app, $lang;

        $colConfigs = $this->prop('cols');
        $dataPairs  = $this->prop('userMap', array());
        $moduleName = $this->prop('moduleName', $app->getModuleName());
        $methodName = $this->prop('methodName', $app->getMethodName());

        foreach($colConfigs as $field => &$config)
        {
            if(is_object($config)) $config = (array)$config;

            if(!isset($config['name']))  $config['name']  = $field;
            if(!isset($config['title'])) $config['title'] = zget($app->lang->{$module}, $config['name'], $config['name']);
            if(isset($config['link']) && is_array($config['link'])) $config['link'] = $this->getLink($config['link']);
            if(isset($config['assignLink']) && is_array($config['assignLink'])) $config['assignLink'] = $this->getLink($config['assignLink']);

            if(!empty($config['type']) && $config['type'] == 'control') $config = $this->initFormCol($config);
            if(!empty($config['actionsMap'])) $config['actionsMap'] = $this->initActions($config['actionsMap'], $module);

            if(!empty($config['delimiter']))
            {
                if(!empty($config['map']))     $dataPairs = $config['map'];
                if(!empty($config['userMap'])) $dataPairs = $config['userMap'];

                $config['dataPairs'] = $dataPairs;
                $config['delimiter'] = is_string($config['delimiter']) ? $config['delimiter'] : ',';
                $config['map']       = jsRaw("window.setMultipleCell");
            }

            if(isset($config['type']))
            {
                if($config['type'] === 'pri' && !isset($config['priList']) && !$this->prop('priList'))
                {
                    if(isset($lang->$moduleName->priList))   $this->setProp('priList', $lang->$moduleName->priList);
                    elseif($methodName === 'task') $this->setProp('priList', $lang->task->priList);
                }
                if($config['type'] === 'severity' && !isset($config['severityList']) && !$this->prop('severityList'))
                {
                    if(isset($lang->$moduleName->severityList)) $this->setProp('severityList', $lang->$moduleName->severityList);
                    elseif($methodName === 'bug') $this->setProp('priList', $lang->bug->severityList);
                }
                if($config['type'] === 'assign' && !isset($config['currentUser']) && isset($app->user) && !$this->prop('currentUser'))
                {
                    $this->setProp('currentUser', $app->user->account);
                }
            }

            if(isset($config['modifier']))
            {
                $modifier = $config['modifier'];
                if($modifier)
                {
                    $tableData = $this->prop('data', array());
                    $key       = $config['name'];
                    if(!is_array($modifier)) $modifier = array($modifier);
                    foreach($tableData as &$item)
                    {
                        foreach($modifier as $subModifier)
                        {
                            if(!is_callable($subModifier)) continue;
                            if($subModifier instanceof \Closure) $subModifier = $subModifier->bindTo($item);
                            if(is_object($item)) $item->$key = $subModifier($item->$key);
                            else                 $item[$key] = $subModifier($item[$key]);
                        }
                    }
                    $this->setProp('data', array_values($tableData));
                }
                unset($config['modifier']);
            }
        }

        $this->setProp('cols', array_values($colConfigs));
    }

    /**
     * 格式化表格表单列配置。
     * Format table form column configuration.
     *
     * @param  array  $config
     * @access public
     * @return array
     */
    public function initFormCol(array $config): array
    {
        if(!empty($config['control']) && is_string($config['control'])) $config['control'] = array('type' => $config['control']);

        if(isset($config['controlItems']))
        {
            if(empty($config['control'])) $config['control'] = array('type' => 'picker');

            $items    = $config['controlItems'];
            $newItems = array();
            foreach($items as $key => $value)
            {
                if(is_numeric($key) && is_array($value)) $newItems[] = $value;
                else $newItems[] = array('text' => $value, 'value' => $key);
            }
            $config['control']['props']['items'] = $newItems;
            unset($config['controlItems']);

            if(isset($config['defaultValue'])) $config['control']['props']['defaultValue'] = $config['defaultValue'];
        }

        return $config;
    }

    /**
     * 格式化表格操作列配置。
     * Format table action column configuration.
     *
     * @param  array  $actionsMap
     * @param  string $module
     * @access public
     * @return array
     */
    public function initActions(array $actionsMap,  string $module): array
    {
        if(empty($actionsMap)) return $actionsMap;

        global $app;
        foreach($actionsMap as &$action)
        {
            if(isset($action['data-toggle']) && !isset($action['data-position'])) $action['data-position'] = 'center';

            $className = zget($action, 'className', '');
            if(!empty($action['ajaxSubmit']))
            {
                $className .= ' ajax-submit';
                if(!isset($action['data-confirm'])) $action['data-confirm'] = zget($app->lang->$module, 'confirmDelete');
            }
            $action['className'] = "{$className} text-primary";
        }

        return $actionsMap;
    }

    /**
     * 格式化表格底部工具栏配置。
     * Format table bottom toolbar configuration.
     *
     * @access public
     * @return void
     */
    public function initFooterBar()
    {
        $footToolbar = $this->prop('footToolbar');
        if(!empty($footToolbar))
        {
            if(!is_array($footToolbar))     $footToolbar = array('items' => array($footToolbar));
            if(array_is_list($footToolbar)) $footToolbar = array('items' => $footToolbar);
            $footToolbarItems = array();
            if(isset($footToolbar['items']))
            {
                foreach($footToolbar['items'] as $item)
                {
                    if($item instanceof item) $item = $item->props->toJSON();
                    $footToolbarItems[] = $item;
                }
                $footToolbar['items'] = $footToolbarItems;
            }
            $this->setProp('footToolbar', $footToolbar);
        }
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
                if(!hasPriv($url['module'], $url['method'])) return '';
                $setting['url'] = createLink($url['module'], $url['method'], zget($url, 'params', ''), '', !empty($setting['onlybody']));
            }
            return $setting;
        }
        else if(!empty($setting['module']) && !empty($setting['method']))
        {
            if($setting['module'] != '{module}' && $setting['method'] != '{module}' && !hasPriv($setting['module'], $setting['method'])) return '';
            $url = createLink($setting['module'], $setting['method'], zget($setting, 'params', ''), '', !empty($setting['onlybody']));
            if(empty($setting['target'])) return $url;

            return array('url' => $url, 'target' => $setting['target']);
        }
        return $setting;
    }

    protected function build()
    {
        global $lang, $app;

        if(empty($this->prop('data')))
        {
            $emptyTip   = $this->prop('emptyTip', $lang->noData);
            $createLink = !empty($this->prop('createLink')) ? $this->prop('createLink') : '';
            if(is_string($emptyTip))
            {
                if(!empty($createLink))
                {
                    $createTip  = $this->prop('createTip', $lang->create);
                    $createAttr = $this->prop('createAttr', '');
                    if(strpos($createAttr, 'data-app') === false) $createAttr .= " data-app='{$app->tab}'";
                    $emptyTip   = array('html' => "<div class='text-gray'>$emptyTip</div><a class='btn primary-pale border-primary' href='$createLink' $createAttr><i class='icon icon-plus'></i> $createTip</a>", 'className' => 'row gap-4 items-center');
                }
                else
                {
                    $emptyTip = array('html' => "$emptyTip", 'className' => 'text-gray');
                }
            }
            $this->setProp('emptyTip', $emptyTip);
            $this->setProp('customCols', false);
        }

        if(!$this->prop('checkInfo'))
        {
            $this->setProp('checkInfo', jsRaw(<<<JS
            function(_, layout)
            {
                const checkedCount = this.getChecks().length;
                if(checkedCount) return {html: "{$lang->selectedItems}".replace('{0}', checkedCount)};
                return {html: "{$lang->pager->totalCount}".replace('{recTotal}', this.layout.allRows.length)};
            }
            JS));
        }

        if(!$this->prop('unassignedText'))
        {
            $this->setProp('unassignedText', $lang->noAssigned);
        }

        return zui::dtable
        (
            $this->hasProp('id') ? set::_id($this->prop('id') . '_table') : null,
            inherit($this)
        );
    }
}
