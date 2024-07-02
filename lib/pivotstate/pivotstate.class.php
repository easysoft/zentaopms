<?php
class pivotState
{
    /**
     * Pivot state ID.
     *
     * @var int
     * @access public
     */
    public $id;

    /**
     * Pivot state dimension.
     *
     * @var string
     * @access public
     */
    public $dimension;

    /**
     * Pivot state group.
     *
     * @var string
     * @access public
     */
    public $group;

    /**
     * Pivot state code.
     *
     * @var string
     * @access public
     */
    public $code;

    /**
     * Pivot state driver.
     *
     * @var string
     * @access public
     */
    public $driver;

    /**
     * Pivot state name.
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * Pivot state description.
     *
     * @var string
     * @access public
     */
    public $desc;

    /**
     * Pivot state SQL.
     *
     * @var string
     * @access public
     */
    public $sql;

    /**
     * Pivot state fields.
     *
     * @var array
     * @access public
     */
    public $fields;

    /**
     * Pivot state fieldSettings.
     *
     * @var array
     * @access public
     */
    public $fieldSettings;

    /**
     * Pivot state languages.
     *
     * @var array
     * @access public
     */
    public $langs;

    /**
     * client languages.
     *
     * @var array
     * @access public
     */
    public $clientLang;

    /**
     * Pivot state variables.
     *
     * @var array
     * @access public
     */
    public $vars;

    /**
     * Pivot state objects.
     *
     * @var array
     * @access public
     */
    public $objects;

    /**
     * Pivot state settings.
     *
     * @var array
     * @access public
     */
    public $settings;

    /**
     * Pivot state filters.
     *
     * @var array
     * @access public
     */
    public $filters;

    /**
     * Pivot state drills.
     *
     * @var array
     * @access public
     */
    public $drills;

    /**
     * Pivot state step.
     *
     * @var int
     * @access public
     */
    public $step;

    /**
     * Pivot state stage.
     *
     * @var string
     * @access public
     */
    public $stage;

    /**
     * Pivot stage action.
     *
     * @var string
     * @access public
     */
    public $action = 'design';

    /**
     * error
     *
     * @var bool
     * @access public
     */
    public $error = false;

    /**
     * errorMsg
     *
     * @var string
     * @access public
     */
    public $errorMsg = '';

    /**
     * queryCols
     *
     * @var array
     * @access public
     */
    public $queryCols = array();

    /**
     * queryData
     *
     * @var array
     * @access public
     */
    public $queryData = array();

    /**
     * pivotCols
     *
     * @var array
     * @access public
     */
    public $pivotCols = array();

    /**
     * pivotData
     *
     * @var array
     * @access public
     */
    public $pivotData = array();

    /**
     * pivotCellSpan
     *
     * @var array
     * @access public
     */
    public $pivotCellSpan = array();

    /**
     * pivotFilters
     *
     * @var array
     * @access public
     */
    public $pivotFilters = array();

    /**
     * pager
     *
     * @var int
     * @access public
     */
    public $pager;

    public function __construct($pivot)
    {
        $this->id        = $pivot->id;
        $this->dimension = $pivot->dimension;
        $this->group     = $pivot->group;
        $this->code      = $pivot->code;
        $this->driver    = $pivot->driver;
        $this->name      = $pivot->name;
        $this->desc      = $pivot->desc;
        $this->names     = $pivot->names;
        $this->descs     = $pivot->descs;
        $this->sql       = $pivot->sql;
        $this->step      = 1;
        $this->stage     = $pivot->stage;

        $this->fields    = $this->json2Array($pivot->fieldSettings);
        $this->langs     = $this->json2Array($pivot->langs);
        $this->vars      = $this->json2Array($pivot->vars);
        $this->objects   = $this->json2Array($pivot->objects);
        $this->settings  = $this->json2Array($pivot->settings);
        $this->filters   = $this->json2Array($pivot->filters);

        $this->fieldSettings = array_merge_recursive($this->fields, $this->langs);
        $this->setPager();
        $this->formatSettingColumns();
    }

    /**
     * Get filters.
     *
     * @access public
     * @return array
     */
    public function getFilters()
    {
        $filters = array();
        $filterValues = array();

        if(!empty($this->pivotFilters))
        {
            $pivotFilters = array();
            foreach($this->pivotFilters as $pivotFilter) $pivotFilters = array_merge($pivotFilters, $pivotFilter);
            foreach($pivotFilters as $pivotFilter) $filterValues[$pivotFilter['name']] = $pivotFilter['value'];
        }

        foreach($this->filters as $filter)
        {
            $value = $filterValues[$filter['field']];
            if(is_array($value)) $value = array_filter($value);
            if(isset($filterValues[$filter['field']])) $filter['default'] = $value;
            $filters[$filter['field']] = $filter;
        }

        return $filters;
    }

    /**
     * Add filter.
     *
     * @access public
     * @return void
     */
    public function addFilter()
    {
        $firstField = current($this->fieldSettings);
        $lang       = $this->clientLang;

        $this->filters[] = array('field' => $firstField['name'], 'saveAs' => '', 'type' => 'input', 'name' => $firstField[$lang], 'defualt' => '');
    }

    /**
     * Judge is query filter or not.
     *
     * @access public
     * @return bool
     */
    public function isQueryFilter()
    {
        if(empty($this->filters)) return false;
        $filter = current($this->filters);

        return isset($filter['from']) && $filter['from'] == 'query';
    }

    /**
     * Complete filters default.
     *
     * @access public
     * @return void
     */
    public function completeFiltersDefault()
    {
        $queryDefaults  = array('select' => '', 'input' => '', 'date' => '', 'datetime' => '');
        $resultDefaults = array('select' => array(), 'input' => '', 'date' => array('begin' => '', 'end' => ''), 'datetime' => array('begin' => '', 'end' => ''));
        foreach($this->filters as $index => $filter)
        {
            if(isset($filter['default'])) continue;

            $from     = zget($filter, 'from', 'result');
            $type     = $filter['type'];
            $defaults = $from == 'query' ? $queryDefaults : $resultDefaults;

            $filter['default'] = $defaults[$type];
            $this->filters[$index] = $filter;
        }
    }

    /**
     * Convert filters to where conditions.
     *
     * @param  array    $filters
     * @access public
     * @return array
     */
    public function convertFiltersToWhere($filters)
    {
        $filterWheres = array();
        foreach($filters as $filter)
        {
            $field   = $filter['field'];
            $default = zget($filter, 'default', '');
            $from    = zget($filter, 'from', 'result');
            $type    = $filter['type'];

            if($from == 'query' || empty($default)) continue;

            switch($type)
            {
                case 'select':
                    if(is_array($default)) $default = implode("', '", array_filter($default, function($val){return trim($val) != '';}));
                    $value = "('" . $default . "')";
                    $filterWheres[$field] = array('operator' => 'IN', 'value' => $value);
                    break;
                case 'input':
                    $filterWheres[$field] = array('operator' => 'LIKE', 'value' => "'%$default%'");
                    break;
                case 'date':
                case 'datetime':
                    $begin = $default['begin'];
                    $end   = $default['end'];

                    if(!empty($begin)) $begin = date('Y-m-d 00:00:00', strtotime($begin));
                    if(!empty($end))   $end   = date('Y-m-d 23:59:59', strtotime($end));

                    if(!empty($begin) &&  empty($end)) $filterWheres[$field] = array('operator' => '>',       'value' => "'{$begin}'");
                    if( empty($begin) && !empty($end)) $filterWheres[$field] = array('operator' => '<',       'value' => "'{$end}'");
                    if(!empty($begin) && !empty($end)) $filterWheres[$field] = array('operator' => 'BETWEEN', 'value' => "'{$begin}' AND '{$end}'");
                    break;
                default:
                    break;
            }
        }

        return $filterWheres;
    }

    /**
     * Complete settings.
     *
     * @access public
     * @return void
     */
    public function completeSettings()
    {
        $settings = $this->settings;
        if(!isset($settings['summary']) || $settings['summary'] !== 'notuse') $this->settings['summary'] = 'use';
        if(!isset($settings['group1']))  $this->settings['group1'] = '';
        if(!isset($settings['columns'])) $this->addColumn();
    }

    /**
     * Process column show origin.
     *
     * @access public
     * @return void
     */
    public function processColumnShowOrigin()
    {
        if(empty($this->settings['columns'])) return;

        $columns = $this->settings['columns'];
        foreach($columns as $index => $column)
        {
            $showOrigin      = $column['showOrigin'];
            $defaultColumn   = $this->getDefaultColumn();
            $columns[$index] = $showOrigin ? array('field' => $column['field'], 'showOrigin' => 1) : array_merge($defaultColumn, $column);
        }

        $this->settings['columns'] = $columns;
    }

    /**
     * Get field options.
     *
     * @access public
     * @return array
     */
    public function getFieldOptions()
    {
        $options = array();
        $lang    = $this->clientLang;
        foreach($this->fieldSettings as $settings)
        {
            $options[] = array('text' => isset($settings[$lang]) ? $settings[$lang] : $settings['name'], 'value' => $settings['name'], 'key' => $settings['name']);
        }

        return $options;
    }

    /**
     * Get setting groups.
     *
     * @access public
     * @return array
     */
    public function getSettingGroups()
    {
        $groups   = array();
        $settings = $this->settings;
        $keys     = array_keys($settings);
        foreach($keys as $key) if(strpos($key, 'group') === 0) $groups[$key] = $settings[$key];

        return $groups;
    }

    /**
     * Get setting columns.
     *
     * @access public
     * @return array
     */
    public function getSettingColumns()
    {
        return $this->settings['columns'];
    }

    /**
     * Add column.
     *
     * @access public
     * @return void
     */
    public function addColumn()
    {
        $this->settings['columns'][] = $this->getDefaultColumn();
    }

    /**
     * Add drill.
     *
     * @access public
     * @return void
     */
    public function addDrill($type = 'auto')
    {
        $this->drills[] = array('type' => $type);
    }

    /**
     * Get defualt column.
     *
     * @param  string|null $field
     * @access public
     * @return void
     */
    public function getDefaultColumn($field = null)
    {
        if(empty($field))
        {
            $firstField = current($this->fieldSettings);
            $field = $firstField['name'];
        }

        return array('field' => $field, 'slice' => 'noSlice', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0);
    }

    /**
     * Update from $_POST.
     *
     * @param  array    $post
     * @access public
     * @return void
     */
    public function updateFromPost($post)
    {
        if(!isset($post['pivotState'])) return;
        $json = $post['pivotState'];
        $array = json_decode($json, true);

        extract($array);

        $this->id        = $id;
        $this->dimension = $dimension;
        $this->group     = $group;
        $this->code      = $code;
        $this->driver    = $driver;
        $this->name      = $name;
        $this->desc      = $desc;
        $this->sql       = $sql;
        $this->step      = $step;
        $this->stage     = $stage;

        $this->fields    = $fields;
        $this->langs     = $langs;
        $this->vars      = $vars;
        $this->objects   = $objects;
        $this->settings  = $settings;
        $this->filters   = $filters;

        $this->action        = $action;
        $this->queryCols     = $queryCols;
        $this->queryData     = $queryData;
        $this->pivotCols     = $pivotCols;
        $this->pivotData     = $pivotData;
        $this->pivotCellSpan = $pivotCellSpan;
        $this->pivotFilters  = $pivotFilters;

        $this->fieldSettings = $fieldSettings;
        $this->setPager($pager['total'], $pager['recPerPage'], $pager['pageID']);
        $this->formatSettingColumns();
        $this->processFieldSettingsLang();
        $this->completeFiltersDefault();
    }

    /**
     * Format setting columns.
     *
     * @access public
     * @return void
     */
    public function formatSettingColumns()
    {
        if(empty($this->settings->columns)) return;

        foreach($this->settings->columns as $id => $column)
        {
            $column['monopolize'] = (int)$column['monopolize'];
            $column['showOrigin'] = (int)$column['showOrigin'];

            $this->settings->columns[$id] = $column;
        }
    }

    /**
     * Clear properies before query sql.
     *
     * @access public
     * @return void
     */
    public function beforeQuerySql()
    {
        $this->error         = false;
        $this->errorMsg      = '';
        $this->queryCols     = array();
        $this->queryData     = array();
        $this->pivotCols     = array();
        $this->pivotData     = array();
        $this->pivotCellSpan = array();
        $this->setPager();
    }

    /**
     * Set fieldSettings with merge.
     *
     * @param  array    $settings
     * @access public
     * @return void
     */
    public function setFieldSettings($settings)
    {
        $settings         = (array)$settings;
        $oldFieldSettings = empty($this->fieldSettings) ? $this->fieldSettings : array();
        $newFieldSettings = array();

        foreach($settings as $field => $setting)
        {
            $oldSetting = isset($oldFieldSettings[$field]) ? $oldFieldSettings[$field] : array();
            if(!empty($oldSetting) && $oldSetting['object'] == $setting['object'] && $oldSetting['field'] == $setting['field'])
            {
                $newFieldSettings[$field] = $this->processFieldSettingLang($field, $oldSetting);
            }
            else
            {
                $newFieldSettings[$field] = $setting;
            }
        }

        $this->fieldSettings = $newFieldSettings;
    }

    /**
     * Process fieldSettings lang.
     *
     * @access public
     * @return void
     */
    public function processFieldSettingsLang()
    {
        if(empty($this->fieldSettings)) return;
        foreach($this->fieldSettings as $field => $fieldSetting)
        {
            $this->fieldSettings[$field] = $this->processFieldSettingLang($field, $fieldSetting);
        }
    }

    /**
     * Process fieldSetting lang.
     *
     * @param  string  $field
     * @param  array   $fieldSetting
     * @access public
     * @return array
     */
    public function processFieldSettingLang($field, $fieldSetting)
    {
        $lang = $this->clientLang;
        if(isset($fieldSetting[$lang])) return $fieldSetting;

        $fieldSetting[$lang]  = $fieldSetting['name'];
        $fieldSetting['name'] = $field;

        return $fieldSetting;
    }

    /**
     * Build cols for query sql with lang.
     *
     * @access public
     * @return object
     */
    public function buildQuerySqlCols()
    {
        $cols = array();
        $lang = $this->clientLang;
        foreach($this->fieldSettings as $field => $settings)
        {
            $settings = (array)$settings;
            $title    = isset($settings[$lang]) ? $settings[$lang] : $field;

            $cols[] = array('name' => $field, 'title' => $title, 'sortType' => false);
        }

        $this->queryCols = $cols;
        return $this;
    }

    public function getFields($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        $fields = array();
        $keys   = array('name', 'object', 'field', 'type');
        foreach($fieldSettings as $fieldSetting)
        {
            $field = array();
            foreach($keys as $key) if(isset($fieldSetting[$key])) $field[$key] = $fieldSetting[$key];
            $fields[$fieldSetting['name']] = $field;
        }

        return $type == 'object' ? $fields : json_encode($fields);
    }

    public function getLangs($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        $langs = array();
        $keys  = array('name', 'object', 'field', 'type');
        foreach($fieldSettings as $fieldSetting)
        {
            $lang = array();
            foreach($fieldSetting as $key => $value) if(!in_array($key, $keys)) $lang[$key] = $value;

            $langs[$fieldSetting['name']] = $lang;
        }

        return $type == 'object' ? $langs : json_encode($langs);
    }

    /**
     * Set clientLang.
     *
     * @param  string    $clientLang
     * @access public
     * @return void
     */
    public function setClientLang($clientLang)
    {
        $this->clientLang = $clientLang;
    }

    /**
     * Set action.
     *
     * @param  string    $action
     * @access public
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Set step.
     *
     * @param  int    $step
     * @access public
     * @return void
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * Judge is publish action.
     *
     * @access public
     * @return bool
     */
    public function isPublish()
    {
        return $this->action == 'publish';
    }

    /**
     * Judge is design action.
     *
     * @access public
     * @return bool
     */
    public function isDesign()
    {
        return $this->action == 'design';
    }

    /**
     * Judge is first design action.
     *
     * @access public
     * @return bool
     */
    public function isFirstDesign()
    {
        return $this->isDesign() && empty($this->sql);
    }

    /**
     * Judge is error.
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * Get error message.
     *
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->errorMsg;
    }

    /**
     * Set error.
     *
     * @param  string    $msg
     * @access public
     * @return object
     */
    public function setError($msg)
    {
        $this->error    = true;
        $this->errorMsg = $msg;

        return $this;
    }

    /**
     * Set pager
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function setPager($total = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->pager = new stdclass();
        $this->pager->total      = $total;
        $this->pager->recPerPage = $recPerPage;
        $this->pager->pageID     = $pageID;
    }

    /**
     * Convert json string to array.
     *
     * @param  string|object|array|null    $json
     * @access public
     * @return array
     */
    private function json2Array(string|object|array|null $json): array
    {
        if(empty($json)) return array();
        if(is_string($json)) return json_decode($json, true);
        if(is_object($json)) return json_decode(json_encode($json), true);

        return $json;
    }
}
