<?php
/**
 * The model file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-02-08 17:15:54Z $
 * @link        https://www.zentao.net
 */
class screenModel extends model
{
    /**
     * Filters
     *
     * @var object
     * @access public
     */
    public $filter;

    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->filter = new stdclass();
    }

    /**
     * Get screen list.
     *
     * @param  int $dimensionID
     * @access public
     * @return array
     */
    public function getList($dimensionID)
    {
        return $this->dao->select('*')->from(TABLE_SCREEN)
            ->where('dimension')->eq($dimensionID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * Get screen by id.
     *
     * @param  int $screenID
     * @param  int $year
     * @param  int $dept
     * @param  string $account
     * @access public
     * @return object
     */
    public function getByID($screenID, $year, $dept, $account)
    {
        $this->filter = new stdclass();
        $this->filter->screen  = $screenID;
        $this->filter->year    = $year;
        $this->filter->dept    = $dept;
        $this->filter->account = $account;
        $this->filter->charts  = array();

        $screen = $this->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();
        $screen->chartData = $this->genChartData($screen, $year, $dept, $account);

        return $screen;
    }

    /**
     * Generate chartData of screen.
     *
     * @param  object $screen
     * @param  int $year
     * @param  int $dept
     * @param  string $account
     * @access public
     * @return object
     */
    public function genChartData($screen)
    {
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
        $componentList = json_decode(file_get_contents('/home/zhu/zcorp/screen/' . $screen->id . '.json'));
        if(empty($componentList)) $componentList = array();

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
        $chartData->requestGlobalConfig =  json_decode('{ "requestDataPond": [], "requestOriginUrl": "", "requestInterval": 30, "requestIntervalUnit": "second", "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } return $chartData; }');

        return $chartData;
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
            $components[] = $this->buildComponent($component);
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

        if(!$component->isGroup) return $this->setComponentDefaults($component);

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
        if(!isset($component->styles))  $component->styles  = json_decode('{"filterShow": false, "hueRotate": 0, "saturate": 1, "contrast": 1, "brightness": 1, "opacity": 1, "rotateZ": 0, "rotateX": 0, "rotateY": 0, "skewX": 0, "skewY": 0, "blendMode": "normal", "animations": []}');
        if(!isset($component->status))  $component->status  = json_decode('{"lock": false, "hide": false}');
        if(!isset($component->request)) $component->request = json_decode('{ "requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

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

                $begin = $this->dao->select('YEAR(MIN(date)) year')->from(TABLE_ACTION)->where('date')->ne('0000-00-00')->fetch('year');
                if($begin < 2009) $begin = 2009;

                $options = array();
                for($year = date('Y'); $year >= $begin; $year--) $options[] = array('label' => $year, 'value' => $year);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen. "&year=' + value + '&dept=" . $this->filter->dept . "&account=" . $this->filter->account . "')";
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
                return $this->buildLineChart($component, $chart);
                break;
            case 'bar':
                return $this->buildBarChart($component, $chart);
                break;
            case 'pie':
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
                        $conditions[] = $field . ' = ' . $this->filter->$key;
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
                    $results = $this->dao->query($sql)->fetchAll();

                    if($settings->value->type === 'value')
                    {
                        $value = $results[0]->$field;
                    }
                    if($settings->value->agg === 'count')
                    {
                        $value = count($results);
                    }
                    else if($settings->value->agg === 'sum')
                    {
                        foreach($results as $result)
                        {
                            $value += $result->$field;
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
                    $results = $this->dao->query($sql)->fetchAll();
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
                    $results = $this->dao->query($sql)->fetchAll();
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
                    $results = $this->dao->query($sql)->fetchAll();

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
                    $results = $this->dao->query($sql)->fetchAll();
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

                $component->option->dataset->dimensions = $dimensions;
                $component->option->dataset->source     = $sourceData;
            }

            return $this->setComponentDefaults($component);
        }
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
                    $results = $this->dao->query($sql)->fetchAll();
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
            $component->option      = json_decode('{"dataset":{"dimensions":["product","dataOne"],"source":[{"product":"data1","dataOne":20},{"product":"data2","dataOne":40},{"product":"data3","dataOne":60},{"product":"data4","dataOne":80},{"product":"data5","dataOne":100}]},"series":[{"name":"Funnel","type":"funnel","gap":5,"label":{"show":true,"position":"inside","fontSize":12}}],"backgroundColor":"rgba(0,0,0,0)"}');

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

            /* Get date list. */
            if(((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
                or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
                or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
                and strpos($type, 'delay') === false)
                $type .= ',withdelay';

            $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
            $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
            $endDate  = strpos($type, 'withdelay') !== false ? $deadline : $execution->end;
            list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $execution->end);

            $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
            $chartData = $this->execution->buildBurnData($executionID, $dateList, $type, 'left', $executionEnd);

            $execution->chartData = $chartData;
            $executionData[$executionID] = $execution;
        }
        return $executionData;
    }
}
