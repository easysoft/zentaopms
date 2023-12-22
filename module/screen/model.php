<?php
/**
 * The model file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-02-08 17:15:54Z $
 * @link        https://www.zentao.net
 */
class screenModel extends model
{
    /**
     * 全局过滤器。
     * Global filter.
     *
     * @var object
     * @access public
     */
    public $filter;

    /**
     * 初始化函数。
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadBIDAO();

        $this->filter = new stdclass();
        $this->filter->screen  = '';
        $this->filter->year    = '';
        $this->filter->dept    = '';
        $this->filter->account = '';
        $this->filter->charts  = array();
    }

    /**
     * 通过维度id获取大屏列表。
     * Get screen list by dimension id.
     *
     * @param  int    $dimensionID
     * @access public
     * @return array
     */
    public function getList(int $dimensionID): array
    {
        return $this->dao->select('*')->from(TABLE_SCREEN)->where('dimension')->eq($dimensionID)->andWhere('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * 通过id获取大屏信息。
     * Get screen by id.
     *
     * @param  int         $screenID
     * @param  int         $year
     * @param  int         $dept
     * @param  string      $account
     * @access public
     * @return object|bool
     */
    public function getByID(int $screenID, int $year = 0, int $dept = 0, string $account = ''): object|bool
    {
        $screen = $this->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();
        if(!$screen) return false;

        if(empty($screen->scheme)) $screen->scheme = file_get_contents(__DIR__ . '/json/screen.json');
        $screen->chartData = $this->genChartData($screen, $year, $dept, $account);

        return $screen;
    }

    /**
     * 构建大屏图表数据。
     * Generate chartData of screen.
     *
     * @param  object $screen
     * @param  int    $year
     * @param  int    $dept
     * @param  string $account
     * @access public
     * @return object
     */
    public function genChartData(object $screen, int $year, int $dept, string $account): object
    {
        $this->filter = new stdclass();
        $this->filter->screen  = $screen->id;
        $this->filter->year    = $year;
        $this->filter->dept    = $dept;
        $this->filter->account = $account;
        $this->filter->charts  = array();

        if($screen->id == 5) return new stdclass();
        if(!$screen->builtin || in_array($screen->id, $this->config->screen->builtinScreen)) return $this->genNewChartData($screen);

        $config = new stdclass();
        $config->width            = 1300;
        $config->height           = 1080;
        $config->filterShow       = false;
        $config->hueRotate        = 0;
        $config->saturate         = 1;
        $config->contrast         = 1;
        $config->brightness       = 1;
        $config->opacity          = 1;
        $config->rotateZ          = 0;
        $config->rotateX          = 0;
        $config->rotateY          = 0;
        $config->skewX            = 0;
        $config->skewY            = 0;
        $config->blendMode        = 'normal';
        $config->background       = '#001028';
        $config->selectColor      = true;
        $config->chartThemeColor  = 'dark';
        $config->previewScaleType = 'scrollY';

        $componentList = json_decode($screen->scheme);
        if(empty($componentList)) $componentList = array();

        /* 重置容器的高度。 */
        /* Reset height of canvas. */
        foreach($componentList as $component)
        {
            if(!isset($component->attr)) continue;

            $height = $component->attr->y + $component->attr->h;
            if($height > $config->height) $config->height = $height;
        }
        $config->height += 50;

        $chartData = new stdclass();
        $chartData->editCanvasConfig    = $config;
        $chartData->componentList       = $this->buildComponentList($componentList);
        $chartData->requestGlobalConfig = json_decode('{ "requestDataPond": [], "requestOriginUrl": "", "requestInterval": 30, "requestIntervalUnit": "second", "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

        return $chartData;
    }

    /**
     * 为新的大屏构建图表数据。
     * Generate chartData of new screen.
     *
     * @param  object $screen
     * @access public
     * @return object
     */
    public function genNewChartData(object $screen): object
    {
        $scheme = json_decode($screen->scheme);
        foreach($scheme->componentList as $component)
        {
            $list = !empty($component->isGroup) ? $component->groupList : array($component);
            foreach($list as $groupComponent) isset($groupComponent->key) && $groupComponent->key === 'Select' && $this->buildSelect($groupComponent);
        }

        /* 过滤为空的组件，并且为组件生成图表数据。 */
        /* Filter component list is empty and generate chartData of component. */
        $list = array();
        array_map(function($component)use(&$list){
            !empty($component->isGroup) ? array_merge($list, $component->groupList) : array_push($list, $component);
        }, array_filter($scheme->componentList));
        foreach($list as $component) $this->getLatestChart($component);

        return $scheme;
    }

    /**
     * 为组件生成图表数据。
     * Generate chartData of component.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function getLatestChart(object $component): void
    {
        /* 不存在的chartID或者为select的组件不需要构建。 */
        /* If chartID is empty or component is select, it doesn't need to build. */
        if(isset($component->key) && $component->key === 'Select') return;
        $chartID = zget($component->chartConfig, 'sourceID', '');
        if(!$chartID) return;

        $type  = $component->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $this->dao->select('*')->from($table)->where('id')->eq($chartID)->fetch();

        $this->genComponentData($chart, $component, $type);
    }

    /**
     * 构建组件数据。
     * Generate a component of screen.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @param  array  $filters
     * @param  bool   $unit
     * @access public
     * @return void
     */
    public function genComponentData(object $chart, object $component, string $type = 'chart', array $filters = array(), bool $unit = false): void
    {
        if(!$unit) $chart = clone($chart);
        if($type == 'pivot' && $chart)
        {
            $this->loadModel('pivot')->processPivot($chart);
            $chart->settings = json_encode($chart->settings);
        }

        if(empty($filters) && !empty($chart->filters))
        {
            $params = array(json_decode($chart->filters, true));
            if($type == 'pivot') array_unshift($params, $chart->sql);
            $result = call_user_func_array(array($this->loadModel($type), 'getFilterFormat'), $params);

            list($chart->sql, $filters) = isset($result[0]) ? $result : array($chart->sql, $result);
        }

        $this->initComponent($chart, $type, $component);
        $this->completeComponent($chart, $type, $filters, $component);
    }

    /**
     * 补充组件信息。
     * Complete component info.
     *
     * @param  object $chart
     * @param  string $type
     * @param  array  $filters
     * @param  object $component
     * @access public
     * @return void
     */
    public function completeComponent(object $chart, string $type, array $filters, object $component): void
    {
        /* 处理图表为空，图表stage值为draft和图表被删除的情况，都给默认值。 */
        /* Process chart is empty, chart stage is draft and chart is deleted, all give default value. */
        if(empty($chart) || ($chart->stage == 'draft' || $chart->deleted == '1'))
        {
            $this->completeComponentShowInfo($chart, $component, $type);
            return;
        }

        /* 根据图表类型获取图表配置。 */
        /* Get chart option by chart type. */
        $this->getChartOption($chart, $component, $filters);
        $component->chartConfig->dataset  = $component->option->dataset;
        $component->chartConfig->fields   = json_decode($chart->fields);
        $component->chartConfig->filters  = $this->getChartFilters($chart);

        /* 当type类型为chart并且图表不是内置图表的时候，需要填充series数组的长度或者修改雷达图的部分配置。 */
        /* When type is chart or chart is not builtin, need to fill length of series array or modify part of radar chart config. */
        if($type == 'chart' && (!$chart->builtin || in_array($chart->id, $this->config->screen->builtinChart)))
        {
            if(!empty($component->option->series))
            {
                $defaultSeries = $component->option->series;
                if($component->type == 'radar')
                {
                    /* 处理雷达图。 */
                    /* Process radar chart. */
                    $component->option->radar->indicator = $component->option->dataset->radarIndicator;
                    $defaultSeries[0]->data = $component->option->dataset->seriesData;

                    $component->option->legend->data = array_map(function($item){return $item->name;}, $component->option->dataset->seriesData);
                }
                else
                {
                    $component->option->series = array_pad([], count($component->option->dataset->dimensions), $defaultSeries[0]);
                }
            }
        }
    }

    /**
     * 补充组件展示信息。
     * Complete component chart info.
     *
     * @param  object $chart
     * @param  object $component
     * @param  string $type
     * @access public
     * @return void
     */
    private function completeComponentShowInfo(object $chart, object $component, string $type): void
    {
        $component->option = new stdclass();
        if($type == 'chart') $this->completeChartShowInfo($chart, $component);
        if($type == 'pivot') $this->completePivotShowInfo($chart, $component);
    }

    /**
     * 补充图表展示信息。
     * Complete chart show info.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return void
     */
    private function completeChartShowInfo(object $chart, object $component): void
    {
        /* 设置图表的title信息。 */
        /* Set chart title info. */
        $component->option->title = new stdclass();
        $component->option->title->text = sprintf($this->lang->screen->noData, $chart->name);
        $component->option->title->left = 'center';
        $component->option->title->top  = '50%';

        /* 初始化x和y轴。 */
        /* Init x and y axis. */
        $component->option->xAxis = new stdclass();
        $component->option->xAxis->show = false;
        $component->option->yAxis = new stdclass();
        $component->option->yAxis->show = false;
    }

    /**
     * 补充透视表展示信息。
     * Complete pivot show info.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return void
     */
    private function completePivotShowInfo(object $chart, object $component): void
    {
        $component->option->ineffective = 1;
        $component->option->header      = array();
        $component->option->align       = array('center');
        $component->option->headerBGC   = 'transparent';
        $component->option->oddRowBGC   = 'transparent';
        $component->option->evenRowBGC  = 'transparent';
        $component->option->columnWidth = array();
        $component->option->rowspan     = array();
        $component->option->colspan     = array();
        $component->option->rowNum      = 1;
        $component->option->dataset     = array(array(sprintf($this->lang->screen->noData, $chart->name)));
    }

    /**
     * 获取图表配置。
     * Get chart option.
     *
     * @param  object $chart
     * @param  object $component
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getChartOption(object $chart, object $component, array $filters): void
    {
        $type = $component->type ? : 'default';
        switch($type)
        {
            case 'line':
                $this->getLineChartOption($component, $chart, $filters);
                break;
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
            case 'bar':
                $this->getBarChartOption($component, $chart, $filters);
                break;
            case 'piecircle':
                $this->buildPieCircleChart($component, $chart);
                break;
            case 'pie':
                $this->getPieChartOption($component, $chart, $filters);
                break;
            case 'table':
                $this->getTableChartOption($component, $chart, $filters);
                break;
            case 'radar':
                $this->getRadarChartOption($component, $chart, $filters);
                break;
            case 'card':
                $this->buildCardChart($component, $chart);
                break;
            case 'waterpolo':
                $this->buildWaterPolo($component, $chart);
                break;
            default:
                break;
        }
    }

    /**
     * 获取条形图配置。
     * Get bar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getBarChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            /* 获取图表字段配置和语言项。 */
            /* Get chart fields and langs. */
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true);
            $settings = current($settings);

            /* 获取图表的相关配置和原始数据。 */
            /* Get chart related settings and raw data. */
            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);
            $fields       = json_decode($chart->fields);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                array_push($dimensions, $field);

                foreach($dataList as $valueField => $value)
                {
                    $valueField = $xLabelValues[$valueField];
                    if(empty($sourceData[$valueField]))
                    {
                        $sourceData[$valueField] = new stdclass();
                        $sourceData[$valueField]->{$settings['xaxis'][0]['field']} = $valueField;
                    }
                    $sourceData[$valueField]->{$field} = $value;
                }
            }
            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        /* 设置组件默认属性。 */
        /* Set component default attributes. */
        $this->setComponentDefaults($component);
    }

    /**
     * 获取折线图配置。
     * Get line chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getLineChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            /* 获去字段配置和语言项。 */
            /* Get chart fields and langs. */
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true);
            $settings = current($settings);

            /* 获取图表的相关配置和原始数据。 */
            /* Get chart related settings and raw data. */
            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);
            $fields       = json_decode($chart->fields);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                array_push($dimensions, $field);

                foreach($dataList as $valueField => $value)
                {
                    $valueField = $xLabelValues[$valueField];
                    if(empty($sourceData[$valueField]))
                    {
                        $sourceData[$valueField] = new stdclass();
                        $sourceData[$valueField]->{$settings['xaxis'][0]['field']} = $valueField;
                    }
                    $sourceData[$valueField]->{$field} = $value;
                }
            }

            /* 填充空数据。 */
            /* Completing empty values. */
            foreach($sourceData as $lineData)
            {
                foreach($dimensions as $dimension)
                {
                    if(empty($lineData->{$dimension})) $lineData->{$dimension} = 0;
                }
            }

            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        /* 设置组件默认属性。 */
        /* Set component default attributes. */
        $this->setComponentDefaults($component);
    }

    /**
     * 获取饼图配置。
     * Get pie chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getPieChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            /* 获取字段配置。 */
            /* Get chart settings. */
            $settings = json_decode($chart->settings, true);
            $settings = current($settings);

            /* 获取图表的相关配置和原始数据。 */
            /* Get chart related settings and raw data. */
            $options = $this->loadModel('chart')->genPie(json_decode($chart->fields, true), $settings, $chart->sql, $filters);
            $groupField = $settings['group'][0]['field'];
            $metricField = $settings['metric'][0]['field'];

            if($groupField == $metricField) $groupField .= '1';
            $dimensions = array($groupField, $metricField);
            $sourceData = array();
            foreach($options['series'] as $dataList)
            {
                foreach($dataList['data'] as $data)
                {
                    $fieldValue = $data['name'];
                    if(empty($sourceData[$fieldValue]))
                    {
                        $sourceData[$fieldValue] = new stdclass();
                        $sourceData[$fieldValue]->{$groupField} = (string)$fieldValue;
                    }
                    $sourceData[$fieldValue]->{$metricField} = $data['value'];
                }
            }

            if(empty($sourceData)) $dimensions = array();

            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        /* 设置组件默认属性。 */
        /* Set component default attributes. */
        $this->setComponentDefaults($component);
    }

    /**
     * 获取雷达图配置。
     * Get radar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getRadarChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            /* 获取字段配置和语言项。 */
            /* Get chart fields and langs. */
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true);
            $settings = current($settings);

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);

            $fields         = json_decode($chart->fields);
            $radarIndicator = array();
            $seriesData     = array();
            $max            = 0;
            $clientLang     = $this->app->getClientLang();
            $xLabelValues   = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field       = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';

                $seriesData[$index] = new stdclass();
                $seriesData[$index]->name = $field;

                $values = array_map(function($value){return (float)$value;}, $dataList);
                $max = max($values);
                $seriesData[$index]->value = $values;
            }

            /* 如果最后一列不为空，则添加一个指标列。 */
            /* If the last column is not empty, add an indicator column. */
            if(!empty($dataList))
            {
                foreach(array_keys($dataList) as $valueField)
                {
                    $indicator = new stdclass();
                    $indicator->name   = $xLabelValues[$valueField];
                    $indicator->max    = $max;
                    $radarIndicator[]  = $indicator;
                }
            }
            $component->option->dataset->radarIndicator = $radarIndicator;
            $component->option->dataset->seriesData     = $seriesData;
        }

        /* 设置组件默认属性。 */
        /* Set component default attributes. */
        $this->setComponentDefaults($component);
    }


    /**
     * 获取表格图表配置。
     * Get table chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getTableChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            /* 获取表格字段配置以及单元格合并配置。 */
            /* Get table fields and merge cells config. */
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true) ? : array();
            $fields   = json_decode($chart->fields,   true);
            list($options, $config) = $this->loadModel('pivot')->genSheet($fields, $settings, $chart->sql, $filters, $langs);

            /* 处理合计行。 */
            /* Process total row. */
            $colspan = array();
            if($options->columnTotal && $options->columnTotal == 'sum' && !empty($options->array))
            {
                $optionsData = $options->array;
                $count       = count($optionsData);
                foreach($optionsData as $index => $data)
                {
                    if($index == ($count - 1))
                    {
                        $newData = array('total' => $this->lang->pivot->step2->total);
                        foreach($options->groups as $field) unset($data[$field]);
                        $newData += $data;
                        $optionsData[$index] = $newData;
                    }
                }
                $options->array = $optionsData;
                $colspan[$count - 1][0] = count($options->groups);
            }

            $dataset = array_map(function($data){return array_values($data);}, $options->array);

            /* 处理单元格合并数据。 */
            /* Process merge cells data. */
            foreach($config as $i => $data)
            {
                foreach($data as $j => $rowspan)
                {
                    for($k = 1; $k < $rowspan; $k ++) unset($dataset[$i + $k][$j]);
                }
            }

            $this->setComponentTableInfo($component, $options->cols, $dataset, $config, $colspan);
        }

        /* 设置组件默认属性。 */
        /* Set component default attributes. */
        $this->setComponentDefaults($component);
    }

    /**
     * 设置组件表格信息。
     * Set component table info.
     *
     * @param  object  $component
     * @param  array   $cols
     * @param  array   $dataset
     * @param  array   $config
     * @param  array   $colspan
     * @access private
     * @return void
     */
    public function setComponentTableInfo(object $component, array $cols, array $dataset, array $config, array $colspan): void
    {
        $align = array_map(function(){return 'center';}, current($cols));

        if(!isset($component->chartConfig->tableInfo)) $component->chartConfig->tableInfo = new stdclass();
        $component->option->header      = $component->chartConfig->tableInfo->header      = $cols;
        $component->option->align       = $component->chartConfig->tableInfo->align       = $align;
        $component->option->columnWidth = $component->chartConfig->tableInfo->columnWidth = array();
        $component->option->rowspan     = $component->chartConfig->tableInfo->rowspan     = $config;
        $component->option->colspan     = $component->chartConfig->tableInfo->colspan     = $colspan;
        $component->option->dataset     = $dataset;
    }

    /**
     * 获取图表的过滤条件。
     * Get chart filters.
     *
     * @param object $chart
     * @access public
     * @return array
     */
    public function getChartFilters(object $chart): array
    {
        $filters = json_decode($chart->filters, true);
        $fields  = json_decode($chart->fields,  true);

        return !empty($filters) ? array_map(function($filter)use($fields, $chart){
            $isQuery = (isset($filter['from']) && $filter['from'] == 'query');
            if($isQuery) $this->setIsQueryScreenFilters($filter);
            if(!$isQuery && ($filter['type'] == 'date' || $filter['type'] == 'datetime')) $this->setDefaultByDate($filter);
            if(!$isQuery && $filter['type'] == 'select')
            {
                $field = zget($fields, $filter['field']);
                $options = $this->getSysOptions($field['type'], $field['object'], $field['field'], $chart->sql);
                $filter['options'] = array_map(function($item, $index){return array('label' => $item, 'value' => $index);}, $options, array_keys($options));
            }

            return $filter;
        }, $filters) : array();
    }

    /**
     * 设置组件的查询过滤条件。
     * Set component query filters.
     *
     * @param  array  $filter
     * @access public
     * @return void
     */
    public function setIsQueryScreenFilters(array &$filter): void
    {
        if($filter['type'] == 'date' || $filter['type'] == 'datetime')
        {
            if(isset($filter['default']))
            {
                $default = $this->loadModel('pivot')->processDateVar($filter['default']);
                $filter['default'] = empty($default) ? null : strtotime($default) * 1000;
            }
        }

        if($filter['type'] == 'select')
        {
            $options = $this->getSysOptions($filter['typeOption']);
            $filter['options'] = array_map(function($item, $index){return array('label' => $item, 'value' => $index);}, $options, array_keys($options));
        }
    }

    /**
     * 根据时间设置默认值。
     * Set default by date.
     *
     * @param  array  $filter
     * @access public
     * @return void
     */
    public function setDefaultByDate(array &$filter): void
    {
        $filter['default'] = $filter['default'] ?? null;

        if(isset($filter['default']))
        {
            extract($filter['default']);
            if(empty($begin)  || empty($end))  $filter['default'] = empty($begin) ? strtotime($end) * 1000 : strtotime($begin) * 1000;
            if(!empty($begin) && !empty($end)) $filter['default'] = array(strtotime($begin) * 1000, strtotime($end) * 1000);
            if(empty($begin)  && empty($end))  $filter['default'] = null;
        }
    }

    /**
     * 根据语言处理横坐标的值。
     * Process xLabel value with lang.
     *
     * @param  array  $xLabel
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function processXLabel(array $xLabels, string $type, string $object, string $field): array
    {
        $xLabelValues = array();
        $options      = $this->getSysOptions($type, $object, $field);
        foreach($xLabels as $label) $xLabelValues[$label] = isset($options[$label]) ? $options[$label] : $label;

        return $xLabelValues;
    }

    /**
     * 获取系统配置。
     * Get system options.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getSysOptions(string $type, string $object = '', string $field = '', string $sql = ''): array
    {
        $options = array();
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
            case 'project.status':
                $this->app->loadLang('project');
                $options = $this->lang->project->statusList;
                break;
            case 'option':
                if($field)
                {
                    /* 引入dataview下的相关文件。 */
                    /* Include related files in dataview. */
                    $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "{$object}.php";
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
                    /* 查询相关字段的值。 */
                    /* Get field value. */
                    $table = zget($this->config->objectTables, $object, '');
                    if($table) $options = $this->dao->select("id, {$field}")->from($table)->fetchPairs();
                }
                break;
            default:
                if($field && $sql)
                {
                    /* 通过sql查询展示字段。 */
                    /* Get field by sql. */
                    $cols = $this->dao->query("select tt.`$field` from ($sql) tt group by tt.`$field` order by tt.`$field` desc")->fetchAll();
                    foreach($cols as $col) $options[$col->{$field}] = $col->{$field};
                }
                break;

        }

        return array_filter($options);
    }

    /**
     * 构建组件列表。
     * Build component list.
     *
     * @param  array  $componentList
     * @access public
     * @return array
     */
    public function buildComponentList(array|object $componentList): array
    {
        /* 清除空数据并且重构每个组件。 */
        /* Clear empty data and rebuild each component. */
        return array_map(function($component){$this->buildComponent($component);return $component;}, array_filter($componentList));
    }

    /**
     * 构建组件。
     * Build component.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildComponent(object $component): void
    {
        /* 如果是内置图表，构建图表。 */
        /* If chart is builtin, build it. */
        if(isset($component->sourceID) && $component->sourceID)
        {
            $this->buildChart($component);
            return;
        }

        /* 如果是select图表，构建select相关图表。 */
        /* If chart is select, build select chart. */
        if(isset($component->key) && $component->key === 'Select')
        {
            $this->buildSelect($component);
            return;
        }

        /* 如果当前组件不是组件集合，设置默认值。 */
        /* If current component is not group, set default value. */
        if(empty($component->isGroup))
        {
            $this->setComponentDefaults($component);
            return;
        }

        $component->groupList = $this->buildComponentList($component->groupList);
        $this->buildGroup($component);
    }

    /**
     * 构建图表组的默认属性。
     * Build group default attributes.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildGroup(object $component): void
    {
        $this->setComponentDefaults($component);
    }

    /**
     * 设置组件的默认值。
     * Set component defaults.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function setComponentDefaults(object $component): void
    {
        if(!isset($component->styles))  $component->styles  = $this->config->screen->chart->default->config->styles;
        if(!isset($component->status))  $component->status  = $this->config->screen->chart->default->config->status;
        if(!isset($component->request)) $component->request = $this->config->screen->chart->default->config->request;
    }

    /**
     * 构建选择框。
     * Build select.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildSelect(object $component): void
    {
        switch($component->type)
        {
            case 'year':
                $component->option->value = $this->filter->year;

                /* 只查询从2009年开始的数据。 */
                /* Only query data from 2009. */
                $begin = $this->dao->select('YEAR(MIN(date)) year')->from(TABLE_ACTION)->where('date')->notZeroDate()->fetch('year');
                if($begin < 2009) $begin = 2009;

                /* 构建年份数据。 */
                /* Build year data. */
                $options = array();
                for($year = date('Y'); $year >= $begin; $year--) $options[] = array('label' => $year, 'value' => $year);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen. "&year=' + value + '&dept=" . $this->filter->dept . "&account=" . $this->filter->account . "')";
                break;
            case 'dept':
                $component->option->value = (string)$this->filter->dept;

                /* 构建部门数据。 */
                /* Build dept data. */
                $options = array(array('label' => $this->lang->screen->allDepts, 'value' => '0'));
                $depts = $this->dao->select('id,name')->from(TABLE_DEPT)->where('grade')->eq(1)->fetchAll();
                array_map(function($dept)use(&$options){array_push($options, array('label' => $dept->name, 'value' => $dept->id));}, $depts);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=' + value + '&account=')";
                break;
            case 'account':
                $component->option->value = $this->filter->account;

                /* 构建用户数据。 */
                /* Build user data. */
                $options = array(array('label' => $this->lang->screen->allUsers, 'value' => ''));
                $depts   = array();
                if($this->filter->dept) $depts = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like(',' . $this->filter->dept . ',%')->fetchPairs();
                $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('deleted')->eq(0)->beginIF($this->filter->dept)->andWhere('dept')->in($depts)->fi()->fetchAll();
                array_map(function($user)use(&$options){array_push($options, array('label' => $user->realname, 'value' => $user->account));}, $users);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=" . $this->filter->dept . "&account=' + value)";
                break;
        }

        if(isset($url)) $component->option->onChange = "window.location.href = {$url}";

        /* 设置全局图表过滤条件。 */
        /* Set global chart filter. */
        foreach($component->filterCharts as $chart)
        {
            if(!isset($this->filter->charts[$chart->chart])) $this->filter->charts[$chart->chart] = array();
            $this->filter->charts[$chart->chart][$component->type] = $chart->field;
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 构建图表。
     * Build chart.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildChart(object $component): void
    {
        $chart = $this->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();
        switch($chart->type)
        {
            case 'card':
                $this->buildCardChart($component, $chart);
                break;
            case 'line':
                $this->buildLineChart($component, $chart);
                break;
            case 'bar':
                $this->buildBarChart($component, $chart);
                break;
            case 'piecircle':
                $this->buildPieCircleChart($component, $chart);
                break;
            case 'pie':
                /* 通过判断是否是内置图表调用不同的方法。 */
                /* Call different methods by judging whether it is a builtin chart. */
                $chart->builtin == '0' ? $this->getPieChartOption($component, $chart) : $this->buildPieChart($component, $chart);
                break;
            case 'radar':
                $this->buildRadarChart($component, $chart);
                break;
            case 'funnel':
                $this->buildFunnelChart($component, $chart);
                break;
            case 'table':
                $this->buildTableChart($component, $chart);
                break;
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
                $this->getBarChartOption($component, $chart);
                break;
        }
    }

    /**
     * 设置sql过滤条件。
     * Set SQL filter.
     *
     * @param  object $chart
     * @access public
     * @return string
     */
    public function setFilterSQL(object $chart): string
    {
        $sql = $chart->sql;
        if(isset($this->filter->charts[$chart->id]))
        {
            $conditions = array();
            foreach($this->filter->charts[$chart->id] as $key => $field)
            {
                switch($key)
                {
                    case 'year':
                        $conditions[] = $field . " = '" . $this->filter->{$key} . "'";
                        break;
                    case 'dept':
                        if($this->filter->dept && !$this->filter->account)
                        {
                            /* 根据部门查询用户。 */
                            /* Query users by dept. */
                            $accountField = $this->filter->charts[$chart->id]['account'];
                            $users = $this->dao->select('account')->from(TABLE_USER)->alias('t1')
                                ->leftJoin(TABLE_DEPT)->alias('t2')
                                ->on('t1.dept = t2.id')
                                ->where('t2.path')->like(',' . $this->filter->dept . ',%')
                                ->fetchPairs('account');
                            $accounts = array_map(function($account){return "'" . $account . "'";}, $users);

                            $conditions[] = $accountField . ' IN (' . implode(',', $accounts) . ')';
                        }
                        break;
                    case 'account':
                        if($this->filter->account) $conditions[] = $field . " = '" . $this->filter->{$key} . "'";
                        break;
                }
            }

            if($conditions) $sql = 'SELECT * FROM (' . str_replace(';', '', $chart->sql) . ') AS t1 WHERE ' . implode(' AND ', $conditions);
        }

        /* 兼容新版本开启了严格模式的数据库，处理可能会报错的sql。 */
        /* Compatible with databases that have strict mode enabled in new versions, and process sql that may cause errors. */
        return str_replace('0000-00-00', '1970-01-01', $sql);
    }

    /**
     * 构建卡片图表。
     * Build card chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildCardChart(object $component, object $chart): void
    {
        $component->option->dataset = '?';
        if($chart->settings)
        {
            $value = 0;
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                $value = '?';
                if($settings && isset($settings->value))
                {
                    $field   = $settings->value->field;
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();

                    if($settings->value->type === 'value') $value = !count($results) ? 0 : current($results)->{$field};
                    if($settings->value->agg  === 'count') $value = count($results);
                    if($settings->value->agg  === 'sum')
                    {
                        $value = 0;
                        foreach($results as $result) $value += (float)$result->{$field};
                        $value = round($value);
                    }
                }
            }
            $component->option->dataset = (string)$value;
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 构建折线图。
     * Build line chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildLineChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('line', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $sourceData = array();
                    $results    = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;
                        $row   = array($key => $result->{$field});

                        foreach($settings->yaxis as $yaxis)
                        {
                            $field = $yaxis->field;
                            $row[$yaxis->name] = $result->{$field};
                        }
                        $sourceData[] = $row;
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = $sourceData;
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建表格图。
     * Build table chart
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildTableChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('table', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->column))
                {
                    $header = $dataset = array();
                    foreach($settings->column as $column) $header[$column->field] = $column->name;

                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result) $dataset[] = array_map(function($field)use($result){return $result->{$field};}, array_keys($header));

                    $component->option->header  = array_values($header);
                    $component->option->dataset = $dataset;
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建条形图。
     * Build bar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildBarChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('bar', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    $sourceData = array();

                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;

                        if($settings->yaxis[0]->agg == 'sum')
                        {
                            if(!isset($sourceData[$result->{$field}])) $sourceData[$result->{$field}] = array($key => $result->{$field});

                            foreach($settings->yaxis as $yaxis)
                            {
                                if(!isset($sourceData[$result->{$field}][$yaxis->name])) $sourceData[$result->{$field}][$yaxis->name] = 0;
                                $sourceData[$result->{$field}][$yaxis->name] += $result->{$yaxis->field};
                            }
                        }
                        else
                        {
                            $row = array($key => $result->{$field});
                            foreach($settings->yaxis as $yaxis) $row[$yaxis->name] = $result->{$yaxis->field};
                            $sourceData[] = $row;
                        }
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = array_values($sourceData);
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建饼图。
     * Build pie chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildPieChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('pie', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $sourceData = array();
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->metric))
                {
                    $dimensions = array($settings->group[0]->name, $settings->metric[0]->field);

                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    $group = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->{$group}])) $groupCount[$result->{$group}] = 0;
                            $groupCount[$result->{$group}]++;
                        }
                    }
                    arsort($groupCount);

                    foreach($groupCount as $groupValue => $groupCount) $sourceData[] = array($settings->group[0]->name => $groupValue, $settings->metric[0]->field => $groupCount);
                }
                if(empty($sourceData)) $dimensions = array();

                $component->option->dataset->dimensions = $dimensions;
                $component->option->dataset->source     = $sourceData;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建环形饼图。
     * Build piecircle chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildPieCircleChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('piecircle', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $sourceData = array();
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->metric))
                {
                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    $group   = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->{$group}])) $groupCount[$result->{$group}] = 0;
                            $groupCount[$result->{$group}]++;
                        }
                    }

                    foreach($groupCount as $groupValue => $groupCount) $sourceData[$groupValue] = $groupCount;
                }
                $doneData = round((array_sum($sourceData) != 0 && !empty($sourceData['done'])) ? $sourceData['done'] / array_sum($sourceData) : 0, 4);
                $component->option->dataset = $doneData;
                $component->option->series[0]->data[0]->value  = array($doneData);
                $component->option->series[0]->data[1]->value  = array(1 - $doneData);
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建水球图。
     * Build water polo chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildWaterPolo(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('waterpolo', $component);  
            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings   = json_decode($chart->settings);
                $sourceData = 0;
                if($settings && isset($settings->metric))
                {
                    /* 通过sql查询数据，并且处理数据。 */
                    /* Query data by sql and process data. */
                    $result     = $this->dao->query($this->setFilterSQL($chart))->fetch();
                    $group      = $settings->group[0]->field;
                    $sourceData = zget($result, $group, 0);
                }
                $component->option->dataset = $sourceData;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建雷达图。
     * Build radar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildRadarChart(object $component, object $chart): void
    {
        /* 如果没有设置图表配置，设置默认值。 */
        /* Set default value if chart settings is empty. */
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('radar', $component);
            $this->setComponentDefaults($component);
        }
        else
        {
            $indicator = $seriesData = array();
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                /* 通过sql查询数据，并且处理数据。 */
                /* Query data by sql and process data. */
                if($settings && isset($settings->metric)) $value = $this->screenTao->processRadarData($this->setFilterSQL($chart), $settings, $indicator, $seriesData);

                $component->option->dataset->radarIndicator   = $indicator;
                $component->option->radar->indicator          = $indicator;
                $component->option->dataset->seriesData       = $seriesData;
                $component->option->series[0]->data[0]->value = $value;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * 构建漏斗图。
     * Build funnel chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildFunnelChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $this->screenTao->setChartDefault('funnel', $component);
            $this->setComponentDefaults($component);
        }
    }

    /**
     * 获取燃尽图数据。
     * Get burn data.
     *
     * @access public
     * @return array
     */
    public function getBurnData(): array
    {
        $type = 'withdelay';

        /* 获取所有正在进行的执行和阶段。 */
        /* Get all sprint and stage which are doing. */
        $executions = $this->loadModel('execution')->getList(0, 'sprint', 'doing') + $this->execution->getList(0, 'stage', 'doing');

        $executionData = array();

        foreach(array_keys($executions) as $executionID)
        {
            $execution = $this->execution->getByID($executionID);

            $project = $this->loadModel('project')->getByID($execution->project);
            if(!$project) continue;

            $execution->name = $project->name . '--' . $execution->name;

            if(((strpos('closed,suspended', $execution->status) === false && helper::today() > $execution->end)
                || ($execution->status == 'closed'    && substr($execution->closedDate, 0, 10) > $execution->end)
                || ($execution->status == 'suspended' && $execution->suspendedDate > $execution->end))
                && strpos($type, 'delay') === false)
                $type .= ',withdelay';

            /* 处理执行的截止日期。 */
            /* Process execution deadline. */
            $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
            $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
            $endDate  = (strpos($type, 'withdelay') !== false && $deadline > $execution->end) ? $deadline : $execution->end;
            list($dateList) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $deadline);

            /* 处理执行的延迟日期。 */
            /* Process execution delay date. */
            $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
            $chartData    = $this->execution->buildBurnData($executionID, $dateList, 'left', $executionEnd);
            $chartData['baseLine']  = json_encode($chartData['baseLine']);
            $chartData['burnLine']  = json_encode($chartData['burnLine']);
            if(isset($chartData['delayLine'])) $chartData['delayLine'] = json_encode($chartData['delayLine']);
            $execution->chartData = $chartData;

            $executionData[$executionID] = $execution;
        }
        return $executionData;
    }

    /**
     * 初始化图表。
     * Init component.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @access public
     * @return void
     */
    public function initComponent(object $chart, string $type, object $component): void
    {
        if(!$component)
        {
            $component = new stdclass();
            return;
        }
        if(!$chart) return;

        $settings = is_string($chart->settings) ? json_decode($chart->settings) : $chart->settings;

        /* 设置组件部分属性的默认值。 */
        /* Set default value of component. */
        if(!isset($component->id))       $component->id       = $chart->id;
        if(!isset($component->sourceID)) $component->sourceID = $chart->id;
        if(!isset($component->title))    $component->title    = $chart->name;

        /* 设置图表类型。 */
        /* Set chart type. */
        if($type == 'chart') $chartType = ($chart->builtin && !in_array($chart->id, $this->config->screen->builtinChart)) ? $chart->type : current($settings)->type;
        if($type == 'pivot') $chartType = 'table';
        $component->type = $chartType;

        $typeChanged = false;

        /* 判断图表类型是否改变。 */
        /* Judge whether chart type is changed. */
        if(isset($component->chartConfig))
        {
            $componentType = '';
            foreach($this->config->screen->chartConfig as $type => $chartConfig)
            {
                $chartConfig = json_decode($chartConfig);
                if($chartConfig->key == $component->chartConfig->key) $componentType = $type;
            }
            $typeChanged = $chartType != $componentType;
        }

        /* 如果没有设置图表配置，使用系统的图表配置默认值。 */
        /* Use system default value if chart settings is empty. */
        if(!isset($component->chartConfig) || $typeChanged)
        {
            $chartConfig = json_decode(zget($this->config->screen->chartConfig, $chartType));
            if(empty($chartConfig)) return;

            $component->chartConfig = $chartConfig;
        }

        /* 如果组件没有配置，则使用系统的组件配置默认值。 */
        /* Use system default value if component settings is empty. */
        if(!isset($component->option) || $typeChanged)
        {
            $component->option = json_decode(zget($this->config->screen->chartOption, $component->type));
            $component->option->dataset = new stdclass();
        }

        if(!isset($component->option->dataset)) $component->option->dataset = new stdclass();
        $component->chartConfig->title    = $chart->name;
        $component->chartConfig->sourceID = $component->sourceID;
    }
}
