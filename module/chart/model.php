<?php
/**
 * The model file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chartModel extends model
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadBIDAO();
    }

    /**
     * Get first chart group id.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function getFirstChartGroupID($dimensionID)
    {
        return $this->dao->select('id')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('chart')
            ->andWhere('root')->eq($dimensionID)
            ->andWhere('grade')->eq(1)
            ->orderBy('`order`')
            ->limit(1)
            ->fetch('id');
    }

    /**
     * Get chart.
     *
     * @param  int    $chartID
     * @access public
     * @return object
     */
    public function getByID($chartID)
    {
        $chart = $this->dao->select('*')->from(TABLE_CHART)->where('id')->eq($chartID)->fetch();
        if(!$chart) return false;

        if(!empty($chart->fields) and $chart->fields != 'null')
        {
            $chart->fieldSettings = json_decode($chart->fields);
            $chart->fields        = array();

            foreach($chart->fieldSettings as $field => $settings) $chart->fields[] = $field;
        }
        else
        {
            $chart->fieldSettings = array();
        }

        if(!empty($chart->settings) and $chart->settings != 'null')
        {
            $settings = json_decode($chart->settings, true);
            if(isset($settings[0]) and isset($settings[0]['type'])) $chart->type = $settings[0]['type'];
        }

        if($chart->sql == null)     $chart->sql     = '';
        if(!empty($chart->filters)) $chart->filters = json_decode($chart->filters, true);

        return $this->processChart($chart);
    }

    /**
     * Process sql and correct type.
     *
     * @param  object $chart
     * @access public
     * @return object
     */
    public function processChart($chart)
    {
        if(!empty($chart->sql))      $chart->sql      = trim(str_replace(';', '', $chart->sql));
        if(!empty($chart->settings)) $chart->settings = json_decode($chart->settings, true);

        if(empty($chart->type) and !empty($chart->settings) and is_array($chart->settings))
        {
            $firstSetting = current($chart->settings);
            if(isset($firstSetting['type'])) $chart->type = $firstSetting['type'];
        }

        return $chart;
    }

    /**
     * Get tree of charts and groups.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPreviewCharts($groupID, $orderBy = 'id_desc')
    {
        if(!$groupID) return array('', array());

        $currentGroup = $this->loadModel('tree')->getByID($groupID);
        if(empty($currentGroup) || $currentGroup->grade != 1) return array('', array());

        $groups = $this->dao->select('id, grade, name')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('path')->like("{$currentGroup->path}%")->orderBy('`order`')->fetchAll();
        if(!$groups) return array('', array());

        $chartGroups = array();
        $this->loadModel('screen');
        foreach($groups as $group)
        {
            $chartGroups[$group->id] = $this->dao->select('*')->from(TABLE_CHART)
                ->where('deleted')->eq(0)
                ->andWhere('builtin', true)->eq('0')
                ->orWhere('id')->in($this->config->screen->builtinChart)
                ->markRight(1)
                ->andWhere("FIND_IN_SET($group->id, `group`)")
                ->andWhere('stage')->ne('draft')
                ->orderBy($orderBy)
                ->fetchAll();
        }

        if(!$chartGroups) return array('', array());

        $readyCharts = array();
        foreach($chartGroups as $groupID => $chartGroup)
        {
            $charts = array();
            foreach($chartGroup as $chart)
            {
                if($chart->stage != 'published') continue;
                $charts[] = $chart;
            }

            if(!empty($charts)) $readyCharts[$groupID] = $charts;
        }

        $index      = 1;
        $chartTree  = '';
        $firstChart = null;
        foreach($groups as $group)
        {
            if(!isset($readyCharts[$group->id])) continue;

            if($group->grade == 2)
            {
                $class = $index == 1 ? 'open' : 'closed';
                $chartTree .= "<li class='$class'><div class='checkbox-primary'><input type='checkbox' data-type='parent' name='groups[]' id='group{$group->id}' value='{$group->id}' title='{$group->name}'><label>{$group->name}</label></div><ul>";
            }

            $charts = $readyCharts[$group->id];
            foreach($charts as $chart)
            {
                $chart->currentGroup = $group->id;
                if(!$firstChart) $firstChart = $chart;

                $chartTree .= "<li title='{$chart->name}'><div class='checkbox-primary checkbox-inline'><input type='checkbox' data-type='child' name='charts[]' id='chart_{$group->id}_{$chart->id}' value='{$group->id}_{$chart->id}' title='{$chart->name}' data-group='{$group->id}'><label for='chart_{$group->id}_{$chart->id}'></label></div><a>{$chart->name}</a></li>";
            }
            if($group->grade == 2) $chartTree .= '</ul></li>';

            $index++;
        }

        if($chartTree) $chartTree = "<ul id='chartGroups' class='tree' data-ride='tree'>" . $chartTree . '</ul>';

        $charts = array();
        if($firstChart)
        {
            if(!empty($firstChart->settings)) $firstChart->settings = json_decode($firstChart->settings, true);
            if(!empty($firstChart->filters))  $firstChart->filters  = json_decode($firstChart->filters, true);
            if(!empty($firstChart->fields) and $firstChart->fields != 'null')
            {
                $firstChart->fieldSettings = json_decode($firstChart->fields);
                $firstChart->fields        = array_keys(json_decode($firstChart->fields, true));
            }

            $charts[] = $firstChart;
        }

        return array($chartTree, $charts);
    }

    /**
     * Gen radar.
     *
     * @param  int    $fields
     * @param  int    $settings
     * @param  int    $defaultSql
     * @param  int    $filters
     * @param  array  $langs
     * @access public
     * @return void
     */
    public function genRadar($fields, $settings, $defaultSql, $filters, $langs = array())
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters);

        $yDatas = array();
        $max    = 0;
        foreach($yStats as $yStat)
        {
            if(empty($yStat)) continue;

            $data = array();
            foreach($xLabels as $xLabel)
            {
                $yStatXLabel = isset($yStat[$xLabel]) ? $yStat[$xLabel] : 0;
                $data[]      = $yStatXLabel;
            }

            if(max($yStat) > $max) $max = max($yStat);
            $yDatas[] = $data;
        }

        $series = array();
        $legend = new stdclass();
        $series['type'] = 'radar';

        $clientLang = $this->app->getClientLang();
        foreach($yDatas as $index => $yData)
        {
            $fieldName = $fields[$metrics[$index]]['name'];

            if(!empty($fields[$metrics[$index]]['object']) and !empty($fields[$metrics[$index]]['field']))
            {
                $relatedObject = $fields[$metrics[$index]]['object'];
                $relatedField  = $fields[$metrics[$index]]['field'];

                $this->app->loadLang($relatedObject);
                $fieldName = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $fieldName;
            }

            if(isset($langs[$metrics[$index]]) and !empty($langs[$metrics[$index]][$clientLang])) $fieldName = $langs[$metrics[$index]][$clientLang];

            $seriesName       = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series['data'][] = array('name' => $seriesName, 'value' => $yData);
        }

        $indicator  = array();
        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($xLabels as $xLabel)
        {
            $labelName = isset($optionList[$xLabel]) ? $optionList[$xLabel] : $xLabel;
            $indicator[] = array('name' => $labelName, 'max' => $max);
        }

        $options = array('series' => $series, 'legend' => $legend, 'radar' => array('indicator' => $indicator), 'tooltip' => array('trigger' => 'item'));
        return $options;
    }

    /**
     * Gen pie.
     *
     * @param  string $schema
     * @param  object $settings
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function genPie($fields, $settings, $sql, $filters)
    {
        $group  = isset($settings['group'][0]['field']) ? $settings['group'][0]['field'] : '';
        $date   = isset($settings['group'][0]['group']) ? zget($this->config->chart->dateConvert, $settings['group'][0]['group']) : '';
        $metric = isset($settings['metric'][0]['field']) ? $settings['metric'][0]['field'] : '';
        $agg    = isset($settings['metric'][0]['valOrAgg']) ? $settings['metric'][0]['valOrAgg'] : '';

        $defaultSql = str_replace(';', '', $sql);
        $groupSql   = $groupBySql = "tt.`$group`";
        if(!empty($date))
        {
            $groupSql   = $date == 'MONTH' ? "YEAR(tt.`$group`) as ttyear, $date(tt.`$group`) as ttgroup" : "$date(tt.`$group`) as $group";
            $groupBySql = $date == 'MONTH' ? "YEAR(tt.`$group`), $date(tt.`$group`)" : "$date(tt.`$group`)";
        }

        if($agg == 'distinct')
        {
            $aggSQL = "count($agg tt.`$metric`) as `$metric`";
        }
        else
        {
            $aggSQL = "$agg(tt.`$metric`) as `$metric`";
        }

        $sql = "select $groupSql,$aggSQL from ($defaultSql) tt";
        if(!empty($filters))
        {
            $wheres = array();
            foreach($filters as $field => $filter)
            {
                $wheres[] = "$field {$filter['operator']} {$filter['value']}";
            }

            $whereStr = implode(' and ', $wheres);
            $sql .= " where $whereStr";
        }
        $sql .= " group by $groupBySql";
        $rows = $this->dao->query($sql)->fetchAll();
        $stat = $this->processRows($rows, $date, $group, $metric);

        $maxCount = 50;
        if(empty($date)) arsort($stat);

        $other = array_sum(array_splice($stat, $maxCount));
        $stat[$this->lang->chart->other] = $other;
        if(empty($date)) arsort($stat);

        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);

        $data = array();
        foreach($stat as $name => $value)
        {
            if($value == 0) continue;

            $value     = round($value, 2);
            $labelName = isset($optionList[$name]) ? $optionList[$name] : $name;

            $data[] = array('name' => $labelName, 'value' => $value);
        }

        $label  = array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%');
        $legend = new stdclass();
        $legend->type = 'scroll';
        $legend->orient = 'vertical';
        $legend->right  = 0;

        $series[] = array('data' => $data, 'type' => 'pie', 'label' => $label);
        $options = array('series' => $series, 'legend' => $legend, 'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)"));
        return $options;
    }

    /**
     * Process rows.
     *
     * @param  array  $rows
     * @param  string $date
     * @param  string $group
     * @param  string $metric
     * @access public
     * @return array
     */
    public function processRows($rows, $date, $group, $metric)
    {
        $stat = array();
        foreach($rows as $row)
        {
            if(!empty($date) and $date == 'MONTH')
            {
                $stat[sprintf("%04d", $row->ttyear) . '-' . sprintf("%02d", $row->ttgroup)] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEARWEEK')
            {
                $yearweek  = sprintf("%06d", $row->$group);
                $year = substr($yearweek, 0, strlen($yearweek) - 2);
                $week = substr($yearweek, -2);

                $weekIndex = in_array($this->app->getClientLang(), array('zh-cn', 'zh-tw')) ? sprintf($this->lang->chart->groupWeek, $year, $week) : sprintf($this->lang->chart->groupWeek, $week, $year);
                $stat[$weekIndex] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEAR')
            {
                $stat[sprintf("%04d", $row->$group)] = $row->$metric;
            }
            else
            {
                $stat[$row->$group] = $row->$metric;
            }
        }

        return $stat;
    }

    /**
     * Gen line.
     *
     * @param  string $schema
     * @param  object $settings
     * @param  string $sql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genLineChart($fields, $settings, $defaultSql, $filters, $langs = array())
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters);

        $fieldType = $fields[$settings['xaxis'][0]['field']]['type'];
        if($fieldType == 'date') sort($xLabels);

        $yDatas = array();
        foreach($xLabels as $xLabel)
        {
            foreach($yStats as $index => $yStat)
            {
                if(!isset($yDatas[$index])) $yDatas[$index] = array();
                $yDatas[$index][] = isset($yStat[$xLabel]) ? $yStat[$xLabel] : 0;
            }
        }

        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($xLabels as $index => $xLabel) $xLabels[$index] = isset($optionList[$xLabel]) ? $optionList[$xLabel] : $xLabel;

        $xaxis      = array('type' => 'category', 'data' => $xLabels, 'axisTick' => array('alignWithLabel' => true));
        $yaxis      = array('type' => 'value');
        $legend     = new stdclass();
        $series     = array();
        $clientLang = $this->app->getClientLang();
        foreach($yDatas as $index => $yData)
        {
            $fieldName = $fields[$metrics[$index]]['name'];

            if(!empty($fields[$metrics[$index]]['object']) and !empty($fields[$metrics[$index]]['field']))
            {
                $relatedObject = $fields[$metrics[$index]]['object'];
                $relatedField  = $fields[$metrics[$index]]['field'];

                $this->app->loadLang($relatedObject);
                $fieldName = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $fieldName;
            }

            if(isset($langs[$metrics[$index]]) and !empty($langs[$metrics[$index]][$clientLang])) $fieldName = $langs[$metrics[$index]][$clientLang];

            $seriesName = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series[]   = array('name' => $seriesName, 'data' => $yData, 'type' => 'line');
        }

        $grid = array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true);

        $options = array('series' => $series, 'grid' => $grid, 'legend' => $legend, 'xAxis' => $xaxis, 'yAxis' => $yaxis, 'tooltip' => array('trigger' => 'axis'));
        return $options;
    }

    /**
     * Gen cluBar.
     *
     * @param  int    $fields
     * @param  int    $settings
     * @param  int    $defaultSql
     * @param  int    $filters
     * @param  int    $stack
     * @param  array  $langs
     * @access public
     * @return void
     */
    public function genCluBar($fields, $settings, $defaultSql, $filters, $stack = '', $langs = array())
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters);

        $yDatas = array();
        foreach($yStats as $yStat)
        {
            $data = array();
            foreach($xLabels as $xLabel)
            {
                $data[] = isset($yStat[$xLabel]) ? $yStat[$xLabel] : 0;
            }
            $yDatas[] = $data;
        }

        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($xLabels as $index => $xLabel) $xLabels[$index] = isset($optionList[$xLabel]) ? $optionList[$xLabel] : $xLabel;

        $xaxis  = array('type' => 'category', 'data' => $xLabels, 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true));
        $yaxis  = array('type' => 'value');
        $legend = new stdclass();

        /* Cluster bar X graphs and cluster bar Y graphs are really just x and y axes switched, so cluster bar Y $xaixs and $yaxis are swapped so that the method can be reused. */
        /* 簇状柱形图和簇状条形图其实只是x轴和y轴换了换，所以交换一下簇状条形图 xAxis和yAxis即可，这样方法就可以复用了。*/

        $position = 'top';
        if($settings['type'] == 'stackedBar' or $settings['type'] == 'stackedBarY')  $position = 'inside';
        if($settings['type'] == 'cluBarY') $position = 'right';
        $label = array('show' => true, 'position' => $position, 'formatter' => '{c}');

        if(in_array($settings['type'], array('cluBarY', 'stackedBarY'))) list($xaxis, $yaxis) = array($yaxis, $xaxis);

        $series     = array();
        $clientLang = $this->app->getClientLang();
        foreach($yDatas as $index => $yData)
        {
            $fieldName = $fields[$metrics[$index]]['name'];

            if(!empty($fields[$metrics[$index]]['object']) and !empty($fields[$metrics[$index]]['field']))
            {
                $relatedObject = $fields[$metrics[$index]]['object'];
                $relatedField  = $fields[$metrics[$index]]['field'];

                $this->app->loadLang($relatedObject);
                $fieldName = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $fieldName;
            }

            if(isset($langs[$metrics[$index]]) and !empty($langs[$metrics[$index]][$clientLang])) $fieldName = $langs[$metrics[$index]][$clientLang];

            $seriesName = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series[]   = array('name' => $seriesName, 'data' => $yData, 'type' => 'bar', 'stack' => $stack, 'label' => $label);
        }

        $dataZoomX = '[{"type":"inside","startValue":0,"endValue":5,"minValueSpan":10,"maxValueSpan":10,"xAxisIndex":[0],"zoomOnMouseWheel":false,"moveOnMouseWheel":true,"moveOnMouseMove":true},{"type":"slider","realtime":true,"startValue":0,"endValue":5,"zoomLock":true,"brushSelect":false,"width":"80%","height":"5","xAxisIndex":[0],"fillerColor":"#ccc","borderColor":"#33aaff00","backgroundColor":"#cfcfcf00","handleSize":0,"showDataShadow":false,"showDetail":false,"bottom":"0","left":"10%"}]';
        $dataZoomY = '[{"type":"inside","startValue":0,"endValue":5,"minValueSpan":10,"maxValueSpan":10,"yAxisIndex":[0],"zoomOnMouseWheel":false,"moveOnMouseWheel":true,"moveOnMouseMove":true},{"type":"slider","realtime":true,"startValue":0,"endValue":5,"zoomLock":true,"brushSelect":false,"width":5,"height":"80%","yAxisIndex":[0],"fillerColor":"#ccc","borderColor":"#33aaff00","backgroundColor":"#cfcfcf00","handleSize":0,"showDataShadow":false,"showDetail":false,"top":"10%","right":0}]';
        $isY = in_array($settings['type'], array('cluBarY', 'stackedBarY'));
        $dataZoom = $isY ? json_decode($dataZoomY, true) : json_decode($dataZoomX, true);

        $grid = array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true);

        $options = array('series' => $series, 'grid' => $grid, 'legend' => $legend, 'xAxis' => $xaxis, 'yAxis' => $yaxis, 'tooltip' => array('trigger' => 'axis'), 'dataZoom' => $dataZoom);
        return $options;
    }

    /**
     * Get multi data.
     *
     * @param  int    $settings
     * @param  int    $defaultSql
     * @param  int    $filters
     * @access public
     * @return void
     */
    public function getMultiData($settings, $defaultSql, $filters, $sort = false)
    {
        $group   = isset($settings['xaxis'][0]['field']) ? $settings['xaxis'][0]['field'] : '';
        $date    = isset($settings['xaxis'][0]['group']) ? zget($this->config->chart->dateConvert, $settings['xaxis'][0]['group']) : '';
        $metrics = array();
        $aggs    = array();
        foreach($settings['yaxis'] as $yaxis)
        {
            $metrics[] = $yaxis['field'];
            $aggs[]    = $yaxis['valOrAgg'];
        }
        $yCount  = count($metrics);

        $xLabels = array();
        $yStats  = array();

        for($i = 0; $i < $yCount; $i ++)
        {
            $metric   = $metrics[$i];
            $agg      = $aggs[$i];

            $groupSql   = $groupBySql = "tt.`$group`";
            if(!empty($date))
            {
                $groupSql   = $date == 'MONTH' ? "YEAR(tt.`$group`) as ttyear, $date(tt.`$group`) as ttgroup" : "$date(tt.`$group`) as $group";
                $groupBySql = $date == 'MONTH' ? "YEAR(tt.`$group`), $date(tt.`$group`)" : "$date(tt.`$group`)";
            }

            if($agg == 'distinct')
            {
                $aggSQL = "count($agg tt.`$metric`) as `$metric`";
            }
            else
            {
                $aggSQL = "$agg(tt.`$metric`) as `$metric`";
            }

            $sql = "select $groupSql,$aggSQL from ($defaultSql) tt";
            if(!empty($filters))
            {
                $wheres = array();
                foreach($filters as $field => $filter)
                {
                    $wheres[] = "`$field` {$filter['operator']} {$filter['value']}";
                }

                $whereStr = implode(' and ', $wheres);
                $sql .= " where $whereStr";
            }
            $sql .= " group by $groupBySql";
            $rows = $this->dao->query($sql)->fetchAll();
            $stat = $this->processRows($rows, $date, $group, $metric);

            $maxCount = 50;
            if($sort) arsort($stat);
            $yStats[] = $stat;

            $xLabels = array_merge($xLabels, array_keys($stat));
            $xLabels = array_unique($xLabels);
        }

        return array($group, $metrics, $aggs, $xLabels, $yStats);
    }

    /**
     * Generate waterpolo for screen designer.
     *
     * @param  string $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function genWaterpolo($settings, $sql, $filters)
    {
        $operate = "{$settings['calc']}({$settings['goal']})";
        $sql = "select $operate count from ($sql) tt ";

        $moleculeSQL    = $sql;
        $denominatorSQL = $sql;

        $moleculeWheres    = array();
        $denominatorWheres = array();
        foreach($settings['conditions'] as $condition)
        {
            $where = "{$condition['field']} {$this->lang->chart->conditionList[$condition['condition']]} '{$condition['value']}'";
            $moleculeWheres[]    = $where;
        }

        if(!empty($filters))
        {
            $wheres = array();
            foreach($filters as $field => $filter)
            {
                $wheres[] = "$field {$filter['operator']} {$filter['value']}";
            }
            $moleculeWheres    = array_merge($moleculeWheres, $wheres);
            $denominatorWheres = $wheres;
        }

        if($moleculeWheres)    $moleculeSQL    .= 'where ' . implode(' and ', $moleculeWheres);
        if($denominatorWheres) $denominatorSQL .= 'where ' . implode(' and ', $denominatorWheres);

        $molecule    = $this->dao->query($moleculeSQL)->fetch();
        $denominator = $this->dao->query($denominatorSQL)->fetch();

        $percent = $denominator->count ? round((int)$molecule->count / (int)$denominator->count, 4) : 0;

        $series  = array(array('type' => 'liquidFill', 'data' => array($percent), 'color' => array('#2e7fff'), 'outline' => array('show' => false), 'label' => array('fontSize' => 26)));
        $tooltip = array('show' => true);

        $options = array('series' => $series, 'tooltip' => $tooltip);

        return $options;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object $chart
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($chart, $action)
    {
        if($chart->id <= 20015) return false;
        if($chart->builtin) return false;
        return true;
    }

    /**
     * Get sys options.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getSysOptions($type, $object = '', $field = '', $sql = '')
    {
        $options = array('' => '');
        switch($type)
        {
            case 'user':
                $options = $this->loadModel('user')->getPairs();
                break;
            case 'product':
                $options = $this->loadModel('product')->getPairs();
                break;
            case 'project':
                $options = $this->loadModel('project')->getPairsByProgram();
                break;
            case 'execution':
                $options = $this->loadModel('execution')->getPairs();
                break;
            case 'dept':
                $options = $this->loadModel('dept')->getOptionMenu(0);
                break;
            case 'option':
                if($field)
                {
                    $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
                    if(is_file($path))
                    {
                        include $path;
                        $options = $schema->fields[$field]['options'];
                    }
                }
                break;
            case 'object':
                if($field)
                {
                    $table = zget($this->config->objectTables, $object, '');
                    if($table) $options = $this->dao->select("id, {$field}")->from($table)->fetchPairs();
                }
                break;
            case 'string':
            case 'number':
                if($field and $sql)
                {
                    $cols = $this->dbh->query($sql)->fetchAll();
                    foreach($cols as $col)
                    {
                        $data = $col->$field;
                        $options[$data] = $data;
                    }
                }
                break;
        }

        return $options;
    }

    public function getFilterFormat($filters)
    {
        $filterFormat = array();
        foreach($filters as $filter)
        {
            $field = $filter['field'];
            if(!isset($filter['default'])) continue;

            $default = $filter['default'];
            switch($filter['type'])
            {
                case 'select':
                    if(empty($default)) break;
                    if(!is_array($default)) $default = array($default);
                    $default = array_filter($default, function($val){return !empty($val);});
                    $value = "('" . implode("', '", $default) . "')";
                    $filterFormat[$field] = array('operator' => 'IN', 'value' => $value);
                    break;
                case 'input':
                    $filterFormat[$field] = array('operator' => 'like', 'value' => "'%$default%'");
                    break;
                case 'date':
                case 'datetime':
                    $begin = $default['begin'];
                    $end   = $default['end'];

                    if(empty($begin) or empty($end)) break;

                    $value = "'$begin' and '$end'";
                    $filterFormat[$field] = array('operator' => 'BETWEEN', 'value' => $value);
                    break;
                case 'condition':
                    $operator = $filter['operator'];
                    $value    = $filter['value'];

                    if(in_array($operator, array('IN', 'NOT IN')))
                    {
                        $valueArr = explode(',', $value);
                        foreach($valueArr as $key => $val) $valueArr[$key] = '"' . $val . '"';
                        $value = '(' . implode(',', $valueArr) . ')';
                    }
                    elseif(in_array($operator, array('IS NOT NULL', 'IS NULL')))
                    {
                        $value = '';
                    }
                    $filterFormat[$field] = array('operator' => $operator, 'value' => $value);
                    break;
            }
        }

        return $filterFormat;
    }

    /**
     * Get tables.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getTables($sql)
    {
        $processedSql = trim($sql, ';');
        $processedSql = str_replace(array("\r\n", "\n"), ' ', $processedSql);
        $processedSql = str_replace('`', '', $processedSql);
        preg_match_all('/^select (.+) from (.+)$/i', $processedSql, $selectAndFrom);
        if(empty($selectAndFrom[2][0])) return false;

        $selectSql = $selectAndFrom[1][0];
        $tableSql  = $fromSql = $selectAndFrom[2][0];
        if(stripos($fromSql, 'where') !== false)    $tableSql = trim(substr($fromSql, 0, stripos($fromSql, 'where')));
        if(stripos($fromSql, 'limit') !== false)    $tableSql = trim(substr($fromSql, 0, stripos($fromSql, 'limit')));
        if(stripos($fromSql, 'having') !== false)   $tableSql = trim(substr($fromSql, 0, stripos($fromSql, 'having')));
        if(stripos($fromSql, 'group by') !== false) $tableSql = trim(substr($fromSql, 0, stripos($fromSql, 'group by')));

        /* Remove such as "left join|right join|join", "on (t1.id=t2.id)", result like t1, t2 as t3. */
        $tableSql .= ' ';
        if(stripos($tableSql, 'join') !== false) $tableSql = preg_replace(array('/join\s+([A-Z]+_\w+ .*)on/Ui', '/,\s*on\s+[^,]+/i'), array(',$1,on', ''), $tableSql);

        /* Match t2 as t3 */
        preg_match_all('/(\w+) +as +(\w+)/i', $tableSql, $out);

        $fields = explode(',', $selectSql);
        foreach($fields as $i => $field)
        {
            if($field) $asField = '';
            if(strrpos($field, ' as ') !== false) list($field, $asField) = explode(' as ', $field);
            if(strrpos($field, ' AS ') !== false) list($field, $asField) = explode(' AS ', $field);

            $field     = trim($field);
            $asField   = trim($asField);
            $fieldName = $field;
            if(strrpos($field, '.') !== false)
            {
                $table     = substr($field, 0, strrpos($field, '.'));
                $fieldName = substr($field, strrpos($field, '.') + 1);
                if(!empty($out[0]) and in_array($table, $out[2]))
                {
                    $realTable = $out[1][array_search($table, $out[2])];
                    $tableFieldName = str_replace($table . '.', $realTable . '.', $field);

                    if(isset($fields[$fieldName]) && !$asField)
                    {
                        $fieldName = str_replace('.', '', $field);

                        //$sql = preg_replace(array("/$field/", "/`$field`/"), array("$field AS $fieldName", "`$field` AS $fieldName"), $sql, 1);

                        $field = $tableFieldName;
                    }
                }

                if($fieldName == '*') $fieldName = $field;
            }

            $fieldName = $asField ? $asField : $fieldName;

            $fields[$fieldName] = $field;
            unset($fields[$i]);
        }

        $tableSql = preg_replace('/as +\w+/i', ' ', $tableSql);
        $tableSql = trim(str_replace(array('(', ')', ','), ' ', $tableSql));
        $tableSql = preg_replace('/ +/', ' ', $tableSql);

        $tables = explode(' ', $tableSql);

        return array('sql' => $sql, 'tables' => $tables, 'fields' => $fields);
    }

    /**
     * Parse variables to null string in sql.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function parseSqlVars($sql, $filters)
    {
        if($filters)
        {
            foreach($filters as $filter)
            {
                if(!isset($filter['default'])) continue;
                if(isset($filter['from']) and $filter['from'] == 'query')
                {
                    $default = "'{$filter['default']}'";
                    $sql     = str_replace('$' . $filter['field'], $default, $sql);
                }
            }
        }
        if(preg_match_all("/[\$]+[a-zA-Z0-9]+/", $sql, $out))
        {
            foreach($out[0] as $match) $sql = str_replace($match, "''", $sql);
        }

        return $sql;
    }
}

