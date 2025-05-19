<?php
/**
 * The model file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class chartModel extends model
{
    /**
     * Construct.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct(string $appName = '')
    {
        parent::__construct($appName);
        $this->loadBIDAO();
        $this->loadModel('bi');
    }

    /**
     * 判断是否有权限访问。
     * Check chart access.
     *
     * @param  int    $chartID
     * @access public
     * @return array
     */
    public function checkAccess($chartID, $method = 'preview')
    {
        $viewableObjects = $this->bi->getViewableObject('chart');
        if(!in_array($chartID, $viewableObjects))
        {
            return $this->app->control->sendError($this->lang->chart->accessDenied, helper::createLink('chart', $method));
        }
    }

    /**
     * 获取指定维度下的第一个分组 id。
     * Get the first group id under the specified dimension.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int|string
     */
    public function getFirstGroup(int $dimensionID): int|string
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
     * 获取指定分组下默认显示的图表。
     * Get the charts displayed by default under the specified group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getDefaultCharts(int $groupID): array
    {
        $group = $this->loadModel('tree')->getByID($groupID);
        if(empty($group) || $group->grade != 1) return array();

        $groups = $this->dao->select('id')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('path')->like(",{$groupID},%")->orderBy('`order`')->fetchPairs();
        if(!$groups) return array();

        $this->app->loadModuleConfig('screen');

        $viewableObjects = $this->bi->getViewableObject('chart');
        /* 获取分组下的第一个图表。*/
        /* Get the first chart under the group. */
        foreach($groups as $groupID)
        {
            $chart = $this->dao->select('*')->from(TABLE_CHART)
                ->where('deleted')->eq('0')
                ->andWhere('builtin', true)->eq('0')
                ->orWhere('id')->in($this->config->screen->builtinChart)
                ->markRight(1)
                ->andWhere("FIND_IN_SET({$groupID}, `group`)")
                ->andWhere('stage')->eq('published')
                ->andWhere('id')->in($viewableObjects)
                ->orderBy('id_desc')
                ->limit(1)
                ->fetch();
            if($chart)
            {
                $chart = $this->processChart($chart);
                $chart->currentGroup = $groupID;

                return array($chart);
            }
        }

        return array();
    }

    /**
     * 根据 id 获取一个图表。
     * Get a chart by id.
     *
     * @param  int    $chartID
     * @access public
     * @return object
     */
    public function getByID(int $chartID): object|false
    {
        $chart = $this->dao->select('*')->from(TABLE_CHART)->where('id')->eq($chartID)->fetch();
        if(!$chart) return false;

        return $this->processChart($chart);
    }

    /**
     * 处理图表的数据以供后续使用。
     * Process the data of the chart for subsequent use.
     *
     * @param  object $chart
     * @access public
     * @return object
     */
    public function processChart(object $chart): object
    {
        if($chart->sql == null) $chart->sql = '';
        if($chart->sql) $chart->sql = trim(str_replace(';', '', $chart->sql));

        $chart->langs = json_decode($chart->langs, true);
        if($chart->langs === null) $chart->langs = array();

        $chart->filters = json_decode($chart->filters, true);
        if($chart->filters === null) $chart->filters = array();

        $chart->settings = json_decode($chart->settings, true);
        if($chart->settings === null) $chart->settings = array();
        if(!empty($chart->settings[0]['type']) && empty($chart->type)) $chart->type = $chart->settings[0]['type'];

        $chart->fieldSettings = json_decode($chart->fields, true);
        if($chart->fieldSettings === null) $chart->fieldSettings = array();
        $chart->fields = array_keys($chart->fieldSettings);

        return $chart;
    }

    /**
     * 生成分组和图表混排的菜单树。
     * Generate a menu tree that mixes groups and charts.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getTreeMenu(int $groupID, string $orderBy = 'id_desc'): array
    {
        if(!$groupID) return array();

        $group = $this->loadModel('tree')->getByID($groupID);
        if(empty($group) || $group->grade != 1) return array();

        $groups = $this->dao->select('id, grade, name')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('path')->like("{$group->path}%")->orderBy('`order`')->fetchAll();
        if(!$groups) return array();

        $this->app->loadModuleConfig('screen');

        $viewableObjects = $this->bi->getViewableObject('chart');
        /* 获取每个分组下的图表以供生成菜单树。*/
        /* Get the charts under each group for generating the menu tree. */
        $chartGroups = array();
        foreach($groups as $group)
        {
            $chartGroups[$group->id] = $this->dao->select('id, name')->from(TABLE_CHART)
                ->where('deleted')->eq('0')
                ->andWhere('builtin', true)->eq('0')
                ->orWhere('id')->in($this->config->screen->builtinChart)
                ->markRight(1)
                ->andWhere("FIND_IN_SET({$group->id}, `group`)")
                ->andWhere('stage')->eq('published')
                ->andWhere('id')->in($viewableObjects)
                ->orderBy($orderBy)
                ->fetchAll();
        }
        if(!$chartGroups) return array();

        $treeMenu = array();
        foreach($groups as $group)
        {
            if(empty($chartGroups[$group->id])) continue;

            /* 菜单树中只显示二级分组名称。*/
            /* Only the name of the second-level group is displayed in the menu tree. */
            if($group->grade == 2) $treeMenu[] = (object)array('id' => $group->id, 'parent' => 0, 'name' => $group->name);

            foreach($chartGroups[$group->id] as $chart) $treeMenu[] = (object)array('id' => $group->id . '_' . $chart->id, 'parent' => $group->id, 'name' => $chart->name);
        }

        return $treeMenu;
    }

    /**
     * 获取图表的 echarts 配置。
     * Get the echarts configuration of the chart.
     *
     * @param  object $chart
     * @access public
     * @return array
     */
    public function getEchartOptions(object $chart): array
    {
        $settings = current($chart->settings);
        $type     = $settings['type'];
        $driver   = $chart->driver;
        $options  = array();

        $filterFormat = $this->getFilterFormat($chart->filters);

        if($type == 'pie')   $options = $this->genPie($chart->fieldSettings, $settings, $chart->sql, $filterFormat, $driver);
        if($type == 'radar') $options = $this->genRadar($chart->fieldSettings, $settings, $chart->sql, $filterFormat, $chart->langs, $driver);
        if($type == 'line')  $options = $this->genLineChart($chart->fieldSettings, $settings, $chart->sql, $filterFormat, $chart->langs, $driver);
        if($type == 'cluBarX'    || $type == 'cluBarY')     $options = $this->genCluBar($chart->fieldSettings, $settings, $chart->sql, $filterFormat, '', $chart->langs, $driver);
        if($type == 'stackedBar' || $type == 'stackedBarY') $options = $this->genCluBar($chart->fieldSettings, $settings, $chart->sql, $filterFormat, 'total', $chart->langs, $driver);
        if($type == 'waterpolo') $options = $this->bi->genWaterpolo($chart->fieldSettings, $settings, $chart->sql, $filterFormat, $driver);

        if(empty($options)) return array();

        $options = $this->addFormatter4Echart($options, $type);
        $options = $this->addRotate4Echart($options, $settings, $type);

        return $options;
    }

    /**
     * 为 echart options 添加 formatter。
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return array
     */
    public function addFormatter4Echart(array $options, string $type): array
    {
        if($type == 'waterpolo')
        {
            $formatter = "RAWJS<(params) => (params.value * 100).toFixed(2) + '%'>RAWJS";
            $options['series'][0]['label']['formatter'] = $formatter;
            $options['tooltip']['formatter'] = $formatter;
        }
        elseif(in_array($type, $this->config->chart->canLabelRotate))
        {
            $labelMaxLength = $this->config->chart->labelMaxLength;
            $labelFormatter = "RAWJS<(value) => {value = value.toString(); return value.length <= $labelMaxLength ? value : value.substring(0, $labelMaxLength) + '...'}>RAWJS";

            if(!isset($options['xAxis']['axisLabel'])) $options['xAxis']['axisLabel'] = array();
            if(!isset($options['yAxis']['axisLabel'])) $options['yAxis']['axisLabel'] = array();
            $options['xAxis']['axisLabel']['formatter'] = $labelFormatter;
            $options['yAxis']['axisLabel']['formatter'] = $labelFormatter;
        }

        return $options;
    }

    /**
     * 为 echart options 添加 rotate。
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return array
     */
    public function addRotate4Echart(array $options, array $settings, string $type): array
    {
        if(in_array($type, $this->config->chart->canLabelRotate))
        {
            if(isset($settings['rotateX']) and $settings['rotateX'] == 'use') $options['xAxis']['axisLabel']['rotate'] = 30;
            if(isset($settings['rotateY']) and $settings['rotateY'] == 'use') $options['yAxis']['axisLabel']['rotate'] = 30;
        }

        return $options;
    }

    /**
     * 雷达图。
     * Gen radar.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genRadar(array $fields, array $settings, string $defaultSql, array $filters, array $langs = array(), $driver = 'mysql'): array
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters, $driver);

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
        $series['type'] = 'radar';
        foreach($yDatas as $index => $yData)
        {
            $fieldName  = $this->chartTao->switchFieldName($fields, $langs, $metrics, $index);
            $seriesName = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series['data'][] = array('name' => $seriesName, 'value' => $yData);
        }

        $indicator  = array();
        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($xLabels as $xLabel)
        {
            $labelName = isset($optionList[$xLabel]) ? $optionList[$xLabel] : $xLabel;
            $indicator[] = array('name' => $labelName, 'max' => $max);
        }

        return array('series' => $series, 'radar' => array('indicator' => $indicator, 'center' => array('50%', '55%')), 'tooltip' => array('trigger' => 'item'));
    }

    /**
     * 饼图。
     * Gen pie.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function genPie(array $fields, array $settings, string $sql, array $filters, string $driver = 'mysql'): array
    {
        $group  = isset($settings['group'][0]['field']) ? $settings['group'][0]['field'] : '';
        $date   = isset($settings['group'][0]['group']) ? zget($this->config->chart->dateConvert, $settings['group'][0]['group']) : '';
        $metric = isset($settings['metric'][0]['field']) ? $settings['metric'][0]['field'] : '';
        $agg    = isset($settings['metric'][0]['valOrAgg']) ? $settings['metric'][0]['valOrAgg'] : '';

        $rows = $this->chartTao->getRows(str_replace(';', '', $sql), $filters, $date, $group, $metric, $agg, $driver);
        $stat = $this->chartTao->processRows($rows, $date, $group, $metric);
        if(empty($date)) arsort($stat);

        /* 若查询结果大于50条，将50条之后的结果归于其他。*/
        /* If the query results are greater than 50, the results after 50 will be classified as other. */
        $maxCount = 50;
        if(count($stat) > $maxCount)
        {
            $other = array_sum(array_slice($stat, $maxCount));
            $stat  = array_slice($stat, 0, $maxCount);
            $stat[$this->lang->chart->other] = $other;
        }

        $seriesData = array();
        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($stat as $name => $value)
        {
            if(empty($value)) continue;

            $labelName = isset($optionList[$name]) ? $optionList[$name] : $name;
            $value     = round($value, 2);

            $seriesData[] = array('name' => $labelName, 'value' => $value);
        }

        $label    = array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%');
        $series[] = array('data' => $seriesData, 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => $label);

        $legend = new stdclass();
        $legend->type   = 'scroll';
        $legend->orient = 'horizontal';
        $legend->left  = 'center';
        $legend->top   = 'top';

        return array('series' => $series, 'legend' => $legend, 'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)"));
    }


    /**
     * 折线图。
     * Gen line.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genLineChart(array $fields, array $settings, string $defaultSql, array $filters, array $langs = array(), string $driver = 'mysql'): array
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters, $driver);

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

        $series = array();
        foreach($yDatas as $index => $yData)
        {
            $fieldName  = $this->chartTao->switchFieldName($fields, $langs, $metrics, $index);
            $seriesName = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series[]   = array('name' => $seriesName, 'data' => $yData, 'type' => 'line');
        }

        $grid  = array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true);
        $xaxis = array('type' => 'category', 'data' => $xLabels, 'axisTick' => array('alignWithLabel' => true));
        $yaxis = array('type' => 'value');

        return array('series' => $series, 'grid' => $grid, 'xAxis' => $xaxis, 'yAxis' => $yaxis, 'tooltip' => array('trigger' => 'axis'));
    }

    /**
     * 簇状条形图、堆积条形图。
     * Gen cluBar.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  string $stack
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genCluBar(array $fields, array $settings, string $defaultSql, array $filters, string $stack = '', array $langs = array(), $driver = 'mysql'): array
    {
        list($group, $metrics, $aggs, $xLabels, $yStats) = $this->getMultiData($settings, $defaultSql, $filters, $driver);

        $yDatas = array();
        foreach($yStats as $yStat)
        {
            $data = array();
            foreach($xLabels as $xLabel) $data[] = isset($yStat[$xLabel]) ? $yStat[$xLabel] : 0;
            $yDatas[] = $data;
        }

        $optionList = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);
        foreach($xLabels as $index => $xLabel) $xLabels[$index] = isset($optionList[$xLabel]) ? $optionList[$xLabel] : $xLabel;


        $position = 'top';
        if($settings['type'] == 'stackedBar' or $settings['type'] == 'stackedBarY')  $position = 'inside';
        if($settings['type'] == 'cluBarY') $position = 'right';
        $label = array('show' => true, 'position' => $position, 'formatter' => '{c}');

        $series = array();
        foreach($yDatas as $index => $yData)
        {
            $fieldName  = $this->chartTao->switchFieldName($fields, $langs, $metrics, $index);
            $seriesName = $fieldName . '(' . $this->lang->chart->aggList[$aggs[$index]] . ')';
            $series[]   = array('name' => $seriesName, 'data' => $yData, 'type' => 'bar', 'stack' => $stack, 'label' => $label);
        }

        $grid  = array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true);
        $xaxis = array('type' => 'category', 'data' => $xLabels, 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true));
        $yaxis = array('type' => 'value');

        /* 簇状柱形图和簇状条形图其实只是x轴和y轴换了换，所以交换一下簇状条形图 xAxis和yAxis即可，这样方法就可以复用了。*/
        $isY   = in_array($settings['type'], array('cluBarY', 'stackedBarY'));
        if($isY) list($xaxis, $yaxis) = array($yaxis, $xaxis);
        $options = array('series' => $series, 'grid' => $grid, 'xAxis' => $xaxis, 'yAxis' => $yaxis, 'tooltip' => array('trigger' => 'axis'));

        if(is_array($xLabels) and count($xLabels) > 10)
        {
            $sliderConfig = $this->config->chart->dataZoom->slider;
            $axisIndex    = $isY ? 'yAxisIndex' : 'xAxisIndex';

            $dataZoomCommon = $this->config->chart->dataZoom->common;
            $dataZoomCommon->inside->$axisIndex = array(0);
            $dataZoomCommon->slider->$axisIndex = array(0);
            $dataZoomCommon->slider->width  = $sliderConfig->{$isY ? 'width' : 'height'};
            $dataZoomCommon->slider->height = $sliderConfig->{$isY ? 'height' : 'width'};
            $dataZoomCommon->slider->{$isY ? 'top' : 'bottom'} = $sliderConfig->{$isY ? 'top' : 'bottom'};
            $dataZoomCommon->slider->{$isY ? 'right' : 'left'} = $sliderConfig->{$isY ? 'right' : 'left'};

            $dataZoom = array($dataZoomCommon->inside, $dataZoomCommon->slider);

            $options['dataZoom'] = $dataZoom;
        }

        return $options;
    }

    /**
     * 获取图表所需的数据：X轴、Y轴、计数方式
     * Get multi data.
     *
     * @param  array   $settings
     * @param  string  $defaultSql
     * @param  array   $filters
     * @param  bool    $sort
     * @access public
     * @return array
     */
    public function getMultiData(array $settings, string $defaultSql, array $filters, string $driver, bool $sort = false): array
    {
        $group = isset($settings['xaxis'][0]['field']) ? $settings['xaxis'][0]['field'] : '';
        $date  = isset($settings['xaxis'][0]['group']) ? zget($this->config->chart->dateConvert, $settings['xaxis'][0]['group']) : '';

        $metrics = array();
        $aggs    = array();
        foreach($settings['yaxis'] as $yaxis)
        {
            $metrics[] = $yaxis['field'];
            $aggs[]    = $yaxis['valOrAgg'];
        }
        $yCount = count($metrics);

        $xLabels = array();
        $yStats  = array();
        for($i = 0; $i < $yCount; $i ++)
        {
            $metric = $metrics[$i];
            $agg    = $aggs[$i];

            $rows = $this->chartTao->getRows($defaultSql, $filters, $date, $group, $metric, $agg, $driver);
            $stat = $this->chartTao->processRows($rows, $date, $group, $metric);

            if($sort) arsort($stat);
            $yStats[] = $stat;

            $xLabels = array_merge($xLabels, array_keys($stat));
            $xLabels = array_unique($xLabels);
        }

        return array($group, $metrics, $aggs, $xLabels, $yStats);
    }

    /**
     * 使用设置的内容，在sql结果中计算百分比
     * Get water polo option.
     *
     * @param  array   $settings
     * @param  string  $sql
     * @param  array   $filters
     * @access public
     * @return array
     */
    public function genWaterpolo(array $settings, string $sql, array $filters, string $driver = 'mysql')
    {
        $operate = "{$settings['calc']}({$settings['goal']})";
        $sql = "select $operate count from ($sql) tt ";

        $moleculeSQL    = $sql;
        $denominatorSQL = $sql;

        $moleculeWheres    = array();
        $denominatorWheres = array();
        foreach($settings['conditions'] as $condition)
        {
            $condition = (array)$condition;
            $where = "{$condition['field']} {$this->config->chart->conditionList[$condition['condition']]} '{$condition['value']}'";
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

        $molecule    = $this->bi->queryWithDriver($driver, $moleculeSQL, false);
        $denominator = $this->bi->queryWithDriver($driver, $denominatorSQL, false);

        $percent = $denominator->count ? round($molecule->count / $denominator->count, 4) : 0;

        $series  = array(array('type' => 'liquidFill', 'data' => array($percent), 'color' => array('#2e7fff'), 'outline' => array('show' => false), 'label' => array('fontSize' => 26)));
        $tooltip = array('show' => true);
        $options = array('series' => $series, 'tooltip' => $tooltip);

        return $options;
    }

    /**
     * 根据用户设置的字段展示对应的下拉菜单。
     * Get field options.
     *
     * @param  string $type user|product|project|execution|dept|option|object|string
     * @param  string $object
     * @param  string $field
     * @param  string $sql
     * @param  string $saveAs
     * @access public
     * @return array
     */
    public function getSysOptions(string $type, string $object = '', string $field = '', string $sql = '', string $saveAs = '', $driver = 'mysql'): array
    {
        if(in_array($type, array('user', 'product', 'project', 'execution', 'dept'))) return $this->bi->getScopeOptions($type);
        if(!$field) return array();

        $options = array();
        switch($type)
        {
            case 'option':
                $options = $this->bi->getDataviewOptions($object, $field);
                break;
            case 'object':
                $options = $this->bi->getObjectOptions($object, $field);
                break;
            case 'string':
            case 'number':
                if($sql)
                {
                    $keyField   = $field;
                    $valueField = $saveAs ? $saveAs : $field;
                    $options = $this->bi->getOptionsFromSql($sql, $driver, $keyField, $valueField);
                }
                break;
        }

        if($sql and $saveAs and in_array($type, array('user', 'product', 'project', 'execution', 'dept', 'option', 'object')))
        {
            $options = $this->bi->getOptionsFromSql($sql, $driver, $field, $saveAs);
        }

        return array_filter($options);
    }

    /**
     * 判断操作按钮是否可点击。
     * Adjust the action is clickable.
     *
     * @param  object $chart
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $chart, string $action): bool
    {
        $builtinCharts = array();
        $builtinCharts[] = array(1001,  1110);
        $builtinCharts[] = array(10000, 10119);
        $builtinCharts[] = array(10201, 10220);
        $builtinCharts[] = array(20002, 20015);
        $builtinCharts[] = array(30000, 30001);

        $found = false; // 标记ID是否在范围内

        foreach ($builtinCharts as $range) {
            $minId = $range[0];
            $maxId = $range[1];

            if ($chart->id >= $minId && $chart->id <= $maxId) {
                $found = true;
                break;
            }
        }

        return !$found && !$chart->builtin;

    }

    /**
     * 格式化筛选器。
     * Format filter.
     *
     * @param  array $filters
     * @access public
     * @return array
     */
    public function getFilterFormat(array $filters): array
    {
        $filterFormat = array();
        foreach($filters as $filter)
        {
            $field = $filter['field'];
            $type  = $filter['type'];
            if($type != 'condition' && !isset($filter['default'])) continue;

            if($type != 'condition') $default = $filter['default'];
            switch($type)
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

                    $begin = date('Y-m-d 00:00:00', strtotime($begin));
                    $end   = date('Y-m-d 23:59:59', strtotime($end));

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
     * 在sql中将变量解析为空字符串。
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return bool
     */
    public function isChartHaveData(array $options, string $type): bool
    {
        if($type == 'waterpolo') return true;

        $data = array();
        if($type == 'pie')   $data = $options['series'][0]['data'];
        if($type == 'line')  $data = $options['xAxis']['data'];
        if($type == 'radar') $data = $options['radar']['indicator'];
        if($type == 'cluBarY' or $type == 'stackedBarY') $data = $options['yAxis']['data'];
        if($type == 'cluBarX' or $type == 'stackedBar')  $data = $options['xAxis']['data'];

        return count($data) ? true : false;
    }
}
