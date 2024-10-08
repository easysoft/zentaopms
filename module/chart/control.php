<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class chart extends control
{
    /**
     * __construct method.
     *
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->dao->exec("SET @@sql_mode=''");
    }

    /**
     * 预览图表。
     * Preview charts of a group.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function preview(int $dimensionID = 0, int $groupID = 0)
    {
        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);

        if(!is_numeric($groupID)) $groupID = 0;
        if(!$groupID) $groupID = $this->chart->getFirstGroup($dimensionID);

        $charts = array();
        if($_POST)
        {
            if($this->post->charts)
            {
                /* 选中多个图表查看。*/
                /* Select multiple charts to view. */
                $charts = $this->getChartsToView($this->post->charts);
            }
            else if($this->post->groupID && $this->post->chartID)
            {
                /* 更改一个图表的过滤条件。*/
                /* Change filter conditions of a chart. */
                $chart = $this->getChartToFilter((int)$this->post->groupID, (int)$this->post->chartID, $this->post->filterValues ? $this->post->filterValues : array());
                if($chart) $charts[] = $chart;
            }
        }

        if(!$charts) $charts = $this->chart->getDefaultCharts($groupID);

        $this->view->title       = $this->lang->chart->preview;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 1);
        $this->view->treeMenu    = $this->chart->getTreeMenu($groupID);
        $this->view->recTotal    = count($this->getMenuItems($this->view->treeMenu));
        $this->view->charts      = $charts;
        $this->view->dimensionID = $dimensionID;
        $this->view->groupID     = $groupID;
        $this->display();
    }

    /**
     * 获取筛选器表单。
     * Ajax get filter form html.
     *
     * @access public
     * @return string
     */
    public function ajaxGetFilterForm()
    {
        $fieldList     = $this->post->fieldList;
        $filters       = $this->post->filters;
        $fieldSettings = $this->post->fieldSettings;
        $langs         = $this->post->langs;
        $sql           = $this->post->sql;
        $clientLang    = $this->app->getClientLang();

        $verifyResult = $this->loadModel('dataview')->verifySqlWithModify($sql);

        if($verifyResult !== true) return $this->send($verifyResult);

        $fieldPairs = array();
        foreach($fieldList as $field => $fieldName)
        {
            $fieldObject  = $fieldSettings[$field]['object'];
            $relatedField = $fieldSettings[$field]['field'];

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }

        $htmls = array();
        foreach($filters as $filter)
        {
            $field   = $filter['field'];
            $type    = $filter['type'];
            $default = isset($filter['default']) ? $filter['default'] : '';

            $saveAs      = isset($filter['saveAs']) ? $filter['saveAs'] : '';
            $saveAsClass = $type == 'select' ? '' : 'hidden'; // 只有是选择的时候，才展示显示为

            $options = array();
            if($type == 'select')
            {
                $fieldSetting = $fieldSettings[$field];
                $options      = $this->chart->getSysOptions(zget($fieldSetting, 'type', ''), zget($fieldSetting, 'object', ''), zget($fieldSetting, 'field', ''), $sql, zget($filter, 'saveAs', ''));
            }

            $filterHtml = array();

            /* field html */
            $filterHtml['field']  = html::select('field', $fieldPairs, $field, "class='form-control picker-select' onchange='initFilterForm(this, this.value)'");
            $filterHtml['saveAs'] = html::select('saveAs', array('' => '') + $fieldPairs, $saveAs, "class='form-control picker-select' onchange='changeSaveAs(this, this.value)'");
            $filterHtml['saveAsClass'] = $saveAsClass;

            /* default html */
            $filterHtml['default'] = '';

            if($type == 'input') $filterHtml['default'] .= html::input('default', $default, "class='form-control' onchange='changeDefault(this,this.value)'");
            if($type == 'date' or $type == 'datetime')
            {
                if(empty($default)) $default = array('begin' => '', 'end' => '');
                $class = $type == 'date' ? 'form-date' : 'form-datetime';
                $filterHtml['default'] .= '<div class="input-group">';
                $filterHtml['default'] .= html::input('default[begin]', $default['begin'], "class='form-control $class default-begin' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this,this.value)'");
                $filterHtml['default'] .= "<span class='input-group-addon fix-border borderBox' style='border-radius: 0px;'>{$this->lang->chart->colon}</span>";
                $filterHtml['default'] .= html::input('default[end]', $default['end'], "class='form-control $class default-end' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this,this.value)'");
                $filterHtml['default'] .= '</div>';
            }
            if($type == 'select') $filterHtml['default'] = html::select('default[]', $options, $default, "class='form-control picker-select' onchange='changeDefault(this,this.value)' multiple");
            if($type == 'condition')
            {
                $operator = isset($filter['operator']) ? $filter['operator'] : '';
                $value    = isset($filter['value'])    ? $filter['value']    : '';
                $hidden   = strpos($operator, 'NULL') !== false ? 'hidden' : '';
                $filterHtml['default']  = "<div class='conditionGroup' style='display:flex'>";
                $filterHtml['default'] .= html::select('operator', $this->config->bi->conditionList, $operator, "class='form-control picker-select' onchange='changeCondition(this,this.value)'");
                $filterHtml['default'] .= html::input('value', $value, "class='form-control $hidden' onchange='changeConditionValue(this,this.value)'");
                $filterHtml['default'] .= "<div/>";
            }

            /* type html */
            $filterHtml['type'] = html::select('type', $this->lang->chart->fieldTypeList, $type, "class='form-control picker-select' onchange='changeType(this, this.value)'");

            /* filter item html */
            $filterHtml['item'] = '';
            if($type == 'input') $filterHtml['item'] .= html::input('default', $default, "class='form-control'");
            if($type == 'date' or $type == 'datetime')
            {
                if(empty($default)) $default = array('begin' => '', 'end' => '');
                $class = $type == 'date' ? 'form-date' : 'form-datetime';
                $filterHtml['item'] .= '<div class="input-group">';
                $filterHtml['item'] .= "<input type='text' name='default[begin]' id='default[begin]' value='{$default['begin']}' class='form-control $class default-begin' autocomplete='off' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this, this.value)'>";
                $filterHtml['item'] .= '<span class="input-group-addon fix-border borderBox" style="border-radius: 0px;">' . $this->lang->chart->colon . '</span>';
                $filterHtml['item'] .= "<input type='text' name='default[end]' id='default[end]' value='{$default['end']}' class='form-control $class default-end' autocomplete='off' placeholder='{$this->lang->chart->unlimited}' onchange='changeDefault(this, this.value)'>";
                $filterHtml['item'] .= '</div>';
            }
            if($type == 'select') $filterHtml['item'] .= html::select('default', array('' => '') + $options, $default, "class='form-control picker-select' multiple");
            if($type == 'condition')
            {
                $operator = isset($filter['operator']) ? $filter['operator'] : '';
                $value    = isset($filter['value'])    ? $filter['value']    : '';
                $hidden   = strpos($operator, 'NULL') !== false ? 'hidden' : '';
                $filterHtml['item']  = "<div class='conditionGroup' style='display:flex'>";
                $filterHtml['item'] .= html::select('operator', $this->config->bi->conditionList, $operator, "class='form-control picker-select' onchange='changeCondition(this,this.value, true)'");
                $filterHtml['item'] .= html::input('value', $value, "class='form-control $hidden'");
                $filterHtml['item'] .= "<div/>";
            }

            $htmls[] = $filterHtml;
        }

        echo json_encode($htmls);
    }

    /**
     * 获取图表。
     * Ajax get chart.
     *
     * @access public
     * @return void
     */
    public function ajaxGetChart()
    {
        $post = fixer::input('post')->skipSpecial('settings,filters,sql,langs')->get();

        $settings = current($post->settings);
        $type     = $settings['type'];
        $filters  = isset($_POST['searchFilters']) ? $_POST['searchFilters'] : (isset($_POST['filters']) ? $post->filters : array());

        $filterFormat = $this->chart->getFilterFormat($filters);

        $sql    = str_replace(';', '', "$post->sql");
        $fields = $post->fieldSettings;
        $langs  = !empty($post->langs) ? $post->langs : array();
        if(is_string($langs)) $langs = json_decode($langs, true);

        switch($type)
        {
            case 'line':
                $data = $this->chart->genLineChart($fields, $settings, $sql, $filterFormat, $langs);
                break;
            case 'cluBarX':
                $data = $this->chart->genCluBar($fields, $settings, $sql, $filterFormat, '', $langs);
                break;
            case 'cluBarY':
                $data = $this->chart->genCluBar($fields, $settings, $sql, $filterFormat, '', $langs);
                break;
            case 'pie':
                $data = $this->chart->genPie($fields, $settings, $sql, $filterFormat);
                break;
            case 'radar':
                $data = $this->chart->genRadar($fields, $settings, $sql, $filterFormat, $langs);
                break;
            case 'stackedBar':
                $data = $this->chart->genCluBar($fields, $settings, $sql, $filterFormat, 'total', $langs);
                break;
            case 'stackedBarY':
                $data = $this->chart->genCluBar($fields, $settings, $sql, $filterFormat, 'total', $langs);
                break;
            case 'waterpolo':
                $data = $this->chart->genWaterpolo($settings, $sql, $filterFormat);
                break;
        }

        // 对于能进行旋转的图表，判断有没有设置旋转
        if(in_array($type, $this->config->chart->canLabelRotate))
        {
            if(isset($settings['rotateX']) and $settings['rotateX'] == 'use') $data['xAxis']['axisLabel'] = array('rotate' => 30);
            if(isset($settings['rotateY']) and $settings['rotateY'] == 'use') $data['yAxis']['axisLabel'] = array('rotate' => 30);
        }

        echo json_encode($data);
    }
}
