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
     * Pivot state mode.
     *
     * @var string
     * @access public
     */
    public $mode;

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
     * Pivot state relatedObject.
     * e.g array('id' => 'action')
     *
     * @var array
     * @access public
     */
    public $relatedObject;

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
     * Pivot state version.
     *
     * @var string
     * @access public
     */
    public $version;

    /**
     * Pivot state acl.
     *
     * @var string
     * @access public
     */
    public $acl;

    /**
     * Pivot state used.
     *
     * @var string
     * @access public
     */
    public $used;

    /**
     * Pivot state whitelist.
     *
     * @var string
     * @access public
     */
    public $whitelist;

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

    /**
     * sqlChanged
     *
     * @var bool
     * @access public
     */
    public $sqlChanged = false;

    /**
     * filterChanged.
     *
     * @var bool
     * @access public
     */
    public $filterChanged = false;

    /**
     * addQueryFilter
     *
     * @var array
     * @access public
     */
    public $addQueryFilter = array();

    /**
     * step2 finish with Sql, used to judge if step1 sql changed
     *
     * @var string
     * @access public
     */
    public $step2FinishSql = '';

    /**
     * First enter design.
     *
     * @var bool
     * @access public
     */
    public $firstEnterDesign = false;

    /**
     * Auto gen drills.
     *
     * @var bool
     * @access public
     */
    public $autoGenDrills = false;

    /**
     * Check step design.
     *
     * @var bool
     * @access public
     */
    public $checkStepDesign = false;

    public $triggerQuery = false;

    public $canChangeMode = true;

    public $sqlbuilder = array();

    /**
     * __construct method.
     *
     * @param  pivot      object
     * @param  drills     array
     * @param  clientLang string
     * @access public
     * @return void
     */
    public function __construct($pivot, $drills = array(), $clientLang = 'zh-cn', $sqlbuilder = array())
    {
        $this->id        = $pivot->id;
        $this->dimension = $pivot->dimension;
        $this->group     = $pivot->group;
        $this->code      = $pivot->code;
        $this->driver    = $pivot->driver;
        $this->mode      = $pivot->mode;
        $this->name      = $pivot->name;
        $this->desc      = $pivot->desc;
        $this->names     = $pivot->names;
        $this->descs     = $pivot->descs;
        $this->sql       = $pivot->sql;
        $this->step      = 'query';
        $this->stage     = $pivot->stage;
        $this->version   = $pivot->version;
        $this->acl       = $pivot->acl;
        $this->whitelist = $pivot->whitelist;

        $this->drills       = $drills;
        $this->defaultDrill = $this->initDrill();

        $this->sqlbuilder = $sqlbuilder;

        $this->fields    = $this->json2Array($pivot->fieldSettings);
        $this->langs     = $this->json2Array($pivot->langs);
        $this->vars      = $this->json2Array($pivot->vars);
        $this->objects   = $this->json2Array($pivot->objects);
        $this->settings  = $this->json2Array($pivot->settings);
        $this->filters   = $this->json2Array($pivot->filters);

        $this->clientLang    = $clientLang;
        $this->fieldSettings = array_merge_recursive($this->fields, $this->langs);
        $this->setPager();
        $this->formatSettingColumns();
        $this->setStep2FinishSql();
    }

    /**
     * Process query filters.
     *
     * @access public
     * @return void
     */
    public function processQueryFilters()
    {
        if($this->mode == 'text') return;

        $querys = $this->sqlbuilder->querys;
        $this->clearFilters();
        if(empty($querys)) return;

        if($this->sqlbuilder->checkQuerys() !== true) return;

        foreach($querys as $index => $query)
        {
            $filter = $this->getDefaultQueryFilter();
            $field  = "var$index";
            foreach(array_keys($filter) as $key)
            {
                if($key == 'field') $filter[$key] = $field;
                elseif(isset($query[$key])) $filter[$key] = $query[$key];
            }
            $filter['from'] = 'query';
            $this->filters[] = $filter;
        }
    }

    /**
     * Match field setting from builder.
     *
     * @param  string $key
     * @param  array  $setting
     * @access public
     * @return array
     */
    public function matchFieldSettingFromBuilder($key, $setting)
    {
        if($this->mode == 'text') return $setting;
        $selects = array_merge($this->sqlbuilder->getSelects(), $this->sqlbuilder->getFuncSelects());
        foreach($selects as $select)
        {
            list($table, $field, $alias) = $select;
            if($key != $alias) continue;

            $fieldList = $this->sqlbuilder->getTableDescList($table);
            $name = zget($fieldList, $field, $field);
            $setting[$this->clientLang] = $name;
            $setting['field']           = $field;
        }
        return $setting;
    }

    /**
     * Clear fieldSettings.
     *
     * @access public
     * @return void
     */
    public function clearFieldSettings()
    {
        $this->fields        = array();
        $this->langs         = array();
        $this->fieldSettings = array();
        $this->relatedObject = array();
    }

    /**
     * Clear settings.
     *
     * @param  int    $init
     * @access public
     * @return void
     */
    public function clearSettings($init = false)
    {
        $this->settings = array();
        if($init) $this->completeSettings();
    }

    /**
     * Clear column drill.
     *
     * @access public
     * @return void
     */
    public function clearColumnDrill()
    {
        if(empty($this->settings)) return;
        if(!isset($this->settings['columns']) || empty($this->settings['columns'])) return;

        $columns = $this->settings['columns'];
        foreach($columns as $index => $column)
        {
            if(isset($column['drill'])) unset($this->settings['columns'][$index]['drill']);
        }
    }

    /**
     * Clear filters.
     *
     * @access public
     * @return void
     */
    public function clearFilters()
    {
        $this->filters      = array();
        $this->pivotFilters = array();
    }

    /**
     * Clear drills.
     *
     * @access public
     * @return void
     */
    public function clearDrills()
    {
        $this->drills = array();
    }

    /**
     * Sql changed.
     *
     * @access public
     * @return void
     */
    public function sqlChanged()
    {
        $this->queryCols = array();
        $this->queryData = array();
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
            $value = zget($filterValues, $filter['field'], $filter['default']);
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
        $firstKey   = key($this->fieldSettings);
        $firstField = current($this->fieldSettings);
        $lang       = $this->clientLang;

        $this->filters[] = array('field' => $firstKey, 'saveAs' => '', 'type' => 'input', 'name' => $firstField[$lang], 'default' => '');
    }

    /**
     * Judge is query filter or not.
     *
     * @param array  $filters
     * @access public
     * @return bool
     */
    public function isQueryFilter($filters = array())
    {
        $filters = empty($filters) ? $this->filters : $filters;
        if(empty($filters)) return false;
        $filter = current($filters);

        return isset($filter['from']) && $filter['from'] == 'query';
    }

    /**
     * Save query filter.
     *
     * @access public
     * @return void
     */
    public function saveQueryFilter()
    {
        $filter = $this->addQueryFilter;
        $filter['from'] = 'query';
        $this->filters[] = $filter;
        $this->addQueryFilter = array();
    }

    /**
     * Add variable to sql.
     *
     * @access public
     * @return void
     */
    public function addVariableToSql()
    {
        $variable = $this->addQueryFilter['field'];

        $this->sql .= "\$$variable";
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
        if($this->isQueryFilter($filters)) return array_values($filters);

        $filterWheres = array();
        foreach($filters as $filter)
        {
            $field   = $filter['field'];
            $default = zget($filter, 'default', '');
            $type    = $filter['type'];

            if(empty($default)) continue;

            switch($type)
            {
                case 'select':
                    if(is_array($default)) $default = implode("', '", array_filter($default, function($val){return trim($val) != '';}));
                    if(empty($default)) break;
                    $value = "('" . $default . "')";
                    $filterWheres[$field] = array('operator' => 'IN', 'type' => $type, 'value' => $value);
                    break;
                case 'input':
                    $filterWheres[$field] = array('operator' => 'LIKE', 'type' => $type, 'value' => "'%$default%'");
                    break;
                case 'date':
                case 'datetime':
                    $begin = $default['begin'];
                    $end   = $default['end'];

                    if(!empty($begin)) $begin = date('Y-m-d 00:00:00', strtotime($begin));
                    if(!empty($end))   $end   = date('Y-m-d 23:59:59', strtotime($end));

                    if(!empty($begin) &&  empty($end)) $filterWheres[$field] = array('operator' => '>=',      'type' => $type, 'value' => "'{$begin}'");
                    if( empty($begin) && !empty($end)) $filterWheres[$field] = array('operator' => '<=',      'type' => $type, 'value' => "'{$end}'");
                    if(!empty($begin) && !empty($end)) $filterWheres[$field] = array('operator' => 'BETWEEN', 'type' => $type, 'value' => "'{$begin}' AND '{$end}'");
                    break;
                default:
                    break;
            }
        }

        return $filterWheres;
    }

    /**
     * Set filters default value.
     *
     * @param  array    $filterValues
     * @access public
     * @return void
     */
    public function setFiltersDefaultValue($filterValues)
    {
        $filters = array();
        foreach($this->filters as $index => $filter)
        {
            if(!isset($filterValues[$index])) continue;

            $default = $filterValues[$index];
            $type    = $filter['type'];
            $from    = zget($filter, 'from', 'result');
            if($type == 'select' && is_array($default)) $default = array_filter($default);
            if($type == 'date' || $type == 'datetime')
            {
                if($from == 'query')
                {
                    $format = $type == 'datetime' ? 'Y-m-d H:i:s' : 'Y-m-d';
                    switch($default)
                    {
                        case '$MONDAY':     $default = date($format, time() - (date('N') - 1) * 24 * 3600); break;
                        case '$SUNDAY':     $default = date($format, time() + (7 - date('N')) * 24 * 3600); break;
                        case '$MONTHBEGIN': $default = date($format, time() - (date('j') - 1) * 24 * 3600); break;
                        case '$MONTHEND':   $default = date($format, time() + (date('t') - date('j')) * 24 * 3600); break;
                        default:
                        break;
                    }
                }
                else
                {
                    if(is_array($default))
                    {
                        $begin = $default['begin'];
                        $end   = $default['end'];

                        if(is_numeric($begin)) $begin = date('Y-m-d H:i:s', $begin / 1000);
                        if(is_numeric($end))   $end   = date('Y-m-d H:i:s', $end / 1000);

                        $default = array('begin' => $begin, 'end' => $end);
                    }
                    else
                    {
                        $default = array('begin' => '', 'end' => '');
                    }
                }
            }
            $filter['default'] = $default;
            $filters[] = $filter;
        }

        return $filters;
    }

    /**
     * Complete settings.
     *
     * @access public
     * @return void
     */
    public function isSummaryNotUse()
    {
        return (isset($this->settings['summary']) && $this->settings['summary'] === 'notuse');
    }

    /**
     * Complete settings.
     *
     * @access public
     * @return void
     */
    public function completeSettings()
    {
        if($this->isSummaryNotUse())
        {
            $this->settings['drills'] = array_column($this->drills, 'condition', 'field');
            return;
        }

        $settings = $this->settings;
        if(!isset($settings['summary']) || $settings['summary'] !== 'notuse') $this->settings['summary'] = 'use';
        if(!isset($settings['group1']))  $this->settings['group1'] = '';
        if(!isset($settings['columns']) || empty($settings['columns'])) $this->addColumn();
        if(!isset($settings['columnTotal'])) $this->settings['columnTotal'] = 'noShow';
        if(!isset($settings['columnPosition'])) $this->settings['columnPosition'] = 'bottom';

        foreach($this->settings['columns'] as $index => $column)
        {
            $this->settings['columns'][$index] = array_merge($this->getDefaultColumn(), $column);

            unset($this->settings['columns'][$index]['drill']);
            foreach($this->drills as $drill)
            {
                if(empty($drill)) continue;
                if($drill['field'] == $column['field']) $this->settings['columns'][$index]['drill'] = $drill;
            }
        }
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
        foreach($this->fieldSettings as $field => $settings)
        {
            $name      = zget($settings, 'name', $field);
            $text      = zget($settings, $lang, $name);
            $options[] = array('text' => $text, 'value' => $field, 'key' => $field);
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
     * Init drill.
     *
     * @access public
     * @return void
     */
    public function initDrill()
    {
        return array('field' => '', 'object' => '', 'referSQL' => '', 'whereSQL' => '', 'type' => 'manual', 'condition' => array($this->addCondition()));
    }

    /**
     * Add drill.
     *
     * @access public
     * @return void
     */
    public function addDrill($drill)
    {
        $this->drills[] = $drill;
    }

    /**
     * Add condition.
     *
     * @access public
     * @return void
     */
    public function addCondition()
    {
        return array('drillObject' => '', 'drillAlias' => '', 'drillField' => '', 'queryField' => '');
    }

    /**
     * Get default column.
     *
     * @param  string|null $field
     * @access public
     * @return void
     */
    public function getDefaultColumn($field = null)
    {
        if(empty($field))
        {
            $field = key($this->fieldSettings);
        }

        return array('field' => $field, 'slice' => 'noSlice', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0);
    }

    /**
     * Get default query filter.
     *
     * @access public
     * @return array
     */
    public function getDefaultQueryFilter()
    {
        return array('field' => '', 'name' => '', 'type' => 'input', 'typeOption' => '', 'default' => '', 'items' => array());
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
        if(!isset($post['data'])) return;

        $data = json_decode($post['data'], true);
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }

        $this->formatSettingColumns();
        $this->processFieldSettingsLang();
        $this->completeFiltersDefault();
        $this->canChangeMode = $this->setCanChangeMode();
    }

    /**
     * Set can change mode.
     *
     * @access public
     * @return bool
     */
    public function setCanChangeMode()
    {
        if($this->mode == 'builder') return true;
        if($this->mode == 'text' && empty($this->sql)) return true;

        return false;
    }

    /**
     * Update from cache.
     *
     * @param  object  $cache
     * @access public
     * @return void
     */
    public function updateFromCache($cache)
    {
        if($cache === false) return;
        foreach($cache as $key => $value)
        {
            if(is_array($value) || is_object($value)) $value = json_decode(json_encode($value), true);
            $this->$key = $value;
        }

        $this->formatSettingColumns();
        $this->processFieldSettingsLang();
        $this->completeFiltersDefault();
        $this->canChangeMode = $this->setCanChangeMode();
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
        $oldFieldSettings = !empty($this->fieldSettings) ? $this->fieldSettings : array();
        $newFieldSettings = array();

        foreach($settings as $field => $setting)
        {
            $oldSetting = isset($oldFieldSettings[$field]) ? $oldFieldSettings[$field] : array();
            if(!empty($oldSetting))
            {
                $newFieldSettings[$field] = $this->processFieldSettingLang($field, $oldSetting, $setting);

                if($setting['type'] != $oldSetting['type'] && in_array($setting['type'], array('user', 'type'))) $newFieldSettings[$field]['type'] = $setting['type'];
            }
            else
            {
                // $newFieldSettings[$field] = $setting;
                $newFieldSettings[$field] = $this->matchFieldSettingFromBuilder($field, $setting);
            }
        }

        $this->fields        = $this->json2Array($newFieldSettings);
        $this->fieldSettings = $newFieldSettings;
    }

    /**
     * Set drills.
     *
     * @param  array    $drills
     * @access public
     * @return void
     */
    public function setDrills($drills)
    {
        $this->drills = json_decode(json_encode($drills), true);
    }

    /**
     * Set filters.
     *
     * @param  array    $drills
     * @access public
     * @return void
     */
    public function setFilters($filters)
    {
        $this->filters = json_decode(json_encode($filters), true);
    }

    /**
     * Set field related object.
     *
     * @param  array  $relatedObject
     * @access public
     * @return void
     */
    public function setFieldRelatedObject($relatedObject)
    {
        $this->relatedObject = $relatedObject;
    }

    /**
     * Set step2 finishsql.
     *
     * @access public
     * @return void
     */
    public function setStep2FinishSql()
    {
        if($this->issetSettings()) $this->step2FinishSql = $this->sql;
    }

    /**
     * isset pivot setting.
     *
     * @access public
     * @return void
     */
    public function issetSettings()
    {
        if($this->isSummaryNotUse()) return true;

        if(empty($this->getSettingGroups())) return false;
        $columns = $this->getSettingColumns();
        if(count($columns) == 1 && empty($columns[0]['field'])) return false;

        return true;
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
     * @param  array   $newSetting
     * @access public
     * @return array
     */
    public function processFieldSettingLang($field, $oldSetting, $newSetting = array())
    {
        $lang = $this->clientLang;
        if(!empty($oldSetting[$lang])) return $oldSetting;

        if(!empty($newSetting[$lang]))
        {
            $oldSetting[$lang] = $newSetting[$lang];
            return $oldSetting;
        }

        $oldSetting[$lang] = isset($oldSetting['name']) ? $oldSetting['name'] : $field;
        return $oldSetting;
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

    /**
     * Get langs.
     *
     * @param  string $type
     * @access public
     * @return object|string
     */
    public function getFields($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        if(empty($fieldSettings)) return null;

        $fields = array();
        $keys   = array('object', 'field', 'type');
        foreach($fieldSettings as $fieldKey => $fieldSetting)
        {
            $field = array();
            foreach($keys as $key)
            {
                if(isset($fieldSetting[$key])) $field[$key] = $fieldSetting[$key];
            }

            $fields[$fieldKey] = $field;
        }
        return $type == 'object' ? $fields : json_encode($fields);
    }

    /**
     * Get langs.
     *
     * @param  string $type
     * @access public
     * @return object|string
     */
    public function getLangs($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        if(empty($fieldSettings)) return null;

        $langs = array();
        $keys  = array('object', 'field', 'type');
        foreach($fieldSettings as $fieldKey => $fieldSetting)
        {
            $lang = array();
            foreach($fieldSetting as $key => $value)
            {
                if(!in_array($key, $keys)) $lang[$key] = $value;
            }

            $langs[$fieldKey] = $lang;
        }

        return $type == 'object' ? $langs : json_encode($langs);
    }

    /**
     * Get filters.
     *
     * @param  string $type
     * @access public
     * @return object|string
     */
    public function getFiltersForSave($type = 'object')
    {
        $filters = $this->filters;
        foreach($filters as $index => $filter)
        {
            if(isset($filter['items'])) unset($filters[$index]['items']);
        }
        if($type == 'object') return $filters;
        return json_encode($filters);
    }

    /**
     * Judge is queried.
     *
     * @access public
     * @return bool
     */
    public function isQueried()
    {
        return !empty($this->sql) && !$this->isError() && !empty($this->queryCols);
    }

    /**
     * Check fields with setting.
     *
     * @param  bool    $removeUnused
     * @access public
     * @return bool
     */
    public function checkFieldsWithSetting($removeUnused = false)
    {
        $settings = $this->settings;
        $fields   = $this->fieldSettings;

        $isChanged = false;
        foreach($settings as $key => $value)
        {
            if(strpos($key, 'group') === 0 && !isset($fields[$value]))
            {
                $isChanged = true;
                if($removeUnused) unset($this->settings[$key]);
            }
            if($key === 'columns')
            {
                foreach($value as $index => $column)
                {
                    if(!isset($fields[$column['field']]) || !isset($fields[$column['slice']]))
                    {
                        $isChanged = true;
                        if($removeUnused) unset($this->settings['columns'][$index]);
                    }
                }
            }
        }

        $filters = $this->filters;
        foreach($filters as $index => $filter)
        {
            $from = zget($filter, 'from', 'result');
            if($from == 'query') continue;
            if(!isset($fields[$filter['field']]))
            {
                $isChanged = true;
                if($removeUnused) unset($this->filters[$index]);
            }
        }

        $drills = $this->drills;
        foreach($drills as $index => $drill)
        {
            if(!isset($fields[$drill['field']]))
            {
                $isChanged = true;
                if($removeUnused) unset($this->drills[$index]);
            }
            foreach($drill['condition'] as $conditionIndex => $condition)
            {
                if(!isset($fields[$condition['queryField']]))
                {
                    $isChanged = true;
                    if($removeUnused) unset($this->drills[$index]['condition'][$conditionIndex]);
                }
            }
        }

        if($removeUnused) $this->completeSettings();

        return $isChanged;
    }

    /**
     * Check settings.
     *
     * @access public
     * @return array
     */
    public function checkSettings()
    {
        if($this->isSummaryNotUse()) return array();

        $errors = array();
        foreach($this->settings as $key => $value)
        {
            if(strpos($key, 'group') === 0 && empty($value))
            {
                $errors[$key] = true;
                continue;
            }

            if($key === 'columns')
            {
                foreach($value as $index => $column)
                {
                    if(empty($column['field']))                                $errors[$key][$index]['field'] = true;
                    if($column['showOrigin'] === 0 && empty($column['stat']))  $errors[$key][$index]['stat']  = true;
                }
            }
        }

        return $errors;
    }

    /**
     * Check query filter.
     *
     * @access public
     * @return array
     */
    public function checkQueryFilter()
    {
        $errors = array();
        foreach($this->filters as $index => $filter)
        {
            if(empty($filter['field'])) $errors[$index]['field'] = true;
            if(empty($filter['name']))  $errors[$index]['name'] = true;
        }

        $addFilter = $this->addQueryFilter;
        if(empty($addFilter['field'])) $errors[$index]['field'] = true;
        if(empty($addFilter['name'])) $errors[$index]['name'] = true;

        return $errors;
    }

    /**
     * Match query filte from sql.
     *
     * @access public
     * @return void
     */
    public function matchQueryFilterFromSql()
    {
        if(!$this->isQueryFilter()) return;

        $keepFilters      = array();
        $keepPivotFilters = array();
        foreach($this->filters as $index => $filter)
        {
            $field = $filter['field'];
            if(strpos($this->sql, "\$$field") !== false)
            {
                $keepFilters[]      = $filter;
                $keepPivotFilters[] = $this->pivotFilters[0][$index];
            }
        }

        $this->filters      = $keepFilters;
        $this->pivotFilters[0] = $keepPivotFilters;
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
        if($action == 'enterDesign') $action = 'design';
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
    public function setPager($total = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->pager = array();
        $this->pager['total']      = $total;
        $this->pager['recPerPage'] = $recPerPage;
        $this->pager['pageID']     = $pageID;
        $this->pager['pageTotal']  = $total % $recPerPage == 0 ? (int)($total / $recPerPage) : (int)($total / $recPerPage) + 1;
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
        if(is_object($json) || is_array($json)) return json_decode(json_encode($json), true);

        return $json;
    }
}
