<?php
class chartZen extends chart
{
    /**
     * 根据 chartList 获取要查看的图表。
     * Get the charts to view by chartList.
     *
     * @param  array  $chartList
     * @access protected
     * @return array
     */
    protected function getChartsToView(array $chartList): array
    {
        $charts = array();
        foreach($chartList as $chart)
        {
            $group = $chart['groupID'];
            $chart = $this->chart->getByID($chart['chartID']);
            if($chart)
            {
                $chart->currentGroup = $group;
                $charts[] = $chart;
            }
        }

        return $charts;
    }

    /**
     * 根据 chartID 和 filterValues 获取要筛选的图表。
     * Get the charts to filter by chartID and filterValues.
     *
     * @param  int    $groupID
     * @param  int    $chartID
     * @param  array  $filterValues
     * @access protected
     * @return object|null
     */
    protected function getChartToFilter(int $groupID, int $chartID, array $filterValues): object|null
    {
        $chart = $this->chart->getByID($chartID);
        if(!$chart) return null;

        $chart->currentGroup = $groupID;

        foreach($filterValues as $key => $value) $chart->filters[$key]['default'] = $value;

        return $chart;
    }

    /**
     * 获取筛选器表单中关联字段下拉菜单数据。
     * Get the options for the related field in filter.
     *
     * @access protected
     * @return array
     */
    protected function getOptions4SelectField(): array
    {
        $options       = array();
        $fieldList     = $this->post->fieldList;
        $fieldSettings = $this->post->fieldSettings;
        $langs         = $this->post->langs;
        $clientLang    = $this->app->getClientLang();

        foreach($fieldList as $field => $fieldName)
        {
            $fieldObject  = $fieldSettings[$field]['object'];
            $relatedField = $fieldSettings[$field]['field'];

            $this->app->loadLang($fieldObject);
            $options[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $options[$field] = $langs[$field][$clientLang];
        }

        return $options;
    }

    /**
     * 获取筛选器中默认值和查询的HTML。
     * Get the HTML for the default value and search in filter.
     *
     * @param  string  $key default|item
     * @param  string $type  input|date|datetime|select|condition
     * @param  array  $filter
     * @access protected
     * @return string
     */
    protected function getHTML(string $key, string $type, array $filter): string
    {
        $html    = '';
        $default = isset($filter['default']) ? $filter['default'] : '';

        if($type == 'input')
        {
            $onChange = $key == 'default' ? "onchange='changeDefault(this,this.value)'" : '';
            $html = html::input('default', $default, "class='form-control' $onChange");
        }

        if($type == 'date' || $type == 'datetime')
        {
            if(empty($default)) $default = array('begin' => '', 'end' => '');
            $class = $type == 'date' ? 'form-date' : 'form-datetime';

            $html  = '<div class="input-group">';
            $html .= html::input('default[begin]', $default['begin'], "class='form-control $class default-begin' autocomplete='off' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this, this.value)'");
            $html .= "<span class='input-group-addon fix-border borderBox' style='border-radius: 0px;'>{$this->lang->chart->colon}</span>";
            $html .= html::input('default[end]', $default['end'], "class='form-control $class default-end' autocomplete='off' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this, this.value)'");
            $html .= '</div>';
        }

        if($type == 'select')
        {
            $options       = array();
            $fieldSettings = $this->post->fieldSettings;
            $fieldSetting  = $fieldSettings[$field];
            $options       = $this->chart->getSysOptions(zget($fieldSetting, 'type', ''), zget($fieldSetting, 'object', ''), zget($fieldSetting, 'field', ''), $this->post->sql);
            $onChange      = $key == 'default' ? "onchange='changeDefault(this, this.value)'" : '';

            $html = html::select('default[]', $options, $default, "class='form-control picker-select' $onChange multiple");
        }

        if($type == 'condition')
        {
            $operator   = isset($filter['operator']) ? $filter['operator'] : '';
            $value      = isset($filter['value'])    ? $filter['value']    : '';
            $hidden     = strpos($operator, 'NULL') !== false ? 'hidden' : '';
            $onChange   = $key == 'default' ? "onchange='changeConditionValue(this,this.value)'" : '';
            $showSearch = $key == 'item' ? true : false;

            $html  = "<div class='conditionGroup' style='display:flex'>";
            $html .= html::select('operator', $this->config->bi->conditionList, $operator, "class='form-control picker-select' onchange='changeCondition(this, this.value, $showSearch)'");
            $html .= html::input('value', $value, "class='form-control $hidden' $onChange");
            $html .= "<div/>";
        }

        return $html;
    }
}
