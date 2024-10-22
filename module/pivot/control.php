<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class pivot extends control
{
    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->dao->exec("SET @@sql_mode=''");
    }

    /**
     * 透视表首页，跳转到访问透视表页面。
     * The index of pivot, goto preview.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('preview'));
    }

    /**
     * 访问透视表。
     * Preview a pivot.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function preview(int $dimensionID = 0, int $groupID = 0, string $method = '', string $params = '')
    {
        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);
        if(!$groupID) $groupID = $this->pivot->getFirstGroup($dimensionID);
        $params = helper::safe64Decode($params);

        if(!$method) list($method, $params) = $this->getDefaultMethodAndParams($dimensionID, $groupID);

        if($method && $method != 'show' && !common::hasPriv('pivot', $method)) $this->loadModel('common')->deny('pivot', $method);

        parse_str($params, $result);

        if(method_exists($this->pivotZen, $method)) call_user_func_array(array($this->pivotZen, $method), $result);
        $this->session->set('backDimension', $dimensionID);
        $this->session->set('backGroup', $groupID);

        if(!$this->view->title) $this->view->title = $this->lang->pivot->preview;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 1, 'pivot');
        $this->view->menus       = $this->getSidebarMenus($dimensionID, $groupID, $method, $params);
        $this->view->recTotal    = count($this->getMenuItems($this->view->menus));
        $this->view->dimensionID = $dimensionID;
        $this->view->groupID     = $groupID;
        $this->view->method      = $method;
        $this->view->params      = $params;

        $this->display();
    }

    /**
     * Drill data modal.
     * 下钻数据的弹窗。
     *
     * @param  int    $pivotID
     * @param  string $colName
     * @param  string $status
     * @param  string $drillFields
     * @param  string $filterValues
     * @param  string $value
     * @access public
     * @return void
     */
    public function drillModal(int $pivotID, string $colName, string $status, string $conditions, string $filterValues, string $value)
    {
        $drill        = $this->pivotZen->getDrill($pivotID, $colName, $status);
        $conditions   = json_decode(base64_decode($conditions), true);
        $filterValues = json_decode(base64_decode($filterValues), true);

        $mergeConditions = array();
        foreach($drill->condition as $index => $condition)
        {
            $condition['value'] = $conditions[$index];
            $mergeConditions[] = $condition;
        }

        $pivot      = $this->pivot->getByID($pivotID);
        $pivotState = $this->pivotZen->initPivotState($pivot, $status == 'design');
        $cols       = $this->pivot->getDrillCols($drill->object);
        $datas      = $value == 0 ? array() : $this->pivot->getDrillDatas($pivotState, $drill, $mergeConditions, $filterValues);

        if(strpos(',story,task,bug,', ",{$drill->object},") !== false) $datas = $this->pivot->processKanbanDatas($drill->object, $datas);

        $this->view->title = $this->lang->pivot->stepDrill->drillView;
        $this->view->cols  = $cols;
        $this->view->datas = $datas;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * AJAX: 获取系统数据下拉选项。
     * AJAX: get sys options.
     *
     * @param  int $pivotID
     * @param  string $field
     * @param  string $saveAs
     * @param  string $values
     * @param  string $search
     * @param  int $limit
     * @access public
     * @return void
     */
    public function ajaxGetSysOptions(int|string $pivotID, string $field = '', string $saveAs = '', string $values = '', string $search = '', int $limit = 100)
    {
        if(!empty($field))
        {
            $pivot        = $this->pivot->getByID($pivotID);
            $fieldSetting = $pivot->fieldSettings->$field;
            $options      = $this->pivot->getSysOptions($fieldSetting->type, $fieldSetting->object, $fieldSetting->field, $pivot->sql, $saveAs);
        }
        else
        {
            $typeOption = $pivotID;
            $options = $this->pivot->getSysOptions($typeOption);
        }

        /* 根据关键字过滤选项。*/
        /* Filter options by keywords. */
        $limitOptions = $options;
        if(!empty($search))
        {
            foreach($limitOptions as $key => $text)
            {
                if(strpos($text, $search) === false) unset($limitOptions[$key]);
            }
        }

        /* 根据限制数量过滤选项。*/
        /* Filter options by limit. */
        $limitOptions = array_slice($limitOptions, 0, $limit, true);

        /* 添加默认值到选项列表。*/
        /* Add default value to options. */
        if(!empty($values))
        {
            $values = explode(',', $values);
            foreach($values as $value)
            {
                if(!isset($limitOptions[$value])) $limitOptions[$value] = $options[$value];
            }
        }

        /* 转换为value text格式。*/
        /* Convert to value text format. */
        $valueTextList = array();
        foreach($limitOptions as $value => $text)
        {
            $valueTextList[] = array('value' => $value, 'text' => $text);
        }

        echo json_encode($valueTextList);
    }
}
