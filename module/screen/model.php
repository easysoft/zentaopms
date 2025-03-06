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
        $this->loadModel('bi');

        $this->filter = new stdclass();
        $this->filter->screen  = '';
        $this->filter->year    = '';
        $this->filter->month   = '';
        $this->filter->dept    = '';
        $this->filter->account = '';
        $this->filter->charts  = array();
    }

    /**
     * 判断是否有权限访问。
     * Check screen access.
     *
     * @param  int    $dimensionID
     * @access public
     * @return array
     */
    public function checkAccess($screenID)
    {
        $viewableObjects = $this->bi->getViewableObject('screen');
        if(!in_array($screenID, $viewableObjects))
        {
            return $this->app->control->sendError($this->lang->screen->accessDenied, helper::createLink('screen', 'browse'));
        }
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
        return $this->dao->select('*')->from(TABLE_SCREEN)
            ->where('dimension')->eq($dimensionID)
            ->beginIF($this->config->edition == 'open')->andWhere('id')->ne($this->config->screen->phpScreen['usageReport'])->fi()
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id', false);
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
    public function getByID(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = '', $withChartData = true): object|bool
    {
        $screen = $this->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();
        if(!$screen) return false;

        if(empty($screen->scheme)) $screen->scheme = file_get_contents(__DIR__ . '/json/screen.json');

        if($withChartData) $screen->chartData = $this->genChartData($screen, $year, $month, $dept, $account);

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
    public function genChartData(object $screen, int $year, int $month, int $dept, string $account): object
    {
        $this->filter = new stdclass();
        $this->filter->screen  = $screen->id;
        $this->filter->year    = $year;
        $this->filter->month   = $month;
        $this->filter->dept    = $dept;
        $this->filter->account = $account;
        $this->filter->charts  = array();

        $scheme = json_decode($screen->scheme);

        if(!$screen->builtin or in_array($screen->id, $this->config->screen->builtinScreen))
        {
            foreach($scheme->componentList as $component)
            {
                if(!empty($component->isGroup))
                {
                    foreach($component->groupList as $key => $groupComponent)
                    {
                        if(isset($groupComponent->key) and $groupComponent->key === 'Select') $groupComponent = $this->buildSelect($groupComponent);
                        $this->setComponentDefaults($groupComponent);
                    }
                }
                else
                {
                    if(isset($component->key) and $component->key === 'Select') $component = $this->buildSelect($component);
                    $this->setComponentDefaults($component);
                }
            }

            return $scheme;
        }

        $scheme->componentList = $this->buildComponentList($scheme->componentList);

        return $scheme;
    }

    /**
     * Get the latest chart.
     *
     * @param  object  $component
     * @access public
     * @return void
     */
    public function getLatestChart($component)
    {
        if(isset($component->key) and $component->key === 'Select') return $component;
        $chartID = zget($component->chartConfig, 'sourceID', '');
        if(!$chartID) return $component;

        $type  = $component->chartConfig->package;
        $type  = $this->getChartType($type);
        $table = $this->config->objectTables[$type];

        if($type == 'metric')
        {
            $chart = $this->loadModel('metric')->getByID($chartID);
        }
        else
        {
            $chart = $this->dao->select('*')->from($table)->where('id')->eq($chartID)->fetch();
        }

        if($type == 'metric') return $this->genMetricComponent($chart);
        return $this->genComponentData($chart, $type, $component);
    }

    /**
     * mergeChartAndPivotFilters
     *
     * @param  int    $type
     * @param  int    $chartOrPivot
     * @param  int    $sourceID
     * @param  int    $filters
     * @access public
     * @return void
     */
    public function mergeChartAndPivotFilters($type, $chartOrPivot, $sourceID, $filters)
    {
        $filterFormat = array();
        $chartOrPivotFilters = json_decode($chartOrPivot->filters, true);
        $mergeFilters = array();

        foreach($chartOrPivotFilters as $index => $chartOrPivotFilter)
        {
            if(!isset($filters[$index]['default'])) continue;

            $filterDefault = $filters[$index]['default'];
            if($filterDefault === null) continue;

            $filterType = $chartOrPivotFilter['type'];
            $filterFrom = zget($chartOrPivotFilter, 'from', '');
            if($filterType == 'date' or $filterType == 'datetime')
            {
                if($filterFrom == 'query')
                {
                    if(is_numeric($filterDefault)) $filterDefault = date('Y-m-d H:i:s', $filterDefault / 1000);
                }
                else
                {
                    if(is_array($filterDefault))
                    {
                        $begin = $filterDefault[0];
                        $end   = $filterDefault[1];

                        $begin = date('Y-m-d H:i:s', $begin / 1000);
                        $end = date('Y-m-d H:i:s', $end / 1000);

                        $filterDefault = array('begin' => $begin, 'end' => $end);
                    }
                    else
                    {
                        $filterDefault = array('begin' => '', 'end' => '');
                    }
                }

            }
            $chartOrPivotFilter['default'] = $filterDefault;
            $mergeFilters[] = $chartOrPivotFilter;
        }

        if($type == 'pivot')
        {
            list($sql, $filterFormat) = $this->loadModel($type)->getFilterFormat($chartOrPivot->sql, $mergeFilters);
            $chartOrPivot->sql = $sql;
        }
        else
        {
            $filterFormat = $this->loadModel($type)->getFilterFormat($mergeFilters);
        }

        return array($chartOrPivot, $filterFormat);
    }

    /**
     * Generate a component of screen.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @access public
     * @return object
     */
    public function genComponentData($chart, $type = 'chart', $component = null, $filters = array())
    {
        if(empty($chart) || ($chart->stage == 'draft' || $chart->deleted == '1'))
        {
            return $this->genNotFoundOrDraftComponentOption($component, $chart, $type);
        }

        $component = $this->unsetComponentDraftMarker($component);

        $chart = clone($chart);
        if($type == 'pivot' and $chart)
        {
            $this->loadModel('pivot')->processNameDesc($chart);
        }

        if(empty($filters) and !empty($chart->filters))
        {
            if($type == 'pivot')
            {
                list($sql, $filters) = $this->loadModel($type)->getFilterFormat($chart->sql, json_decode($chart->filters, true));
                $chart->sql = $sql;
            }
            else
            {
                $filters = $this->loadModel($type)->getFilterFormat(json_decode($chart->filters, true));
            }
        }

        list($component, $typeChanged) = $this->initComponent($chart, $type, $component);

        $component = $this->getChartOption($chart, $component, $filters);
        if($type == 'chart') $component = $this->getAxisRotateOption($chart, $component);

        $latestFilters = $this->getChartFilters($chart);
        $component = $this->updateComponentFilters($component, $latestFilters);

        if($type == 'chart' && (!$chart->builtin or in_array($chart->id, $this->config->screen->builtinChart)))
        {
            if(!empty($component->option->series))
            {

                $defaultSeries = $component->option->series;
                if($component->type == 'radar')
                {
                    $component->option->radar->indicator = $component->option->dataset->radarIndicator;
                    $defaultSeries[0]->data = $component->option->dataset->seriesData;

                    $legends = array();
                    foreach($component->option->dataset->seriesData as $seriesData) $legends[] = $seriesData->name;
                    $component->option->legend->data = $legends;
                }
                elseif($component->type != 'waterpolo')
                {
                    $series = array();
                    for($i = 1; $i < count($component->option->dataset->dimensions); $i ++) $series[] = $defaultSeries[0];
                    $component->option->series = $series;
                }
            }
        }

        return $component;
    }

    /**
     * Update piovt or chart component chartConfig filters.
     *
     * @param  object $component
     * @param  array  $latestFilters
     * @access public
     * @return object
     */
    public function updateComponentFilters($component, $latestFilters)
    {
        // 如果传过来的component->chartConfig中有filters，判断filters是否发生了改变，改变则重置filters，其中单个filter的linkedGlobalFilter属性就不存在了
        if(!isset($component->chartConfig->filters))
        {
            $component->chartConfig->filters = $latestFilters;
            $component->chartConfig->noSetupGlobalFilterList = array();
        }
        else
        {
            $oldFilters = $component->chartConfig->filters;
            $filterChanged = $this->isFilterChange($oldFilters, $latestFilters);

            if($filterChanged)
            {
                $component->chartConfig->filters = $latestFilters;
                $component->chartConfig->noSetupGlobalFilterList = array();
            }
            else
            {
                if(!isset($_POST['filters'])) // 如果不是前端进行筛选操作，则说明是第一次打开页面，此时看看后台的默认值有没有发生变更
                {
                    foreach($oldFilters as $index => $filter)
                    {
                        $newFilter = $latestFilters[$index];
                        $oldDefault = isset($filter->default) ? $filter->default : '';
                        $newDefault = isset($newFilter->default) ? $newFilter->default : '';
                        if($oldDefault !== $newDefault) $component->chartConfig->filters[$index]->default = $newDefault;
                    }
                }
                foreach($oldFilters as $index => $filter)
                {
                    $isSelect  = $filter->type == 'select' && $latestFilters[$index]->type == 'select';
                    $oldSaveAs = zget($filter, 'saveAs', '');
                    $newSaveAs = zget($latestFilters[$index], 'saveAs', '');

                    if($oldSaveAs !== $newSaveAs) $component->chartConfig->filters[$index]->default = $newSaveAs;
                    if($isSelect and $oldSaveAs !== $newSaveAs) $component->chartConfig->filters[$index]->saveAs  = $newSaveAs;
                }
            }
        }

        return $component;
    }

    /**
     * 判断图表/透视表的筛选器是否发生了变化。
     * Determine if the filter for the chart/pivot has changed.
     *
     * @param  array  $oldFilters
     * @param  array  $latestFilters
     * @access public
     * @return void
     */
    public function isFilterChange($oldFilters, $latestFilters)
    {
        $filterChanged = false;

        if(empty($latestFilters)) return true;

        if(count($oldFilters) != count($latestFilters)) $filterChanged = true;
        foreach($oldFilters as $index => $oldFilter)
        {
            $newFilter = $latestFilters[$index];

            // 结果筛选器和查询筛选器都有的三个字段
            if($oldFilter->field != $newFilter->field || $oldFilter->name != $newFilter->name || $oldFilter->type != $newFilter->type) $filterChanged = true;

            // 如果一个是查询筛选器一个不是，也判定为更改
            $oldHaveQuery = isset($oldFilter->from) and $oldFilter->from = 'query';
            $newHaveQuery = isset($newFilter->from) and $newFilter->from = 'query';
            if($oldHaveQuery != $newHaveQuery) $filterChanged = true;

            // 对于查询筛选器，可以再做进一步判断，如果下拉选择器的typeOption发生了变化，也应该判定为更改
            if($oldHaveQuery and $newHaveQuery)
            {
                if($oldFilter->type == 'select' && $newFilter->type == 'select' && $oldFilter->typeOption != $newFilter->typeOption) $filterChanged = true;
            }

            // 如果发生了变化，不必再去判断后续
            if($filterChanged) break;
        }

        return $filterChanged;
    }

    /**
     * 生成不存在的图表或者草稿图表的参数。
     * Generate not found or draft chart option.
     *
     * @param  object $component
     * @param  object   $chart
     * @access public
     * @return void
     */
    public function genNotFoundOrDraftComponentOption($component, $chart, $type)
    {
        if(empty($component)) $component = new stdclass();
        $noDataLang = $type == 'chart' ? 'noChartData' : 'noPivotData';

        if(!isset($component->option)) $component->option = new stdclass();
        if(!isset($component->option->title)) $component->option->title = new stdclass();

        $name = zget($chart, 'name', '');
        $component->option->title->notFoundText = sprintf($this->lang->screen->$noDataLang, $name);
        $component->option->isDeleted = true;

        return $component;
    }

    /**
     * 生成已下架或者已删除度量项的参数。
     * Generate delist or deleted metric options.
     *
     * @param  object    $component
     * @access public
     * @return object
     */
    public function genDelistOrDeletedMetricOption($component)
    {
        if(empty($component)) $component = new stdclass();

        if(!isset($component->option)) $component->option = new stdclass();
        if(!isset($component->option->title)) $component->option->title = new stdclass();

        $component->option->title->notFoundText = $this->lang->screen->noMetricData;
        $component->option->isDeleted = true;

        return $component;
    }

    /**
     * 删除component的已删除标记。
     * Unset component option isDeleted.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function unsetComponentDraftMarker($component)
    {
        if(isset($component->option->isDeleted)) unset($component->option->isDeleted);
        if(isset($component->option->title))     unset($component->option->title->notFoundText);
        return $component;
    }

    /**
     * 生成全局筛选器的组件。
     * Generate component of global filters.
     *
     * @param  string $filterType
     * @access public
     * @return object
     */
    public function genFilterComponent($filterType)
    {
        $this->loadModel('metric');
        $type = ucfirst($filterType);

        $component = new stdclass();
        $component->chartConfig = new stdclass();
        $component->chartConfig->id           = $type;
        $component->chartConfig->key          = "{$type}Filter";
        $component->chartConfig->chartKey     = "V{$type}Filter";
        $component->chartConfig->conKey       = "VC{$type}Filter";
        $component->chartConfig->category     = 'Filters';
        $component->chartConfig->categoryName = $this->lang->screen->globalFilter;
        $component->chartConfig->package      = 'Decorates';

        if(in_array($filterType, $this->config->metric->scopeList))
        {
            $objectPairs = $this->metric->getPairsByScope($filterType, true);
            $component->chartConfig->objectList = array_map(function($objectID, $objectTitle)
            {
                $object = new stdclass();
                $object->label = $objectTitle;
                $object->value = (string)$objectID;
                return $object;
            }, array_keys($objectPairs), array_values($objectPairs));
        }

        $firstAction = $this->dao->select('YEAR(date) as year')->from(TABLE_ACTION)->orderBy('id_asc')->limit(1)->fetch();
        $yearRange = range((int)date('Y'), $firstAction->year);
        $component->chartConfig->yearList = array_map(function($year)
        {
            $yearObject = new stdclass();
            $yearObject->label = $year;
            $yearObject->value = $year;
            return $yearObject;
        }, $yearRange);

        return $component;
    }

    /**
     * Generate metric component.
     *
     * @param  object      $metric
     * @param  object|null $component
     * @param  array       $filterParams
     * @access public
     * @return object
     */
    public function genMetricComponent($metric, $component = null, $filterParams = array())
    {
        $this->loadModel('metric');
        if($metric->deleted == '1' || $metric->stage == 'wait') return $this->genDelistOrDeletedMetricOption($component);

        $pagination = $this->getMetricPagination($component);
        $filters    = $this->processMetricFilter($filterParams, $metric->dateType);

        list($pager, $pagination) = $this->preparePaginationBeforeFetchRecords($pagination);
        $result = $this->metric->getResultByCode($metric->code, $filters, 'cron', $pager);

        $pagination['total']     = $pager->recTotal;
        $pagination['pageTotal'] = $pager->pageTotal;

        $resultHeader   = $this->metric->getViewTableHeader($metric);
        $resultData     = $this->metric->getViewTableData($metric, $result);
        $isObjectMetric = $metric->scope != 'system';
        $isDateMetric   = $metric->dateType != 'nodate';

        $tableOption = $this->getMetricTableOption($metric, $resultHeader, $resultData, $component);
        $chartOption = $this->getMetricChartOption($metric, $resultHeader, $resultData, $component);
        $card        = $this->getMetricCardOption($metric, $resultData, $component);

        $tableOption->pagination = $pagination;
        $card->pagination        = $pagination;

        list($component, $typeChanged) = $this->initMetricComponent($metric, $component);

        $component->chartConfig->title       = $metric->name;
        $component->chartConfig->sourceID    = $metric->id;
        $component->chartConfig->scope       = $metric->scope;
        $component->chartConfig->dateType    = $metric->dateType;

        $latestFilters = $this->buildMetricFilters($metric, $isObjectMetric, $isDateMetric);
        $component     = $this->updateMetricFilters($component, $latestFilters);

        $component->option->chartOption           = $chartOption;
        $component->option->tableOption           = $tableOption;
        $component->option->card                  = $card;
        $component->option->card->isDateMetric    = $isDateMetric;
        $component->option->card->isObjectMetric  = $isObjectMetric;
        $component->option->card->cardDateDefault = $component->option->card->filterValue ? $component->option->card->filterValue->dateString : '';
        $component->option->noDataTip             = $this->metric->getNoDataTip($metric->code);

        return $component;
    }

    public function getMetricPagination($component)
    {
        $tablePagination = array('index' => 1, 'size' => 5, 'total' => 0, 'pageTotal' => 1);
        $cardPagination  = array('index' => 1, 'size' => 2 * 6, 'total' => 0, 'pageTotal' => 1);

        if(empty($component)) return $tablePagination;

        $option = $component->option;
        $displayType = $option->displayType;
        if(isset($option->card->pagination)) $cardPagination = array_merge($cardPagination, (array)$option->card->pagination);
        if(isset($option->tableOption->pagination)) $tablePagination = array_merge($tablePagination, (array)$option->tableOption->pagination);

        $tableOption = $option->tableOption;
        $cardOption  = $option->card;

        if(isset($tableOption->rowNum)) $tablePagination['size'] = $tableOption->rowNum;

        $cardRow = isset($cardOption->countEachRow) ? $cardOption->countEachRow : 2;
        $cardColumn = isset($cardOption->countEachColumn) ? $cardOption->countEachColumn : 6;
        $cardPagination['size'] = $cardRow * $cardColumn;

        return $displayType == 'normal' ? $tablePagination : $cardPagination;
    }

    /**
     * Prepare pagination before fetch records.
     *
     * @param  object    $pagination
     * @access public
     * @return array
     */
    public function preparePaginationBeforeFetchRecords($pagination)
    {
        $defaultPagination = array('index' => 1, 'size' => 2 * 6, 'total' => 0);

        if(is_string($pagination)) $pagination = json_decode($pagination, true);
        if(empty($pagination)) return $pagination;

        $pagination = array_merge($defaultPagination, (array)$pagination);

        extract($pagination);

        $this->app->loadClass('pager', true);
        $pager = new pager($total, $size, $index);

        return array($pager, $pagination);
    }

    /**
     * Update metric component chartConfig filters.
     *
     * @param  object $component
     * @param  array  $latestFilters
     * @access public
     * @return object
     */
    public function updateMetricFilters($component, $latestFilters)
    {
        if(!isset($component->chartConfig->filters))
        {
            $component->chartConfig->filters = $latestFilters;
        }

        return $component;
    }

    /**
     * Build filters for metric.
     *
     * @param  object $metric
     * @access public
     * @return array
     */
    public function buildMetricFilters($metric, $isObjectMetric, $isDateMetric)
    {
        $this->loadModel('metric');
        $scope = $metric->scope;

        $filters = array();
        if($isObjectMetric)
        {
            $scopeFilter = new stdclass();
            $scopeFilter->belong     = 'metric';
            $scopeFilter->field      = $scope;
            $scopeFilter->name       = $this->lang->screen->belong . $this->lang->$scope->common;
            $scopeFilter->type       = 'select';
            $scopeFilter->typeOption = $scope;
            $scopeFilter->default    = null;

            $filters[] = $scopeFilter;
        }

        if($isDateMetric)
        {
            $dateFilter = new stdclass();
            $dateFilter->belong     = 'metric';
            $dateFilter->field      = 'date';
            $dateFilter->name       = $this->lang->screen->dateRange;
            $dateFilter->type       = 'dateRange';
            $dateFilter->typeOption = null;
            $dateFilter->default    = null;

            $filters[] = $dateFilter;
        }

        return $filters;
    }

    /**
     * Get chart option.
     *
     * @param  object $chart
     * @param  object $component
     * @param  array  $filters
     * @access public
     * @return object
     */
    public function getChartOption($chart, $component, $filters = '')
    {
        switch($component->type)
        {
            case 'line':
                return $this->getLineChartOption($component, $chart, $filters);
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
            case 'bar':
                return $this->getBarChartOption($component, $chart, $filters);
            case 'piecircle':
                return $this->buildPieCircleChart($component, $chart);
            case 'pie':
                return $this->getPieChartOption($component, $chart, $filters);
            case 'table':
                return $this->getTableChartOption($component, $chart, $filters);
            case 'radar':
                return $this->getRadarChartOption($component, $chart, $filters);
            case 'card':
                return $this->buildCardChart($component, $chart);
            case 'waterpolo':
                if(strpos($chart->settings, 'waterpolo') === false) return $this->buildWaterPolo($component, $chart);
                return $this->getWaterPoloOption($component, $chart, $filters);
            case 'metric':
                return $this->getMetricOption($component, $chart, $filters);
            default:
                return '';
        }
    }

    /**
     * Get chart option about rotate.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return object
     */
    public function getAxisRotateOption($chart, $component)
    {
        $this->loadModel('chart');
        if(!in_array($chart->type, $this->config->chart->canLabelRotate)) return $component;

        $settings = json_decode($chart->settings, true);
        if(!isset($settings[0])) return $component;

        $setting  = $settings[0];

        $component->chartConfig->xAxis = new stdclass();
        $component->chartConfig->yAxis = new stdclass();
        $component->chartConfig->xAxis->axisLabel = new stdclass();
        $component->chartConfig->yAxis->axisLabel = new stdclass();

        $component->chartConfig->xAxis->axisLabel->rotate = 0;
        $component->chartConfig->yAxis->axisLabel->rotate = 0;
        if(isset($setting['rotateX']) && $setting['rotateX'] == 'use') $component->chartConfig->xAxis->axisLabel->rotate = 30;
        if(isset($setting['rotateY']) && $setting['rotateY'] == 'use') $component->chartConfig->yAxis->axisLabel->rotate = 30;

        return $component;
    }

    /**
     * Get bar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getBarChartOption($component, $chart, $filters = '')
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs, true);
            $settings = $settings[0];

            $isSort = in_array($chart->id, $this->config->screen->annualRankingChart) ? true : false;
            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->bi->getMultiData($settings, $chart->sql, $filters, $chart->driver, $isSort);

            $fields       = json_decode($chart->fields, true);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);

            foreach($yStats as $index => $dataList)
            {
                $field     = zget($fields, $metrics[$index]);
                $fieldName = $field['name'];
                if(isset($langs[$field['field']]) and !empty($langs[$field['field']][$clientLang])) $fieldName = $langs[$field['field']][$clientLang];
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                $dimensions[] = $field;

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

        $component = $this->setComponentDefaults($component);

        return $component;
    }

    /**
     * Get line chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getLineChartOption($component, $chart, $filters = '')
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs, true);
            $settings = $settings[0];

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->bi->getMultiData($settings, $chart->sql, $filters, $chart->driver);

            $fields       = json_decode($chart->fields, true);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);

            foreach($yStats as $index => $dataList)
            {
                $field     = zget($fields, $metrics[$index]);
                $fieldName = $field['name'];
                if(isset($langs[$field['field']]) and !empty($langs[$field['field']][$clientLang])) $fieldName = $langs[$field['field']][$clientLang];
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                $dimensions[] = $field;

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

        return $this->setComponentDefaults($component);
    }

    /**
     * Get pie chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getPieChartOption($component, $chart, $filters = '')
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $settings = $settings[0];

            $options = $this->loadModel('chart')->genPie(json_decode($chart->fields, true), $settings, $chart->sql, $filters, $chart->driver);

            if($settings['group'][0]['field'] == $settings['metric'][0]['field']) $settings['group'][0]['field'] = $settings['group'][0]['field'] . '1';
            $dimensions = array($settings['group'][0]['field'], $settings['metric'][0]['field']);
            $sourceData = array();
            foreach($options['series'] as $dataList)
            {
                $field = $settings['metric'][0]['field'];
                foreach($dataList['data'] as $data)
                {
                    $fieldValue = $data['name'];
                    if(empty($sourceData[$fieldValue]))
                    {
                        $sourceData[$fieldValue] = new stdclass();
                        $sourceData[$fieldValue]->{$settings['group'][0]['field']} = (string)$fieldValue;
                    }
                    $sourceData[$fieldValue]->{$field} = $data['value'];
                }
            }

            if(empty($sourceData)) $dimensions = array();

            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        return $this->setComponentDefaults($component);
    }

    /**
     * Get radar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getRadarChartOption($component, $chart, $filters = '')
    {
        $indicator  = array();
        $seriesData = array();
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs, true);
            $settings = $settings[0];

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->bi->getMultiData($settings, $chart->sql, $filters, $chart->driver);

            $fields         = json_decode($chart->fields, true);
            $radarIndicator = array();
            $seriesData     = array();
            $max            = 0;
            $clientLang     = $this->app->getClientLang();
            $xLabelValues   = $this->processXLabel($xLabels, $fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field']);

            foreach($yStats as $index => $dataList)
            {
                $fieldObj  = zget($fields, $metrics[$index]);
                $fieldName = $fieldObj['name'];
                $field     = $fieldObj['field'];

                if(isset($langs[$field])) $fieldName = zget($langs[$field], $clientLang, $fieldName);

                $seriesData[$index] = new stdclass();
                $seriesData[$index]->name = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';

                $values = array();
                foreach($dataList as $valueField => $value)
                {
                    $values[] = (float)$value;
                    $max = $max < $value ? (float)$value : (float)$max;
                }
                $seriesData[$index]->value = $values;
            }

            if(!empty($dataList))
            {
                foreach($dataList as $valueField => $value)
                {
                    $indicator = new stdclass();
                    $indicator->name   = $xLabelValues[$valueField];
                    $indicator->max    = $max;
                    $radarIndicator[]  = $indicator;;
                }
            }
            $component->option->dataset->radarIndicator = $radarIndicator;
            $component->option->dataset->seriesData     = $seriesData;
        }

        return $this->setComponentDefaults($component);
    }


    /**
     * Get table chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getTableChartOption($component, $chart, $filters = array())
    {
        if($chart->sql)
        {
            $chart->settings = json_decode($chart->settings, true);
            $this->loadModel('pivot')->addDrills($chart);

            $settings     = $chart->settings;
            $fields       = json_decode($chart->fields, true);
            $langs        = json_decode($chart->langs, true);
            $chartFilters = json_decode($chart->filters, true);

            if(empty($langs)) $langs = array();
            if(empty($fields)) $fields = array();
            if(!is_array($filters)) $filters = array();

            if(empty($chartFilters)) $filters = false;

            if(isset($settings['summary']) and $settings['summary'] == 'notuse')
            {
                list($options, $config) = $this->loadModel('pivot')->genOriginSheet($fields, $settings, $chart->sql, $filters, $langs, $chart->driver);
            }
            else
            {
                list($options, $config) = $this->loadModel('pivot')->genSheet($fields, $settings, $chart->sql, $filters, $langs, $chart->driver);
            }

            $colspan         = array();
            $showColPosition = $this->pivot->getShowColPosition($options);
            $isShowLastRow   = $this->pivot->isShowLastRow($showColPosition);
            if($isShowLastRow and !empty($options->array))
            {
                $count = count($options->array);
                $colspan[$count - 1][0] = count($options->groups);
            }

            $dataset = array();
            foreach($options->array as $data)
            {
                $data = array_values($data);
                $dataset[] = array_map('strval', $data);
            }

            $skipCellList = array();
            foreach($config as $i => $data)
            {
                foreach($data as $j => $rowspan)
                {
                    if(in_array(array($i, $j), $skipCellList)) continue;
                    for($k = 1; $k < $rowspan; $k ++)
                    {
                        unset($dataset[$i + $k][$j]);
                        $skipCellList[] = array($i + $k, $j);
                    }
                }
            }

            $align        = array();
            $headers      = array();
            $groupCount   = 0;
            $drillConfigs = array();
            foreach($options->cols as $cols)
            {
                $count  = 1;
                $header = array();
                foreach($cols as $data)
                {
                    $colspan    = zget($data, 'colspan', 1);
                    $isDrilling = zget($data, 'isDrilling', 0);
                    $conditions = zget($data, 'condition', array());
                    $drillField = zget($data, 'drillField', '');
                    $isGroup    = zget($data, 'isGroup', 0);
                    $isSlice    = zget($data, 'isSlice', 0);

                    if($isGroup)
                    {
                        $groupCount += 1;
                    }
                    elseif(!$isSlice)
                    {
                        $drillConfigs[] = $isDrilling ? array('drillField' => $drillField, 'conditions' => $conditions) : false;
                        if($colspan > 1) $drillConfigs = array_merge($drillConfigs, array_fill(0, $colspan - 1, false));
                    }

                    $header[] = $data;
                    if($count == 1) $align[] = 'center';
                }
                $headers[] = $header;
                $count ++;
            }

            $drills = array();
            $groupFields = array_fill(0, $groupCount, 'groupCol');
            foreach($options->drills as $drill)
            {
                if(!isset($drill['drillFields'])) continue;
                $drillFields = array_values($drill['drillFields']);
                $drillRow = array();
                foreach($drillFields as $index => $drillField)
                {
                    $drillConfig = $drillConfigs[$index];
                    $drillRow[] = $drillConfig ? array('fields' => $drillField, 'config' => $drillConfig) : false;
                }
                $drillRow = array_merge($groupFields, $drillRow);
                $drills[] = $drillRow;
            }

            if(!isset($component->chartConfig->tableInfo)) $component->chartConfig->tableInfo = new stdclass();
            $component->option->header      = $headers;
            $component->option->align       = $align;
            $component->option->columnWidth = array();
            $component->option->rowspan     = $config;
            $component->option->colspan     = $colspan;
            $component->option->dataset     = $dataset;
            $component->option->drills      = $drills;
        }

        return $this->setComponentDefaults($component);
    }

    /**
     * Get chart filters
     *
     * @param object $chart
     * @access public
     * @return void
     */
    public function getChartFilters($chart)
    {
        $filters = json_decode($chart->filters, true);
        $fields  = json_decode($chart->fields, true);

        if(empty($filters)) return array();

        $this->loadModel('pivot');

        $screenFilters = array();
        foreach($filters as $filter)
        {
            $isQuery = (isset($filter['from']) and $filter['from'] == 'query');

            if($isQuery)
            {
                if($filter['type'] == 'date' or $filter['type'] == 'datetime')
                {
                    if(isset($filter['default']))
                    {
                        $default = $this->pivot->processDateVar($filter['default']);

                        $filter['default'] = empty($default) ? null : strtotime($default) * 1000;
                    }
                }

                $screenFilters[] = (object)$filter;
                continue;
            }

            if($filter['type'] == 'date' or $filter['type'] == 'datetime')
            {
                if(isset($filter['default']))
                {
                    $default = $filter['default'];
                    $begin   = $default['begin'];
                    $end     = $default['end'];

                    if(empty($begin) and empty($end))
                    {
                        $filter['default'] = null;
                    }
                    else if(empty($begin) or empty($end))
                    {
                        $filter['default'] = empty($begin) ? strtotime($end) * 1000 : strtotime($begin) * 1000;
                    }
                    else
                    {
                        $filter['default'] = array(strtotime($begin) * 1000, strtotime($end) * 1000);
                    }
                }
                else
                {
                    $filter['default'] = null;
                }
            }

            $screenFilters[] = (object)$filter;
        }

        return $screenFilters;
    }

    /**
     * Process xLabel with lang
     *
     * @param  array   $xLabel
     * @param  string  $type
     * @param  string  $object
     * @param  string  $field
     * @access public
     * @return array
     */
    public function processXLabel($xLabels, $type, $object, $field)
    {
        $options = $this->getSysOptions($type, $object, $field);
        $xLabelValues = array();
        foreach($xLabels as $index => $label)
        {
            $xLabelValues[$label] = isset($options[$label]) ? $options[$label] : $label;
        }

        return $xLabelValues;
    }

    /**
     * Process metric filter.
     *
     * @param  array  $filterParams
     * @param  string $dateType
     * @access public
     * @return array
     */
    public function processMetricFilter($filterParams, $dateType)
    {
        $filters = array();
        foreach($filterParams as $filterParam)
        {
            $field = $filterParam['field'];
            $value = $filterParam['default'];
            if(empty($value)) continue;

            if($field == 'date')
            {
                $beginValue = $value[0];
                $endValue   = $value[1];

                $beginFilter = $this->formatMetricDateByType($beginValue, $dateType);
                $endFilter   = $this->formatMetricDateByType($endValue, $dateType);

                $beginFilter->value = $beginValue;
                $endFilter->value   = $endValue;

                $filters['dateBegin'] = $beginFilter->$dateType;
                $filters['dateEnd']   = $endFilter->$dateType;
            }
            else
            {
                $scopeFilter = new stdclass();
                $scopeFilter->value = $value;
                $scopeFilter->type  = $field;

                $filters['scope'] = implode(',', $value);
            }
        }

        return $filters;
    }

    /**
     * Format date of metric's filter by type.
     *
     * @param string  $stamp
     * @param string  $dateType
     * @access public
     * @return array
     */
    public function formatMetricDateByType($stamp, $dateType)
    {
        $formatedDate = new stdclass();
        $formatedDate->year = date('Y', $stamp/1000);
        if($dateType == 'month') $formatedDate->month = date('Y-m', $stamp/1000);
        if($dateType == 'week')  $formatedDate->week  = date('Y-W', $stamp/1000);
        if($dateType == 'day')   $formatedDate->day   = date('Y-m-d', $stamp/1000);

        return $formatedDate;
    }

    /**
     * Get system options.
     *
     * @param string $type
     * @param string $object
     * @param string $field
     * @param string $sql
     * @param string $saveAs
     * @access public
     * @return array
     */
    public function getSysOptions($type, $object = '', $field = '', $sql = '', $saveAs = '')
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
                    $useField = $field;
                    $useTable = $object;

                    $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
                    if(is_file($path))
                    {
                        include $path;
                        if(isset($schema->fields[$field]['object']))
                        {
                            $fieldObject = $schema->fields[$field]['object'];
                            $fieldShow   = explode('.', $schema->fields[$field]['show']);

                            if($fieldObject) $useTable = $fieldObject;
                            if(count($fieldShow) == 2) $useField = $fieldShow[1];
                        }
                    }

                    $table = isset($this->config->objectTables[$useTable]) ? $this->config->objectTables[$useTable] : zget($this->config->objectTables, $object, '');
                    if($table)
                    {
                        $columns = $this->dbh->query("SHOW COLUMNS FROM $table")->fetchAll();
                        foreach($columns as $id => $column) $columns[$id] = (array)$column;
                        $fieldList = array_column($columns, 'Field');

                        $useField = in_array($useField, $fieldList) ? $useField : 'id';
                        $options = $this->dao->select("id, {$useField}")->from($table)->fetchPairs();
                    }
                }
                break;
            case strpos($type, '.') !== false:
                $params = explode('.', $type);
                if(empty(array_filter($params)))
                {
                    $options = array();
                }
                else
                {
                    $module   = $params[0];
                    $typeList = $params[1] . 'List';
                    $this->app->loadLang($module);
                    $options = $this->lang->$module->$typeList;
                }
                break;
            default:
                if($field and $sql)
                {
                    $keyField   = $field;
                    $valueField = $saveAs ? $saveAs : $field;
                    $options = $this->getOptionsFromSql($sql, $keyField, $valueField);
                }
                break;
        }

        if($sql and $field and $saveAs and in_array($type, array('user', 'product', 'project', 'execution', 'dept', 'project.status', 'option', 'object')))
        {
            $options = $this->getOptionsFromSql($sql, $field, $saveAs);
        }

        return array_filter($options);
    }

    /**
     * Get pairs from column by keyField and valueField.
     *
     * @param  string $sql
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return array
     */
    public function getOptionsFromSql($sql, $keyField, $valueField)
    {
        $options = array();
        $cols    = $this->dbh->query($sql)->fetchAll();
        $sample  = current($cols);

        if(!isset($sample->$keyField) or !isset($sample->$valueField)) return $options;

        foreach($cols as $col)
        {
            $key   = $col->$keyField;
            $value = $col->$valueField;
            $options[$key] = $value;
        }

        return $options;
    }

    /**
     * Build component list.
     *
     * @param  array $componentList
     * @access public
     * @return array
     */
    public function buildComponentList($componentList)
    {
        $components = array();
        foreach($componentList as $component)
        {
            if($component) $components[] = $this->buildComponent($component);
        }

        return $components;
    }

    /**
     * Build component.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildComponent($component)
    {
        /* If chart is builtin, build it. */
        if(isset($component->sourceID) and $component->sourceID) return $this->buildChart($component);
        if(isset($component->key) and $component->key === 'Select') return $this->buildSelect($component);

        if(empty($component->isGroup)) return $this->setComponentDefaults($component);

        $component->groupList = $this->buildComponentList($component->groupList);
        return $this->buildGroup($component);
    }

    /**
     * Build chart group.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildGroup($component)
    {
        return $this->setComponentDefaults($component);
    }

    /**
     * Set component defaults.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function setComponentDefaults($component)
    {
        $this->loadModel('bi');
        if(!isset($component->styles))  $component->styles  = $this->config->bi->default->styles;
        if(!isset($component->status))  $component->status  = $this->config->bi->default->status;
        if(!isset($component->request)) $component->request = $this->config->bi->default->request;
        if(!isset($component->events))  $component->events  = $this->config->bi->default->events;

        return $component;
    }

    /**
     * Build select.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildSelect($component)
    {
        switch($component->type)
        {
            case 'year':
                $component->option->value = $this->filter->year;

                $beginYear = $this->dao->select('YEAR(MIN(date)) year')->from(TABLE_ACTION)->where('date')->notZeroDate()->fetch('year');
                if($beginYear < 2009) $beginYear = 2009;

                $options = array();
                for($year = date('Y'); $year >= $beginYear; $year--) $options[] = array('label' => $year, 'value' => $year);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen. "&year=' + value + '&month=" . $this->filter->month . "&dept=" . $this->filter->dept . "&account=" . $this->filter->account . "')";
                $component->option->onChange = "window.location.href = $url";
                break;
            case 'month':
                $component->option->value = $this->filter->month;

                $beginYear  = $this->dao->select('YEAR(MIN(date)) year')->from(TABLE_ACTION)->where('date')->notZeroDate()->fetch('year');
                $beginMonth = $this->dao->select('MONTH(MIN(date)) month')->from(TABLE_ACTION)->where('date')->notZeroDate()->fetch('month');

                $currentYear  = date('Y');
                $currentMonth = date('n');

                $options = array();
                for($month = 12; $month >= 1; $month--)
                {
                    if($currentYear == $this->filter->year && $month > $currentMonth) continue;
                    if($currentYear == $beginYear && $month < $beginMonth) continue;

                    $options[] = array('label' => $month, 'value' => $month);
                }
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen. "&year=" . $this->filter->year . "&month=' + value + '&dept=" . $this->filter->dept . "&account=" . $this->filter->account . "')";
                $component->option->onChange = "window.location.href = $url";
                break;
            case 'dept':
                $component->option->value = (string)$this->filter->dept;

                $options = array(array('label' => $this->lang->screen->allDepts, 'value' => '0'));
                $depts = $this->dao->select('id,name')->from(TABLE_DEPT)->where('grade')->eq(1)->fetchAll();
                foreach($depts as $dept)
                {
                    $options[] = array('label' => $dept->name, 'value' => $dept->id);
                }
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=' + value + '&account=')";
                $component->option->onChange = "window.location.href = $url";
                break;
            case 'account':
                $component->option->value = $this->filter->account;

                $options = array(array('label' => $this->lang->screen->allUsers, 'value' => ''));
                $depts   = array();
                if($this->filter->dept) $depts = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like(',' . $this->filter->dept . ',%')->fetchPairs();
                $users = $this->dao->select('account,realname')->from(TABLE_USER)
                    ->where('deleted')->eq(0)
                    ->beginIF($this->filter->dept)->andWhere('dept')->in($depts)->fi()
                    ->fetchAll();
                foreach($users as $user)
                {
                    $options[] = array('label' => $user->realname, 'value' => $user->account);
                }
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=" . $this->filter->dept . "&account=' + value)";
                $component->option->onChange = "window.location.href = $url";
                break;
        }

        foreach($component->filterCharts as $chart)
        {
            if(!isset($this->filter->charts[$chart->chart])) $this->filter->charts[$chart->chart] = array();
            $this->filter->charts[$chart->chart][$component->type] = $chart->field;
        }

        return $this->setComponentDefaults($component);
    }

    /**
     * Build chart.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildChart($component)
    {
        $chart = $this->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();
        switch($chart->type)
        {
            case 'card':
                return $this->buildCardChart($component, $chart);
                break;
            case 'line':
                if($chart->builtin == '0')
                {
                    $chart->sql = $this->setFilterSQL($chart);
                    return $this->getLineChartOption($component, $chart, array());
                }
                return $this->buildLineChart($component, $chart);
                break;
            case 'bar':
                return $this->buildBarChart($component, $chart);
                break;
            case 'piecircle':
                return $this->buildPieCircleChart($component, $chart);
                break;
            case 'pie':
                if($chart->builtin == '0') return $this->getPieChartOption($component, $chart, array());
                return $this->buildPieChart($component, $chart);
                break;
            case 'radar':
                return $this->buildRadarChart($component, $chart);
                break;
            case 'org':
                return $this->buildOrgChart($component, $chart);
                break;
            case 'funnel':
                return $this->buildFunnelChart($component, $chart);
                break;
            case 'table':
                return $this->buildTableChart($component, $chart);
                break;
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
                $chart->sql = $this->setFilterSQL($chart);
                return $this->getBarChartOption($component, $chart);
                break;
            case 'waterpolo':
                return $this->getWaterPoloOption($component, $chart, array());
        }
    }

    /**
     * Set select filter.
     *
     * @param  string $sourceID
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function setSelectFilter($sourceID, $filters)
    {
        if(empty($filters)) return;

        foreach($filters as $filter)
        {
            if(!isset($this->filter->charts[$sourceID])) $this->filter->charts[$sourceID] = array();
            $this->filter->charts[$sourceID][$filter['type']] = $filter['field'];
        }
    }

    /**
     * Set SQL filter
     *
     * @param object $chart
     * @access public
     * @return string
     */
    public function setFilterSQL($chart)
    {
        if(isset($this->filter->charts[$chart->id]))
        {
            $conditions = array();
            foreach($this->filter->charts[$chart->id] as $key => $field)
            {
                switch($key)
                {
                    case 'year':
                        $conditions[] = $field . " = '" . $this->filter->$key . "'";
                        break;
                    case 'month':
                        $conditions[] = $field . " = '" . $this->filter->$key . "'";
                        break;
                    case 'dept':
                        if($this->filter->dept and !$this->filter->account)
                        {
                            $accountField = $this->filter->charts[$chart->id]['account'];
                            $users = $this->dao->select('account')->from(TABLE_USER)->alias('t1')
                                ->leftJoin(TABLE_DEPT)->alias('t2')
                                ->on('t1.dept = t2.id')
                                ->where('t2.path')->like(',' . $this->filter->dept . ',%')
                                ->fetchPairs('account');
                            $accounts = array();
                            foreach($users as $account) $accounts[] = "'" . $account . "'";

                            $conditions[] = $accountField . ' IN (' . implode(',', $accounts) . ')';
                        }
                        break;
                    case 'account':
                        if($this->filter->account) $conditions[] = $field . " = '" . $this->filter->$key . "'";
                        break;
                }
            }

            if($conditions) return 'SELECT * FROM (' . str_replace(';', '', $chart->sql) . ') AS t1 WHERE ' . implode(' AND ', $conditions);
        }

        return $chart->sql;
    }

    /**
     * Build card chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildCardChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->option->dataset = '?';
        }
        else
        {
            $value = 0;

            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->value))
                {
                    $field   = $settings->value->field;
                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->buildDataset($chart->id, $chart->driver, $sql);

                    if($settings->value->type === 'text')
                    {
                        $value = empty($results[0]) ? '' : $results[0]->$field;
                    }
                    if($settings->value->type === 'value')
                    {
                        $value = empty($results[0]) ? 0 : $results[0]->$field;
                    }
                    if($settings->value->agg === 'count')
                    {
                        $value = count($results);
                    }
                    else if($settings->value->agg === 'sum')
                    {
                        foreach($results as $result)
                        {
                            $value += intval($result->$field);
                        }

                        $value = round($value);
                    }
                }
                else
                {
                    $value = '?';
                }
            }
            $component->option->dataset = (string)$value;
        }

        return $this->setComponentDefaults($component);
    }

    /**
     * Build line chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildLineChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "LineCommon";
            $component->chartConfig = json_decode('{"key":"LineCommon","chartKey":"VLineCommon","conKey":"VCLineCommon","title":"折线图","category":"Lines","categoryName":"折线图","package":"Charts","chartFrame":"echarts","image":"/static/png/line-e714bc74.png"}');
            $component->option      = json_decode('{"legend":{"show":true,"top":"5%","textStyle":{"color":"#B9B8CE"}},"xAxis":{"type":"category"},"yAxis":{"show":true,"axisLine":{"show":true},"type":"value"},"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    $sourceData = array();

                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->bi->queryWithDriver($chart->driver, $sql);
                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;
                        $row   = array($key => $result->$field);

                        foreach($settings->yaxis as $yaxis)
                        {
                            $field = $yaxis->field;
                            $row[$yaxis->name] = $result->$field;
                        }
                        $sourceData[] = $row;
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = $sourceData;
                }
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build table chart
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildTableChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "TableScrollBoard";
            $component->chartConfig = json_decode('{"key":"TableScrollBoard","chartKey":"VTableScrollBoard","conKey":"VCTableScrollBoard","title":"轮播列表","category":"Tables","categoryName":"表格","package":"Tables","chartFrame":"common","image":"/static/png/table_scrollboard-fb642e78.png"}');
            $component->option      = json_decode('{"header":["列1","列2","列3"],"dataset":[["行1列1","行1列2","行1列3"],["行2列1","行2列2","行2列3"],["行3列1","行3列2","行3列3"]],"rowNum":2,"waitTime":2,"headerHeight":35,"carousel":"single","headerBGC":"#00BAFF","oddRowBGC":"#003B51","evenRowBGC":"#0A2732"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->column))
                {
                    $header  = array();
                    $dataset = array();
                    foreach($settings->column as $column)
                    {
                        $header[$column->field] = $column->name;
                    }

                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->buildDataset($chart->id, $chart->driver, $sql);

                    foreach($results as $result)
                    {
                        $row = array();
                        foreach($header as $field => $name)
                        {
                            $row[] = $result->$field;
                        }
                        $dataset[] = $row;
                    }

                    $component->option->header  = array_values($header);
                    $component->option->dataset = $dataset;
                }
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build bar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildBarChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {}}}');
            $component->events      = json_decode('{"baseEvent": {}, "advancedEvents": {}}');
            $component->key         = "BarCrossrange";
            $component->chartConfig = json_decode('{"key": "BarCrossrange", "chartKey": "VBarCrossrange", "conKey": "VCBarCrossrange", "title": "横向柱状图", "category": "Bars", "categoryName": "柱状图", "package": "Charts", "chartFrame": "echarts", "image": "/static/png/bar_y-05067169.png" }');
            $component->option      = json_decode('{"xAxis": { "show": true, "type": "category" }, "yAxis": { "show": true, "axisLine": { "show": true }, "type": "value" }, "series": [], "backgroundColor": "rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    $sourceData = array();

                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->bi->queryWithDriver($chart->driver, $sql);

                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;

                        if($settings->yaxis[0]->agg == 'sum')
                        {
                            if(!isset($sourceData[$result->$field])) $sourceData[$result->$field] = array($key => $result->$field);

                            foreach($settings->yaxis as $yaxis)
                            {
                                $valueField = $yaxis->field;
                                if(!isset($sourceData[$result->$field][$yaxis->name])) $sourceData[$result->$field][$yaxis->name] = 0;
                                $sourceData[$result->$field][$yaxis->name] += $result->$valueField;
                            }
                        }
                        else
                        {
                            $row = array($key => $result->$field);

                            foreach($settings->yaxis as $yaxis)
                            {
                                $field = $yaxis->field;
                                $row[$yaxis->name] = $result->$field;
                            }
                            $sourceData[] = $row;
                        }
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = array_values($sourceData);
                }
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build pie chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildPieChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCommon";
            $component->chartConfig = json_decode('{"key":"PieCommon","chartKey":"VPieCommon","conKey":"VCPieCommon","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-9620f191.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->metric))
                {
                    $dimensions = array($settings->group[0]->name, $settings->metric[0]->field);
                    $sourceData = array();

                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->bi->queryWithDriver($driver, $sql);
                    $group = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->$group])) $groupCount[$result->$group] = 0;
                            $groupCount[$result->$group]++;
                        }
                    }
                    arsort($groupCount);

                    foreach($groupCount as $groupValue => $groupCount)
                    {
                        $sourceData[] = array($settings->group[0]->name => $groupValue, $settings->metric[0]->field => $groupCount);
                    }
                }
                if(empty($sourceData)) $dimensions = array();

                $component->option->dataset->dimensions = $dimensions;
                $component->option->dataset->source     = $sourceData;
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build piecircle chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildPieCircleChart($component, $chart)
    {
        $option = new stdclass();
        $option->type = 'nomal';
        $option->series = array();
        $option->series[0] = new stdclass();
        $option->series[0]->type = 'pie';
        $option->series[0]->radius = '70%';
        $option->series[0]->roseType = false;
        $option->backgroundColor = 'rgba(0,0,0,0)';
        $option->series[0]->data = array();
        $option->series[0]->data[0] = new stdclass();
        $option->series[0]->data[0]->value = array();
        $option->series[0]->data[0]->value[0] = 0;
        $option->series[0]->data[1] = new stdclass();
        $option->series[0]->data[1]->value = array();
        $option->series[0]->data[1]->value[0] = 0;

        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCircle";
            $component->chartConfig = json_decode('{"key":"PieCircle","chartKey":"VPieCircle","conKey":"VCPieCircle","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-circle-258fcce7.png"}');
            $component->option      = $option;

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->metric))
                {
                    $sourceData = array();

                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->bi->queryWithDriver($chart->driver, $sql);
                    $group = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->$group])) $groupCount[$result->$group] = 0;
                            $groupCount[$result->$group]++;
                        }
                    }

                    foreach($groupCount as $groupValue => $groupCount) $sourceData[$groupValue] = $groupCount;
                }
                $doneData = round((array_sum($sourceData) != 0 and !empty($sourceData['done'])) ? $sourceData['done'] / array_sum($sourceData) : 0, 4);
                $component->option->dataset = $doneData;
                if(!isset($component->option->series) || !is_array($component->option->series)) $component->option->series = $option->series;
                if(!isset($component->option)) $component->option = $option;
                $component->option->series[0]->data[0]->value  = array($doneData);
                $component->option->series[0]->data[1]->value  = array(1 - $doneData);
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build water polo chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildWaterPolo($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCircle";
            $component->chartConfig = json_decode('{"key":"WaterPolo","chartKey":"VWaterPolo","conKey":"VCWaterPolo","title":"水球图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"water_WaterPolo.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"liquidFill","radius":"90%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings   = json_decode($chart->settings);
                $sourceData = 0;
                if($settings and isset($settings->metric))
                {
                    $sql        = $this->setFilterSQL($chart);
                    $result     = $this->dao->query($sql)->fetch();
                    $group      = $settings->group[0]->field;
                    $sourceData = zget($result, $group, 0);
                    if(empty($sourceData)) $sourceData = 0;
                }
                $component->option->dataset = $sourceData;
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Get waterpolo option.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function getWaterPoloOption($component, $chart, $filters)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCircle";
            $component->chartConfig = json_decode('{"key":"WaterPolo","chartKey":"VWaterPolo","conKey":"VCWaterPolo","title":"水球图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"water_WaterPolo.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"liquidFill","radius":"90%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            $setting = json_decode($chart->settings, true)[0];
            $options = $this->bi->genWaterPolo(json_decode($chart->fields, true), $setting, $chart->sql, $filters, $chart->driver);

            $component->option->dataset = $options['series'][0]['data'][0];
            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Get option of metric chart.
     *
     * @param  object $metric
     * @param  array  $resultHeader
     * @param  array  $resultData
     * @param  object $component
     * @access public
     * @return object
     */
    public function getMetricChartOption($metric, $resultHeader, $resultData, $component = null)
    {
        $chartOption = $this->metric->getEchartsOptions($resultHeader, $resultData);
        if(!$chartOption) return false;

        if(isset($component) && isset($component->option->chartOption))
        {
            $preChartOption = $component->option->chartOption;
            $preChartOption->series = $chartOption['series'];
            if(!isset($preChartOption->xAxis)) $preChartOption->xAxis = $chartOption['xAxis'];
            $preChartOption->xAxis->data = $chartOption['xAxis']['data'];
            return $preChartOption;
        }

        if(!isset($chartOption['title']))
        {
            $chartOption['title'] = array();
            $chartOption['title']['show']      = false;
            $chartOption['title']['titleShow'] = true;
            $chartOption['title']['textStyle'] = array('color' => '#BFBFBF');
        }

        $chartOption['title']['text']   = $metric->name;
        $chartOption['backgroundColor'] = "#0B1727FF";

        if(!isset($chartOption['legend'])) $chartOption['legend'] = array();
        $chartOption['legend']['textStyle']['color'] = 'white';
        $chartOption['legend']['inactiveColor']      = 'gray';

        return $chartOption;
    }

    /**
     * Get option of metric table.
     *
     * @param  array  $resultHeader
     * @param  array  $resultData
     * @param  array  $filterParams
     * @param  object $component
     * @access public
     * @return object
     */
    public function getMetricTableOption($metric, $resultHeader, $resultData, $component = null)
    {
        $this->loadModel('metric');

        $isObjectMetric = $this->metric->isObjectMetric($resultHeader);
        $dateType       = $metric->dateType;

        list($groupHeader, $groupData) = $this->metric->getGroupTable($resultHeader, $resultData, $metric->dateType, false);

        $tableOption = new stdclass();
        if(!empty($component) && isset($component->option->tableOption)) $tableOption = $component->option->tableOption;

        $tableOption->headers = $isObjectMetric ? $this->getMetricHeaders($groupHeader, $dateType) : array($groupHeader);
        $tableOption->data    = $groupData;
        $tableOption->scope   = $metric->scope;

        return $tableOption;
    }

    /**
     * Filter metric data.
     *
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $isObjectMetric
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function filterMetricData($data, $dateType, $isObjectMetric, $filters = array())
    {
        if(empty($filters)) return $data;

        if($isObjectMetric)
        {
            if(isset($filters['scope']))
            {
                $scopeFilter     = $filters['scope'];
                $objectPairs     = $this->loadModel('metric')->getPairsByScope($scopeFilter->type);
                $selectedObjects = array_intersect_key($objectPairs, array_flip($scopeFilter->value));

                foreach($data as $index => $row)
                {
                    if(!in_array($row['scope'], $selectedObjects)) unset($data[$index]);
                }
            }

            $filteredData = array();
            if(isset($filters['begin'], $filters['end']))
            {
                $beginFilter = $filters['begin']->$dateType;
                $endFilter   = $filters['end']->$dateType;
                foreach($data as $index => $row)
                {
                    $filteredData[$index] = array_filter($row, function($value, $key) use ($beginFilter, $endFilter)
                    {
                        if($key == 'scope') return true;
                        if($key >= $beginFilter && $key <= $endFilter) return true;
                        return false;
                    }, ARRAY_FILTER_USE_BOTH);
                }
            }
            else
            {
                $filteredData = $data;
            }
        }
        else
        {
            $beginFilter = $filters['begin']->$dateType;
            $endFilter   = $filters['end']->$dateType;
            $filteredData = array_filter($data, function($row) use ($beginFilter, $endFilter)
            {
                if($row['date'] >= $beginFilter && $row['date'] <= $endFilter) return true;
            });
        }

        return array_values($filteredData);
    }

    /**
     * 获取度量项卡片参数。
     * Get card option of metric.
     *
     * @param  object $metric
     * @access public
     * @return object
     */
    public function getMetricCardOption(object $metric, $resultData, $component = null): object
    {
        $this->loadModel('metric');

        $option = new stdclass();
        if(empty($component))
        {
            $option->displayType = 'normal';
            $option->cardType    = 'A';
            $option->dateType    = $metric->dateType;
            $option->bgColor     = '#26292EFF';
            $option->border      = array('color' => '#515458FF', 'width' => 1, 'radius' => 2);
            $option->scope       = $metric->scope;
            $option->objectPairs = array();
        }
        else
        {
            $option = $component->option->card;
        }
        $option->data        = $resultData;
        $option->filterValue = (is_array($option->data) && !empty($option->data)) ? current($option->data) : array();

        return $option;
    }

    /**
     * Get table headers of metric in screen designer.
     *
     * @param  array  $resultHeaders
     * @param  string $dateType
     * @param  object $filters
     * @access public
     * @return object
     */
    public function getMetricHeaders($resultHeader, $dateType)
    {
        $headers = array_fill(0, 2, array());

        foreach($resultHeader as $head)
        {
            if($head['name'] == 'scope')
            {
                if($dateType != 'year') $head['rowspan'] = 2;
                $headers[0][] = $head;
            }
            else
            {
                $row = $dateType == 'year' ? 0 : 1;

                $head['type']  = $dateType;
                $head['value'] = $head['name'];
                $headers[$row][] = $head;
            }
        }

        $dateGroups = array_count_values(array_column($resultHeader, 'headerGroup'));
        foreach($dateGroups as $date => $count)
        {
            $dateGroup = array();
            $dateGroup['colspan'] = $count;
            $dateGroup['title']   = $date;
            $dateGroup['name']    = $date;
            $dateGroup['value']   = substr($date, 0, 4);
            $dateGroup['type']    = 'year';

            $headers[0][] = $dateGroup;
        }

        if($dateType == 'year') unset($headers[1]);

        return $headers;
    }

    /**
     * Build radar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildRadarChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "Radar";
            $component->chartConfig = json_decode('{"key":"Radar","chartKey":"VRadar","conKey":"VCRadar","title":"雷达图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"/static/png/radar-91567f95.png"}');
            $component->option      = json_decode('{"radar":{"indicator":[{"name":"数据1","max":6500},{"name":"数据2","max":16000},{"name":"数据3","max":30000},{"name":"数据4","max":38000},{"name":"数据5","max":52000}]},"series":[{"name":"radar","type":"radar","areaStyle":{"opacity":0.1},"data":[{"name":"data1","value":[4200,3000,20000,35000,50000]}]}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            $indicator  = array();
            $seriesData = array();
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->metric))
                {
                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->bi->queryWithDriver($driver, $sql);
                    $group   = $settings->group[0]->field;

                    $metrics = array();
                    foreach($settings->metric as $metric)
                    {
                        $metrics[$metric->key] = array('field' => $metric->field, 'name' => $metric->name, 'value' => 0);
                    }


                    foreach($results as $result)
                    {
                        if(isset($metrics[$result->$group]))
                        {
                            $field = $metrics[$result->$group]['field'];
                            $metrics[$result->$group]['value'] += $result->$field;
                        }
                    }
                    $max = 0;
                    foreach($metrics as $data)
                    {
                        if($data['value'] > $max) $max = $data['value'];
                    }

                    $data  = array('name' => '', 'value' => array());
                    $value = array();
                    foreach($metrics as $key => $metric)
                    {
                        $indicator[]     = array('name' => $metric['name'], 'max' => $max);
                        $data['value'][] = $metric['value'];
                        $value[]         = $metric['value'];
                    }
                    $seriesData[] = $data;
                }

                $component->option->dataset->radarIndicator   = $indicator;
                $component->option->radar->indicator          = $indicator;
                $component->option->dataset->seriesData       = $seriesData;
                $component->option->series[0]->data[0]->value = $value;
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Build org chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildOrgChart($component, $chart)
    {
        //TODO
    }

    /**
     * Build funnel chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildFunnelChart($component, $chart)
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "Funnel";
            $component->chartConfig = json_decode('{"key":"Funnel","chartKey":"VFunnel","conKey":"VCFunnel","title":"漏斗图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"echarts","image":"/static/png/funnel-d032fdf6.png"}');
            $component->option      = json_decode('{"dataset":{"dimensions":["product","dataOne"],"source":[{"product":"data1","dataOne":20},{"product":"data2","dataOne":40},{"product":"data3","dataOne":60},{"product":"data4","dataOne":80},{"product":"data5","dataOne":100}]},"series":[{"name":"Funnel","type":"funnel","gap":5,"label":{"show":true,"position":"inside"}}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * Get burn data.
     *
     * @access public
     * @return array
     */
    public function getBurnData()
    {
        $type = 'withdelay';
        $this->loadModel('execution');
        $executions    = $this->execution->getList(0, 'sprint', 'doing') + $this->execution->getList(0, 'stage', 'doing');

        $executionData = array();

        foreach($executions as $executionID => $execution)
        {
            $execution = $this->execution->getByID($executionID);

            /* Splice project name for the execution name. */
            $execution->name = $this->loadModel('project')->getByID($execution->project)->name . '--' . $execution->name;

            /* Get date list. */
            if(((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
                or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
                or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
                and strpos($type, 'delay') === false)
                $type .= ',withdelay';

            $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
            $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
            $endDate  = (strpos($type, 'withdelay') !== false and $deadline > $execution->end) ? $deadline : $execution->end;
            list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $deadline);

            $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
            $chartData = $this->execution->buildBurnData($executionID, $dateList, 'left', $executionEnd);

            $execution->chartData = $chartData;
            $executionData[$executionID] = $execution;
        }
        return $executionData;
    }

    /**
     * Init component.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @access public
     * @return void
     */
    public function initComponent($chart, $type, $component = null)
    {
        if($type == 'metric') return $this->initMetricComponent($chart, $component);
        if($type == 'chart' || $type == 'pivot') return $this->initChartAndPivotComponent($chart, $type, $component);

        return array($component, false);
    }

    /**
     * Init metric component.
     *
     * @param  object $metric
     * @param  object $component
     * @access public
     * @return array
     */
    public function initMetricComponent($metric, $component = null)
    {
        if(!$component)                     $component = new stdclass();
        if(!isset($component->id))          $component->id          = $metric->id;
        if(!isset($component->sourceID))    $component->sourceID    = $metric->id;
        if(!isset($component->title))       $component->title       = $metric->name;
        if(!isset($component->type))        $component->type        = 'metric';
        if(!isset($component->chartConfig)) $component->chartConfig = json_decode($this->config->screen->chartConfig['metric']);
        if(!isset($component->option))      $component->option      = new stdclass();

        return array($component, false);
    }

    /**
     * Init chart or pivot component.
     *
     * @param  object $metric
     * @param  string $type
     * @param  object $component
     * @access public
     * @return array
     */
    public function initChartAndPivotComponent($chart, $type, $component = null)
    {
        if(!$component) $component = new stdclass();
        if(!$chart) return array($component, false);

        $chartID   = $chart->id;
        $chartName = $chart->name;
        $settings  = is_string($chart->settings) ? json_decode($chart->settings) : $chart->settings;
        $builtin   = $chart->builtin;
        $isBuiltin = ($builtin and !in_array($chartID, $this->config->screen->builtinChart));

        if(!isset($component->id))       $component->id       = $chartID;
        if(!isset($component->sourceID)) $component->sourceID = $chartID;
        if(!isset($component->title))    $component->title    = $chartName;

        if($type == 'chart')  $chartType = ($chart->builtin and !in_array($chart->id, $this->config->screen->builtinChart)) ? $chart->type : $settings[0]->type;
        if($type == 'pivot')  $chartType = 'table';
        if($type == 'metric') $chartType = 'metric';
        $component->type = $chartType;

        $typeChanged = false;

        // Get type is changed or not.
        if(isset($component->chartConfig))
        {
            $componentType = $chartType;
            foreach($this->config->screen->chartConfig as $type => $chartConfig)
            {
                $chartConfig = json_decode($chartConfig, true);
                if($chartConfig['key'] == $component->chartConfig->key) $componentType = $type;
            }

            $typeChanged = $chartType != $componentType;
        }

        // New component type or change component type.
        if(!isset($component->chartConfig) or $typeChanged)
        {
            $chartConfig = json_decode(zget($this->config->screen->chartConfig, $chartType));
            if(empty($chartConfig)) return null;

            $component->chartConfig = $chartConfig;
            $component->key         = $chartConfig->key;
        }

        if(!isset($component->option) or $typeChanged)
        {
            $component->option          = new stdclass();
            $component->option->dataset = new stdclass();
        }
        $component = $this->initOptionTitle($component, $type, $chartName);
        if(!isset($component->option->dataset)) $component->option->dataset = new stdclass();

        $component->chartConfig->title    = $chartName;
        $component->chartConfig->sourceID = $component->sourceID;
        if($component->type != 'metric') $component->chartConfig->version = $chart->version;

        return array($component, $typeChanged);
    }

    public function initOptionTitle($component, $type, $chartName)
    {
        if($type == 'pivot')
        {
            if(!isset($component->option->caption)) $component->option->caption = $chartName;
        }
        elseif($type == 'chart')
        {
            if(!isset($component->option->title))
            {
                $component->option->title = new stdclass();
                $component->option->title->text      = $chartName;
                $component->option->title->show      = false;
                $component->option->title->titleShow = true;
            }
        }

        return $component;
    }

    /**
     * Check if the Chart is in use.
     *
     * @param  int    $chartID
     * @param  string $type
     * @access public
     * @return void
     */
    public function checkIFChartInUse($chartID, $type = 'chart')
    {
        static $screenList = array();
        if(empty($screenList)) $screenList = $this->dao->select('scheme')->from(TABLE_SCREEN)->where('deleted')->eq(0)->andWhere('status')->eq('published')->fetchAll();

        foreach($screenList as $screen)
        {
            $scheme = json_decode($screen->scheme);
            if(empty($scheme->componentList)) continue;

            foreach($scheme->componentList as $component)
            {
                if(!empty($component->isGroup))
                {
                    foreach($component->groupList as $key => $groupComponent)
                    {
                        if(!isset($groupComponent->chartConfig)) continue;

                        $sourceID   = zget($groupComponent->chartConfig, 'sourceID', '');
                        $package    = zget($groupComponent->chartConfig, 'package', '');
                        $sourceType = $this->getChartType($package);

                        if($chartID == $sourceID and $type == $sourceType) return true;
                    }
                }
                else
                {
                    if(!isset($component->chartConfig)) continue;

                    $sourceID   = zget($component->chartConfig, 'sourceID', '');
                    $package    = zget($component->chartConfig, 'package', '');
                    $sourceType = $this->getChartType($package);
                    if($chartID == $sourceID and $type == $sourceType) return true;
                }

            }
        }
        return false;
    }

    /**
     * Get chart type.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function getChartType($type)
    {
        if($type == 'Tables' || $type == 'pivot') return 'pivot';
        if($type == 'Metrics') return 'metric';
        return 'chart';
    }

    /**
     * 构建大屏图表的数据源。
     * Build dataset of chart.
     *
     * @param  int    $chartID
     * @param  string $sql
     * @access public
     * @return array
     */
    public function buildDataset($chartID, $driver, $sql = '')
    {
        if(in_array($chartID, $this->config->screen->phpChart)) return $this->getDatasetForUsageReport($chartID);
        return $this->bi->queryWithDriver($driver, $sql);
    }

    /**
     * 获取应用健康度体检报告的数据源。
     * Build dataset for usage report.
     *
     * @param  int    $chartID
     * @access public
     * @return array
     */
    public function getDatasetForUsageReport($chartID)
    {
        $year  = $this->filter->year;
        $month = $this->filter->month;

        $projectList = $this->getUsageReportProjects($year, $month);
        $productList = $this->getUsageReportProducts($year, $month);

        if($chartID == 20002) return $this->getActiveUserTable($year, $month, $projectList);
        if($chartID == 20012) return $this->getProductStoryTable($year, $month, $productList);
        if($chartID == 20011) return $this->getProductTestTable($year, $month, $productList);
        if($chartID == 20004) return $this->getActiveProductCard($year, $month);
        if($chartID == 20007) return $this->getActiveProjectCard($year, $month);
        if($chartID == 20013) return $this->getProjectStoryTable($year, $month, $projectList);
        if($chartID == 20010) return $this->getProjectTaskTable($year, $month, $projectList);
    }

    /**
     * 获取应用健康度体检报告的活跃账号数项目间对比表格。
     * Get table of active account per project in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getActiveUserTable($year, $month, $projectList)
    {
        $date = date("Y-m-t", strtotime("$year-$month"));

        $loginUserList = $this->dao->select('distinct actor')->from(TABLE_ACTION)
            ->where('objectType')->eq('user')
            ->andWhere('action')->eq('login')
            ->andWhere('year(date)')->eq($year)
            ->andWhere('month(date)')->eq($month)
            ->fetchPairs();

        $dataset = array();
        foreach($projectList as $projectID => $projectName)
        {
            $teamMemberList = $this->dao->select('t2.id, t2.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
                ->where('t1.root')->eq($projectID)
                ->andWhere('t1.type')->eq('project')
                ->andWhere('date(t1.join)')->le($date)
                ->andWhere('t2.deleted')->eq('0')
                ->fetchPairs();

            $activeUser = array_filter($teamMemberList, function($item) use ($loginUserList)
            {
                return in_array($item, $loginUserList);
            });

            $row = new stdclass();
            $row->id            = $projectID;
            $row->name          = $projectName;
            $row->year          = $year;
            $row->month         = $month;
            $row->totalAccount  = count($teamMemberList);
            $row->activeAccount = count($activeUser);
            $row->ratio         = $row->totalAccount == 0 ? '0.00%' : number_format(($row->activeAccount/$row->totalAccount) * 100, 2) . '%';

            $dataset[] = $row;
        }

        return $dataset;
    }

    /**
     * 获取应用健康度体检报告的活跃项目数卡片。
     * Get card of active project in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getActiveProjectCard($year, $month)
    {
        return $this->dao->select('count(distinct t1.project) as count')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.project')->ne(0)
            ->andWhere('year(t1.date)')->eq($year)
            ->andWhere('month(t1.date)')->eq($month)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->fetchAll();
    }

    /**
     * 获取应用健康度体检报告的活跃产品数卡片。
     * Get card of active product in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getActiveProductCard($year, $month)
    {
        $noDeletedProductList = $this->dao->select('id')->from(TABLE_PRODUCT)
            ->where('deleted')->eq('0')
            ->andWhere('shadow')->eq(0)
            ->fetchPairs();

        $activeProductList = $this->dao->select('distinct product')->from(TABLE_ACTION)
            ->where('product')->ne(',0,')
            ->andWhere('product')->ne(',,')
            ->andWhere('product')->ne(',,0,,')
            ->andWhere('objectType')->notin('project,execution,task')
            ->andWhere('year(date)')->eq($year)
            ->andWhere('month(date)')->eq($month)
            ->fetchPairs();

        $activeProductCount = 0;
        foreach($activeProductList as $product)
        {
            $productID = trim($product, ',');
            if(in_array($productID, $noDeletedProductList)) $activeProductCount ++;
        }

        $activeProductCard = new stdclass();
        $activeProductCard->count = $activeProductCount;
        $activeProductCard->year  = $year;
        $activeProductCard->month = $month;
        return array($activeProductCard);
    }

    /**
     * 获取应用健康度体检报告的 产品测试表。
     * Get table of product test summary in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $productList
     * @access public
     * @return array
     */
    public function getProductTestTable($year, $month, $productList)
    {
        $dataset = array();
        foreach($productList as $productID => $productName)
        {
            $createdCaseCount = $this->dao->select('count(t2.id) as count')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.id=t2.product')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('year(t2.openedDate)')->eq($year)
                ->andWhere('month(t2.openedDate)')->eq($month)
                ->andWhere('t1.id')->eq($productID)
                ->fetch();

            $linkedBugCount = $this->dao->select('count(t3.id) as count')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.id=t2.product')
                ->leftJoin(TABLE_BUG)->alias('t3')->on('t2.id=t3.case')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t3.deleted')->eq('0')
                ->andWhere('year(t2.openedDate)')->eq($year)
                ->andWhere('month(t2.openedDate)')->eq($month)
                ->andWhere('t1.id')->eq($productID)
                ->fetch();

            $createdBugCount = $this->dao->select('count(t2.id) as count')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.id=t2.product')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('year(t2.openedDate)')->eq($year)
                ->andWhere('month(t2.openedDate)')->eq($month)
                ->andWhere('t1.id')->eq($productID)
                ->fetch();

            $fixedBugList = $this->dao->select('t2.id,datediff(t2.closedDate, t2.openedDate) as fixedCycle')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.id=t2.product')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.status')->eq('closed')
                ->andWhere('t2.resolution')->eq('fixed')
                ->andWhere('year(t2.closedDate)')->eq($year)
                ->andWhere('month(t2.closedDate)')->eq($month)
                ->andWhere('t1.id')->eq($productID)
                ->fetchPairs();

            $row = new stdclass();
            $row->id            = $productID;
            $row->name          = $productName;
            $row->year          = $year;
            $row->month         = $month;
            $row->createdCases  = $createdCaseCount->count;
            $row->avgBugsOfCase = $createdCaseCount->count == 0 ? 0 : round($linkedBugCount->count/$createdCaseCount->count, 2);
            $row->createdBugs   = $createdBugCount->count;
            $row->fixedBugs     = count($fixedBugList);
            $row->avgFixedCycle = count($fixedBugList) == 0 ? 0 : round(array_sum($fixedBugList)/count($fixedBugList), 2);

            if($row->createdCases === 0 && $row->avgBugsOfCase === 0 && $row->createdBugs === 0 && $row->fixedBugs === 0 && $row->avgFixedCycle === 0) continue;
            $dataset[] = $row;
        }

        return $dataset;
    }

    /**
     * 获取应用健康度体检报告的项目任务概况表。
     * Get table of project task summary in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getProjectTaskTable($year, $month, $projectList)
    {
        $deletedExecutionList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('deleted')->eq('1')
            ->andWhere('type')->in('sprint,stage,kanban')
            ->fetchPairs();

        $dataset = array();
        foreach($projectList as $projectID => $projectName)
        {
            $createdTaskList = $this->dao->select('id,openedBy')->from(TABLE_TASK)
                ->where('project')->eq($projectID)
                ->andWhere('year(openedDate)')->eq($year)
                ->andWhere('month(openedDate)')->eq($month)
                ->andWhere('deleted')->eq('0')
                ->andWhere("NOT FIND_IN_SET('or', vision)")
                ->andWhere('execution')->notin($deletedExecutionList)
                ->fetchPairs();

            $finishedTaskList = $this->dao->select('id')->from(TABLE_TASK)
                ->where('project')->eq($projectID)
                ->andWhere('year(finishedDate)')->eq($year)
                ->andWhere('month(finishedDate)')->eq($month)
                ->andWhere('deleted')->eq('0')
                ->andWhere("NOT FIND_IN_SET('or', vision)")
                ->andWhere('execution')->notin($deletedExecutionList)
                ->fetchPairs();

            $row = new stdclass();
            $row->name          = $projectName;
            $row->year          = $year;
            $row->month         = $month;
            $row->createdTasks  = count(array_unique(array_keys($createdTaskList)));
            $row->finishedTasks = count(array_unique($finishedTaskList));
            $row->contributors  = count(array_unique(array_values($createdTaskList)));

            if($row->createdTasks === 0 && $row->finishedTasks === 0 && $row->contributors === 0) continue;
            $dataset[] = $row;
        }

        return $dataset;
    }

    /**
     * 获取应用健康度体检报告的产品需求概况表。
     * Get table of product story summary in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $productList
     * @access public
     * @return array
     */
    public function getProductStoryTable($year, $month, $productList)
    {
        $releasedStories = $this->dao->select('t2.id, t1.id as product')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.id=t2.product')
            ->leftJoin(TABLE_ACTION)->alias('t3')->on('t2.id=t3.objectID')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('story')
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->andWhere('t2.stage')->eq('released')
            ->andWhere('t3.objectType')->eq('story')
            ->andWhere('t3.action')->eq('linked2release')
            ->andWhere('year(t3.date)')->eq($year)
            ->andWhere('month(t3.date)')->eq($month)
            ->fetchPairs();

        $releasedStoryGroups = array();
        foreach($releasedStories as $storyID => $productID)
        {
            if(!isset($releasedStoryGroups[$productID])) $releasedStoryGroups[$productID] = array();
            $releasedStoryGroups[$productID][] = $storyID;
        }

        $dataset = array();
        foreach($productList as $productID => $productName)
        {
            $createdStoryCount = $this->dao->select('count(t2.id) as count')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.id=t2.product')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.type')->eq('story')
                ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
                ->andWhere('t1.id')->eq($productID)
                ->andWhere('year(t2.openedDate)')->eq($year)
                ->andWhere('month(t2.openedDate)')->eq($month)
                ->fetch();

            $finishedStoryList = $this->dao->select('t2.id')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.id=t2.product')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.type')->eq('story')
                ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
                ->andWhere('t1.id')->eq($productID)
                ->andWhere('t2.closedReason')->eq('done')
                ->andWhere('year(t2.closedDate)')->eq($year)
                ->andWhere('month(t2.closedDate)')->eq($month)
                ->fetchPairs();

            $releasedStoryList = isset($releasedStoryGroups[$productID]) ? $releasedStoryGroups[$productID] : array();
            $deliveredStoryCount = count(array_merge($finishedStoryList, (array)$releasedStoryList));

            $row = new stdclass();
            $row->id               = $productID;
            $row->name             = $productName;
            $row->createdStories   = $createdStoryCount->count;
            $row->deliveredStories = $deliveredStoryCount;

            if($row->createdStories === 0 && $row->deliveredStories === 0) continue;
            $dataset[] = $row;
        }

        return $dataset;
    }

    /**
     * 获取应用健康度体检报告的项目需求概况表。
     * Get table of project story summary in usage report.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getProjectStoryTable($year, $month, $projectList)
    {
        $releasedStories = $this->dao->select('t3.id,t1.id as projectID')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.project')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story=t3.id')
            ->leftJoin(TABLE_ACTION)->alias('t4')->on('t3.id=t4.objectID')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t3.type')->eq('story')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere('t3.stage')->eq('released')
            ->andWhere('t4.objectType')->eq('story')
            ->andWhere('t4.action')->eq('linked2release')
            ->andWhere('year(t4.date)')->eq($year)
            ->andWhere('month(t4.date)')->eq($month)
            ->fetchPairs();

        $releasedStoryGroups = array();
        foreach($releasedStories as $storyID => $projectID)
        {
            if(!isset($releasedStoryGroups[$projectID])) $releasedStoryGroups[$projectID] = array();
            $releasedStoryGroups[$projectID][] = $storyID;
        }

        $dataset = array();
        foreach($projectList as $projectID => $projectName)
        {
            $createdStoryCount = $this->dao->select('count(t3.id) as count')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.project')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story=t3.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t1.type')->eq('project')
                ->andWhere('t3.type')->eq('story')
                ->andWhere('t3.deleted')->eq('0')
                ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
                ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
                ->andWhere('t1.type')->eq('project')
                ->andWhere('t3.type')->eq('story')
                ->andWhere('t1.id')->eq($projectID)
                ->andWhere('year(t3.openedDate)')->eq($year)
                ->andWhere('month(t3.openedDate)')->eq($month)
                ->fetch();

            $finishedStoryList = $this->dao->select('t3.id')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.project')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story=t3.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t1.type')->eq('project')
                ->andWhere('t3.deleted')->eq('0')
                ->andWhere('t3.type')->eq('story')
                ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
                ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
                ->andWhere('t1.id')->eq($projectID)
                ->andWhere('t3.closedReason')->eq('done')
                ->andWhere('year(t3.closedDate)')->eq($year)
                ->andWhere('month(t3.closedDate)')->eq($month)
                ->fetchPairs();

            $releasedStoryList = isset($releasedStoryGroups[$projectID]) ? $releasedStoryGroups[$projectID] : array();
            $deliveredStoryCount = count(array_merge($finishedStoryList, (array)$releasedStoryList));

            $row = new stdclass();
            $row->id               = $projectID;
            $row->name             = $projectName;
            $row->createdStories   = $createdStoryCount->count;
            $row->deliveredStories = $deliveredStoryCount;

            if($row->createdStories === 0 && $row->deliveredStories === 0) continue;
            $dataset[] = $row;
        }

        return $dataset;
    }

    /**
     * 获取应用健康度体检报告的项目列表。
     * Get project list for usage report.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getUsageReportProjects($year, $month)
    {
        $date = date("Y-m-t", strtotime("$year-$month"));

        return $this->dao->select('id,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq('0')
            ->andWhere('date(openedDate)')->le($date)
            ->andWhere("NOT FIND_IN_SET('or', vision)")
            ->andWhere('date(closedDate)', true)->gt($date)
            ->orWhere('date(closedDate)')->eq('0000-00-00')
            ->orWhere('closedDate')->in(NULL)
            ->markRight(true)
            ->fetchPairs();
    }

    /**
     * 获取应用健康度体检报告的产品列表。
     * Get product list for usage report.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getUsageReportProducts($year, $month)
    {
        $date = date("Y-m-t", strtotime("$year-$month"));

        return $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq('0')
            ->andWhere('shadow')->eq(0)
            ->andWhere('date(createdDate)')->le($date)
            ->fetchPairs();
    }

    /**
     * Get screen thumbnail.
     *
     * @param  array  $screens
     * @access public
     * @return array
     */
    public function getThumbnail($screens)
    {
        $screenIds = array_column($screens, 'id');
        $images = $this->loadModel('file')->getByObject('screen', $screenIds);
        foreach($screens as $screen)
        {
            $currentImages = array_filter($images, function($image) use ($screen)
            {
                return $image->objectID == $screen->id;
            });
            if(empty($currentImages)) continue;

            $image = end($currentImages);
            $screen->cover = helper::createLink('file', 'read', "fileID={$image->id}", 'png');
        }

        return $screens;
    }

    /**
     * Remove scheme field of screen.
     *
     * @param  array  $screens
     * @access public
     * @return array
     */
    public function removeScheme($screens)
    {
        foreach($screens as $screen) unset($screen->scheme);

        return $screens;
    }
}
