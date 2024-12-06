<?php
/**
 * The control file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-11-18 17:15:54Z $
 * @link        https://www.zentao.net
 */
class screen extends control
{
    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->dao->exec("SET @@sql_mode=''");
    }
    /**
     * 浏览一个维度下的所有大屏。
     * Browse screens of a dimension.
     *
     * @param  int    $dimensionID
     * @access public
     * @return void
     */
    public function browse(int $dimensionID = 0)
    {
        //$this->checkShowGuide();
        $this->view->showGuide = false;

        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);

        $screens = $this->screen->getList($dimensionID);
        $screens = $this->screen->getThumbnail($screens);
        $screens = $this->screen->removeScheme($screens);
        $screens = $this->screenZen->prepareCardList($screens);

        $this->view->title       = $this->lang->screen->common;
        $this->view->screens     = $screens;
        $this->view->dimensionID = $dimensionID;
        $this->display();
    }

    /**
     * 检查是否需要显示 BI 新功能的引导页面。
     * Check if need to show the guide of BI new features.
     *
     * @access public
     * @return void
     */
    public function checkShowGuide()
    {
        $this->app->loadLang('admin');

        $isUpdate = $this->loadModel('setting')->getItem("owner=system&module=bi&key=update2BI");
        if(empty($isUpdate))
        {
            $this->view->showGuide = false;
            return;
        }

        $lang     = (strpos($this->app->getClientLang(), 'zh') !== false) ? 'zh' : 'en';
        $version  = ($this->config->edition == 'biz' || $this->config->edition == 'max') ? 'biz' : 'pms';
        $imageURL = "static/images/bi_guide_{$version}_{$lang}.png";

        $moduleKey = $version . 'Guide';
        $guides    = $this->setting->getItem("owner=system&module=bi&key={$moduleKey}");
        $haveSeen  = explode(',', $guides);
        $afterSeen = array_merge($haveSeen, array($this->app->user->account));
        $this->setting->setItem("system.bi.{$moduleKey}", implode(',', array_unique($afterSeen)));

        $this->view->showGuide = in_array($this->app->user->account, $haveSeen) ? false : true;
        $this->view->imageURL  = $imageURL;
        $this->view->version   = $version;
    }

    /**
     * 查看一个大屏。
     * View a screen.
     *
     * @param  int    $screenID
     * @param  int    $year
     * @param  int    $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function view(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = '')
    {
        $this->screen->checkAccess($screenID);

        if(empty($year))  $year  = date('Y');
        if(empty($month)) $month = date('m');

        if($screenID == 3)
        {
            echo $this->fetch('report', 'annualData', "year={$year}&dept={$dept}&account={$account}");
            return;
        }

        $screen = $this->screen->getByID($screenID, $year, $month, $dept, $account, false);

        $this->view->title    = $screen->name;
        $this->view->screenID = $screenID;

        if($screenID == 5)
        {
            $this->loadModel('execution');
            $this->view->executions = $this->screen->getBurnData();
            $this->view->date       = date('Y-m-d H:i:s');
            $this->view->screen     = $screen;
            $this->display('screen', 'burn');
            return;
        }

        $this->view->year     = $year;
        $this->view->month    = $month;
        $this->view->dept     = $dept;
        $this->view->account  = $account;
        $this->display();
    }

    /**
     * 查看一个大屏(旧页面方式)。
     * View a screen(old page).
     *
     * @param  int    $screenID
     * @param  int    $year
     * @param  int    $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function viewOld(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = '')
    {
        if(empty($year))  $year  = date('Y');
        if(empty($month)) $month = date('m');

        $screen = $this->screen->getByID($screenID, $year, $month, $dept, $account, false);

        $this->view->title   = $screen->name;
        $this->view->screen  = $screen;
        $this->view->year    = $year;
        $this->view->month   = $month;
        $this->view->dept    = $dept;
        $this->view->account = $account;
        $this->display();
    }

    public function ajaxGetScreenScheme(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = '')
    {
        $screen = $this->screen->getByID($screenID, $year, $month, $dept, $account);
        echo(json_encode($screen));
    }

    /**
     * 通过ajax获取图表数据。
     * Ajax get chart.
     *
     * @access public
     * @return void
     */
    public function ajaxGetChart(int $year = 0, int $month = 0, int $dept = 0, string $account = '')
    {
        if(!empty($_POST))
        {
            $sourceID = $this->post->sourceID;
            $type     = $this->post->type;

            if($type == 'Filters')
            {
                $filterComponent = $this->screen->genFilterComponent($sourceID);
                return print(json_encode($filterComponent));
            }

            $queryType    = isset($_POST['queryType']) ? $this->post->queryType : 'filter';
            $component    = isset($_POST['component']) ? json_decode($this->post->component) : null;
            $filterParams = isset($_POST['filters']) ? json_decode($this->post->filters, true) : array();
            $selectFilter = isset($_POST['selectFilter']) ? json_decode($this->post->selectFilter, true) : array();

            $this->screen->filter->year    = $year;
            $this->screen->filter->month   = $month;
            $this->screen->filter->dept    = $dept;
            $this->screen->filter->account = $account;
            $this->screen->setSelectFilter($sourceID, $selectFilter);

            $type = $this->screen->getChartType($type);

            if($type == 'metric')
            {
                $metric     = $this->loadModel('metric')->getByID($sourceID);
                $metricData = $this->screen->genMetricComponent($metric, $component, $filterParams);
                return print(json_encode($metricData));
            }

            if($type == 'pivot')
            {
                $chartOrPivot = $this->loadModel('pivot')->getPivotDataByID($sourceID);
            }
            else
            {
                $table = $this->config->objectTables[$type];
                $chartOrPivot = $this->dao->select('*')->from($table)->where('id')->eq($sourceID)->fetch();
            }

            $filterFormat = array();
            if($queryType == 'filter') list($chartOrPivot, $filterFormat) = $this->screen->mergeChartAndPivotFilters($type, $chartOrPivot, $sourceID, $filterParams);

            $component = $this->screen->genComponentData($chartOrPivot, $type, $component, $filterFormat);
            print(json_encode($component));
        }
    }

    /**
     * 获取度量数据。
     * Ajax get metric data.
     *
     * @access public
     * @return void
     */
    public function ajaxGetMetricData()
    {
        $metricID   = $this->post->metricID;
        list($pager, $pagination) = $this->screen->preparePaginationBeforeFetchRecords($_POST['pagination']);

        $metric = $this->loadModel('metric')->getByID($metricID);
        $result = $this->metric->getLatestResultByCode($metric->code, $_POST, $pager);

        $pagination['total']     = $pager->recTotal;
        $pagination['pageTotal'] = $pager->pageTotal;

        $metricData = new stdclass();
        $metricData->header     = $this->metric->getViewTableHeader($metric);
        $metricData->data       = $this->metric->getViewTableData($metric, $result);
        $metricData->pagination = $pagination;

        echo(json_encode($metricData));
    }

    public function ajaxGetFilterOptions()
    {
        $this->loadModel('metric');

        $type         = $_POST['type'];
        $params       = json_decode($_POST['params']);
        $query        = $_POST['query'];
        $defaultValue = $_POST['defaultValue'];

        $type = $this->screen->getChartType($type);

        if($type == 'metric')
        {
            $scope = $params->typeOption;
            $objectPairs = $this->metric->getPairsByScope($scope, true);
        }
        else
        {
            if(isset($params->typeOption))
            {
                $objectPairs = $this->screen->getSysOptions($params->typeOption);
            }
            else
            {
                $sourceId = $params->sourceID;
                $field    = $params->field;
                $saveAs   = isset($params->saveAs) ? $params->saveAs : '';

                $table = $this->config->objectTables[$type];
                $chart = $this->dao->select('*')->from($table)->where('id')->eq($sourceId)->fetch();
                $fields = json_decode($chart->fields, true);

                $fieldObj = zget($fields, $field);
                $objectPairs = $this->loadModel('pivot')->getSysOptions($fieldObj['type'], $fieldObj['object'], $fieldObj['field'], $chart->sql, $saveAs);
            }
        }

        $options = array_map(function($objectID, $objectName)
        {
            return array(
                'label' => $objectName,
                'value' => "$objectID"
            );
        }, array_keys($objectPairs), array_values($objectPairs));

        if(empty($query))
        {
            if(!empty($defaultValue))
            {
                $defaultValue = is_array($defaultValue) ? $defaultValue : explode(',', $defaultValue);
                $defaultOptions = array_filter($options, function($option) use($defaultValue)
                {
                    return in_array($option['value'], $defaultValue);
                });
            }

            // return limit 10 options.
            $options = array_slice($options, 0, 10);

            if(!empty($defaultOptions))
            {
                $uniqueOptions = array();
                foreach($options as $option)
                {
                    $findInDefault = array_filter($defaultOptions, function($defaultOption) use($option)
                    {
                        return $defaultOption['value'] == $option['value'];
                    });

                    if(empty($findInDefault))
                    {
                        $uniqueOptions[] = $option;
                    }
                }
                $options = array_merge($defaultOptions, $uniqueOptions);
            }

            echo(json_encode($options));
            return;
        }

        $options = array_filter($options, function($option) use($query)
        {
            return strpos(strtolower($option['label']), strtolower($query)) !== false;
        });

        $options = array_values($options);

        echo(json_encode($options));
    }
}
