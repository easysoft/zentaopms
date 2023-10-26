<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * The index of pivot, goto project deviation.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('preview'));
    }

    /**
     * Preview a pivot.
     *
     * @param  int    $dimension
     * @param  string $group
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function preview($dimension = 0, $group = '', $module = 'pivot', $method = '', $params = '')
    {
        $dimension = $this->loadModel('dimension')->getDimension($dimension);
        $this->prepare4Preview($dimension, $group, $module, $method, $params);
        $this->display();
    }

    /**
     * Preview pivots of a group.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function show($dimensionID = 0, $groupID = 0, $pivotID = 0)
    {
        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);

        if(!$groupID)
        {
            $groupID = $this->dao->select('id')->from(TABLE_MODULE)
                ->where('deleted')->eq('0')
                ->andWhere('type')->eq('pivot')
                ->andWhere('root')->eq($dimensionID)
                ->andWhere('grade')->eq(1)
                ->orderBy('`order`')
                ->limit(1)
                ->fetch('id');
        }

        list($pivotTree, $pivot, $groupID) = $this->pivot->getPreviewPivots($dimensionID, $groupID, $pivotID);
        if($pivot)
        {
            list($sql, $filterFormat) = $this->pivot->getFilterFormat($pivot->sql, $pivot->filters);

            $processSqlData = $this->loadModel('chart')->getTables($sql);
            $sql = $processSqlData['sql'];

            list($data, $configs) = $this->pivot->genSheet(json_decode(json_encode($pivot->fieldSettings), true), $pivot->settings, $sql, $filterFormat, json_decode($pivot->langs, true));
            $this->view->data     = $data;
            $this->view->configs  = $configs;
        }

        $group = $this->loadModel('tree')->getByID($groupID);

        $this->view->title       = $this->lang->pivot->preview;
        $this->view->dimensionID = $dimensionID;
        $this->view->pivotTree   = $pivotTree;
        $this->view->pivot       = $pivot;
        $this->view->group       = $group;
        $this->view->parentGroup = $group->grade == 2 ? $this->tree->getByID($group->parent) : $group;
        $this->view->groups      = $this->tree->getGroupPairs($dimensionID, 0, 1, 'pivot');
        $this->display();
    }

    /**
     * Ajax get sys options.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return string
     */
    public function ajaxGetSysOptions($type, $object = '', $field = '')
    {
        $sql     = isset($_POST['sql'])     ? $_POST['sql']     : '';
        $filters = isset($_POST['filters']) ? $_POST['filters'] : '';

        $sql     = $this->loadModel('chart')->parseSqlVars($sql, $filters);
        $options = $this->pivot->getSysOptions($type, $object, $field, $sql);
        return print(html::select('default[]', $options, '', "class='form-control form-select picker-select' multiple"));
    }

    /**
     * Ajax get pivot table.
     *
     * @access public
     * @return void
     */
    public function ajaxGetPivot()
    {
        $post = fixer::input('post')
            ->skipSpecial('settings,filters,sql,langs')
            ->get();

        $postFilters = isset($_POST['filters']) ? $post->filters : array();
        $filters     = isset($_POST['searchFilters']) ? $post->searchFilters : $postFilters;

        $pivotID    = $post->id;
        $settings   = $post->settings;
        $filterType = 'result';

        list($sql, $filterFormat) = $this->pivot->getFilterFormat($post->sql, $filters);
        $post->sql = $sql;

        $settings['filterType'] = $filterType;

        $sql    = str_replace(';', '', "$post->sql");
        $fields = $post->fieldSettings;
        $langs  = isset($_POST['langs']) ? $post->langs : array();
        $langs  = is_array($langs) ? $langs : json_decode($langs, true);

        $processSqlData = $this->loadModel('chart')->getTables($sql);
        $sql = $processSqlData['sql'];

        list($data, $configs) = $this->pivot->genSheet($fields, $settings, $sql, $filterFormat, $langs);

        $this->pivot->buildPivotTable($data, $configs, $fields, $sql);
    }

}
