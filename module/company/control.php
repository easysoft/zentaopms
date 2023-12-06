<?php
/**
 * The control file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: control.php 5100 2013-07-12 00:25:23Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
class company extends control
{
    /**
     * 初始化函数，自动加载dept模块。
     * Construct function, load dept model auto.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('dept');
    }

    /**
     * 首页，跳转到公司部门和人员浏览页面。
     * Index page, header to browse.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * 浏览公司部门和人员。
     * Browse departments and users of a company.
     *
     * @param  string     $browseType
     * @param  string|int $param
     * @param  string     $type
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function browse(string $browseType = 'inside', string|int $param = 0, string $type = 'bydept', string $orderBy = 'id_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $deptID = $type == 'bydept' ? (int)$param : 0;

        /* Save session. */
        $this->session->set('userList', $this->app->getURI(true), 'admin');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Build the search form. */
        $queryID   = $type == 'bydept' ? 0 : (int)$param;
        $actionURL = inlink('browse', "browseType=all&param=myQueryID&type=bysearch");
        $this->company->buildSearchForm($queryID, $actionURL);

        /* Get users. */
        $users = $this->company->getUsers($browseType, $type, $queryID, $deptID, $sort, $pager);

        $this->view->title      = $this->lang->company->index . $this->lang->colon . $this->lang->dept->common;
        $this->view->users      = array_map(function($user){unset($user->password);return $user;}, $users);
        $this->view->deptTree   = $this->dept->getTreeMenu(0, array('deptModel', 'createMemberLink'));
        $this->view->orderBy    = $orderBy;
        $this->view->deptID     = $deptID;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->type       = $type;
        $this->view->browseType = $browseType;
        $this->display();
    }

    public function create()
    {
        if(!empty($_POST))
        {
            $this->company->create();
            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent.parent'));
        }

        $this->view->title    = $this->lang->company->common . $this->lang->colon . $this->lang->company->create;
        $this->view->position = $this->lang->company->create;

        $this->display();
    }

    /**
     * 编辑公司信息。
     * Edit a company.
     *
     * @access public
     * @return void
     */
    public function edit()
    {
        if(!empty($_POST))
        {
            $company = form::data($this->config->company->form->edit)
                ->stripTags('name')
                ->setIF($this->post->website == 'http://', 'website', '')
                ->setIF($this->post->backyard == 'http://', 'backyard', '')
                ->get();

            if(!$this->company->update($this->app->company->id, $company)) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Reset company in session. */
            $this->session->set('company', $this->company->getFirst());

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title   = $this->lang->company->common . $this->lang->colon . $this->lang->company->edit;
        $this->view->company = $this->company->getByID($this->app->company->id);
        $this->display();
    }

    /**
     * 访问公司主页。
     * View a company.
     *
     * @access public
     * @return void
     */
    public function view()
    {
        $this->view->title   = $this->lang->company->common . $this->lang->colon . $this->lang->company->view;
        $this->view->company = $this->company->getByID($this->app->company->id);
        $this->display();
    }

    /**
     * 公司动态。
     * Company dynamic.
     *
     * @param  string     $browseType
     * @param  string     $param
     * @param  int        $recTotal
     * @param  string     $date
     * @param  string     $direction   next|pre
     * @param  int        $userID
     * @param  string|int $productID
     * @param  string|int $projectID
     * @param  string|int $executionID
     * @param  string     $orderBy     date_deac|date_asc
     * @access public
     * @return void
     */
    public function dynamic(string $browseType = 'today', string $param = '', int $recTotal = 0, string $date = '', string $direction = 'next', int $userID = 0, string|int $productID = 0, string|int $projectID = 0, string|int $executionID = 0, string $orderBy = 'date_desc')
    {
        $this->loadModel('action');

        /* Save session.*/
        $this->companyZen->saveUriIntoSession();

        /* Append id for second sort. */
        if($direction == 'next') $orderBy = 'date_desc';
        if($direction == 'pre')  $orderBy = 'date_asc';

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $date    = empty($date) ? '' : date('Y-m-d', $date);

        /* Set product, project, execution and user. */
        $account = $this->companyZen->loadAllSearchModule($userID, $queryID);

        if($browseType != 'bysearch')
        {
            $actions = $this->action->getDynamic($account, $browseType, $orderBy, 50, $productID, $projectID, $executionID, $date, $direction);
        }
        else
        {
            $actions = $this->action->getDynamicBySearch($queryID, $orderBy, 50, $date, $direction);
        }

        /* 根据日期补充动态数据。*/
        /* Supplement action by date.*/
        $dateGroups = $this->action->buildDateGroup($actions, $direction, $orderBy);

        if(empty($recTotal)) $recTotal = !(empty($date) && $browseType == 'all') ? array_sum(array_map('count', $dateGroups)) : $this->action->getDynamicCount();

        /* Assign.*/
        $this->view->title       = $this->lang->company->common . $this->lang->colon . $this->lang->company->dynamic;
        $this->view->recTotal    = $recTotal;
        $this->view->browseType  = $browseType;
        $this->view->account     = $account;
        $this->view->productID   = $productID;
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->queryID     = $queryID;
        $this->view->orderBy     = $orderBy;
        $this->view->userID      = $userID;
        $this->view->param       = $param;
        $this->view->dateGroups  = $dateGroups;
        $this->view->direction   = $direction;
        $this->display();
    }

    /**
     * Ajax获取外部公司。
     * Ajax get outside company.
     *
     * @access public
     * @return void
     */
    public function ajaxGetOutsideCompany()
    {
        $companies = $this->company->getOutsideCompanies();
        $items = array();
        foreach($companies as $companyID => $companyName) $items[] = array('text' => $companyName, 'value' => $companyID, 'keys' => $companyName);
        return print(json_encode($items));
    }
}
