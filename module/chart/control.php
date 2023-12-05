<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chart extends control
{
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

        if(!$groupID) $groupID = $this->chart->getFirstGroup($dimensionID);

        $charts = array();
        if($_POST)
        {
            if($this->post->charts)
            {
                $chartChecked = count($this->post->charts);
                if($chartChecked > $this->config->chart->chartMaxChecked) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->chart->chartMaxChecked, $this->config->chart->chartMaxChecked)));

                /* 选中多个图表查看。*/
                /* Select multiple charts to view. */
                $charts = $this->getChartsToView($this->post->charts);
            }
            else if($this->post->groupID && $this->post->chartID && $this->post->filterValues)
            {
                /* 更改一个图表的过滤条件。*/
                /* Change filter conditions of a chart. */
                $chart = $this->getChartToFilter((int)$this->post->groupID, (int)$this->post->chartID, $this->post->filterValues);
                if($chart) $charts[] = $chart;
            }
        }

        if(!$charts) $charts = $this->chart->getDefaultCharts($groupID);

        $this->view->title       = $this->lang->chart->preview;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 1);
        $this->view->treeMenu    = $this->chart->getTreeMenu($groupID);
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
        $filters = $this->post->filters;

        /* 获取关联字段下拉菜单数据。*/
        /* Get the options for the related field in filter. */
        $fields = $this->chartZen->getOptions4SelectField();

        $htmls = array();
        foreach($filters as $filter)
        {
            $field = $filter['field'];
            $type  = $filter['type'];

            $filterHtml = array();

            /* field html */
            $filterHtml['field'] = html::select('field', $fields, $field, "class='form-control picker-select' onchange='initFilterForm(this, this.value)'");

            /* default html */
            $filterHtml['default'] = $this->chartZen->getHTML('default', $type, $filter);

            /* type html */
            $filterHtml['type'] = html::select('type', $this->lang->chart->fieldTypeList, $type, "class='form-control picker-select' onchange='changeType(this, this.value)'");

            /* filter item html */
            $filterHtml['item'] = $this->chartZen->getHTML('item', $type, $filter);

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
        }

        echo json_encode($data);
    }
}
