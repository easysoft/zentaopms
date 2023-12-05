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
        $this->checkShowGuide();

        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);

        $this->view->title       = $this->lang->screen->common;
        $this->view->screens     = $this->screen->getList($dimensionID);
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
    public function view(int $screenID, int $year = 0, int $dept = 0, string $account = '')
    {
        if($screenID == 3)
        {
            echo $this->fetch('report', 'annualData', "year={$year}&dept={$dept}&account={$account}");
            return;
        }

        if(empty($year)) $year = date('Y');

        $screen = $this->screen->getByID($screenID, $year, $dept, $account);

        $this->view->title  = $screen->name;
        $this->view->screen = $screen;

        if($screenID == 5)
        {
            $this->loadModel('execution');
            $this->view->executions = $this->screen->getBurnData();
            $this->view->date       = date('Y-m-d H:i:s');
            $this->display('screen', 'burn');
        }
        else
        {
            $this->view->year    = $year;
            $this->view->dept    = $dept;
            $this->view->account = $account;
            $this->display();
        }
    }

    /**
     * Ajax get chart.
     *
     * @access public
     * @return void
     */
    public function ajaxGetChart()
    {
        if(!empty($_POST))
        {
            $chartID   = $this->post->sourceID;
            $type      = $this->post->type;
            $queryType = isset($_POST['queryType']) ? $this->post->queryType : 'filter';
            $type      = ($type == 'Tables' || $type == 'pivot') ? 'pivot' : 'chart';
            $table     = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
            $chart     = $this->dao->select('*')->from($table)->where('id')->eq($chartID)->fetch();

            $filterFormat = '';
            if($queryType == 'filter')
            {
                $filterParams = json_decode($this->post->filters, true);
                $filters      = json_decode($chart->filters,      true);
                $mergeFilters = array();
                foreach($filters as $index => $filter)
                {
                    $default = $filterParams[$index]['default'] ?? null;
                    if(in_array($filter['type'], array('date', 'datetime')))
                    {
                        if(isset($filter['from']) && $filter['from'] == 'query')
                        {
                            if(is_numeric($default)) $default = date('Y-m-d H:i:s', $default / 1000);
                        }
                        else
                        {
                            $default = is_array($default) ? array('begin' => date('Y-m-d H:i:s', $default[0] / 1000), 'end' => date('Y-m-d H:i:s', $default[1] / 1000)) : array('begin' => '', 'end' => '');
                        }
                    }
                    $filter['default'] = $default;
                    $mergeFilters[] = $filter;
                }

                if($table == TABLE_PIVOT)
                {
                    list($chart->sql, $filterFormat) = $this->loadModel($type)->getFilterFormat($chart->sql, $mergeFilters);
                }
                else
                {
                    $filterFormat = $this->loadModel($type)->getFilterFormat($mergeFilters);
                }
            }

            $component = new stdclass();
            $this->screen->genComponentData($chart, $component, $type, $filterFormat);
            print(json_encode($component));
        }
    }
}
