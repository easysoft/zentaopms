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

        $cols  = $this->pivot->getDrillCols($drill->object);
        $datas = $value == 0 ? array() : $this->pivot->getDrillDatas($pivotID, $drill, $status, $mergeConditions, $filterValues);

        if(strpos(',story,task,bug,', ",{$drill->object},") !== false) $datas = $this->pivot->processKanbanDatas($drill->object, $datas);

        $this->view->title = $this->lang->pivot->step3->drillView;
        $this->view->cols  = $cols;
        $this->view->datas = $datas;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }
}
