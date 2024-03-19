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

        $this->view->title       = $this->lang->pivot->preview;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 1, 'pivot');
        $this->view->menus       = $this->getSidebarMenus($dimensionID, $groupID, $method, $params);
        $this->view->dimensionID = $dimensionID;
        $this->view->groupID     = $groupID;
        $this->view->method      = $method;
        $this->view->params      = $params;
        $this->display();
    }

    /**
     * Ajax get sys options.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  string $saveAs
     * @access public
     * @return string
     */
    public function ajaxGetSysOptions($type, $object = '', $field = '', $saveAs = '')
    {
        $sql     = isset($_POST['sql'])     ? $_POST['sql']     : '';
        $filters = isset($_POST['filters']) ? $_POST['filters'] : '';

        $sql     = $this->loadModel('chart')->parseSqlVars($sql, $filters);
        $options = $this->pivot->getSysOptions($type, $object, $field, $sql, $saveAs);
        return print(html::select('default[]', array('' => '') + $options, '', "class='form-control form-select picker-select' multiple"));
    }

    /**
     * Ajax get pivot table.
     *
     * @access public
     * @return void
     */
    public function ajaxGetPivot()
    {
        $post = fixer::input('post')->skipSpecial('settings,filters,sql,langs')->get();

        $pivotID    = $post->id;
        $settings   = $post->settings;
        $filters    = isset($_POST['searchFilters']) ? $_POST['searchFilters'] : (isset($_POST['filters']) ? $post->filters : array());
        $page       = isset($_POST['page']) ? $_POST['page'] : 0;
        $filterType = 'result';

        $pivot = $this->pivot->getById($pivotID);

        list($sql, $filterFormat) = $this->pivot->getFilterFormat($post->sql, $filters);
        $post->sql = $sql;

        $settings['filterType'] = $filterType;

        $sql    = str_replace(';', '', "$post->sql");
        $fields = $post->fieldSettings;
        $langs  = isset($post->langs) ? (is_array($post->langs) ? $post->langs : json_decode($post->langs, true)) : array();

        $processSqlData = $this->loadModel('chart')->getTables($sql);
        $sql = $processSqlData['sql'];

        if(isset($settings['summary']) and $settings['summary'] == 'notuse')
        {
            list($data, $configs) = $this->pivot->genOriginSheet($fields, $settings, $sql, $filterFormat, $langs);
        }
        else
        {
            list($data, $configs) = $this->pivot->genSheet($fields, $settings, $sql, $filterFormat, $langs);
        }

        $this->pivot->buildPivotTable($data, $configs, $page);
    }
}
