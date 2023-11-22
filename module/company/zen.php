<?php
declare(strict_types=1);
/**
 * The zen file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang XuePeng <wangxuepeng@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
class companyZen extends company
{
    /**
     * 将当前的URI保存到session中。
     * Save current uri into session.
     *
     * @access public
     * @return void
     */
    public function saveUriIntoSession()
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('riskList',        $uri, 'project');
        $this->session->set('opportunityList', $uri, 'project');
        $this->session->set('trainplanList',   $uri, 'project');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('effortList',      $uri, 'my');
        $this->session->set('meetingList',     $uri, 'my');
        $this->session->set('meetingList',     $uri, 'project');
        $this->session->set('meetingroomList', $uri, 'admin');
    }

    /**
     * 加载所有的搜索模块。
     * Load all search module.
     *
     * @param  int        $userID
     * @param  string|int $queryID
     * @access public
     * @return string
     */
    public function loadAllSearchModule(int $userID, string|int $queryID): string
    {
        return $this->buildDyanmicSearchForm($this->loadProduct(), $this->loadProject(), $this->loadExecution(), $userID, $queryID);
    }

    /**
     * 加载产品搜索模块。
     * Load product search module.
     *
     * @access public
     * @return array
     */
    public function loadProduct(): array
    {
        $products = $this->loadModel('product')->getPairs('nocode');
        $products = array($this->lang->company->product) + $products;
        $this->view->products = $products;

        return $products;
    }

    /**
     * 加载项目搜索模块。
     * Load project search module.
     *
     * @access public
     * @return array
     */
    public function loadProject(): array
    {
        $projects = $this->loadModel('project')->getPairsByProgram();
        $this->view->projects = array($this->lang->company->project) + $projects;;

        return $projects;
    }

    /**
     * 加载执行搜索模块。
     * Load execution search module.
     *
     * @access public
     * @return array
     */
    public function loadExecution(): array
    {
        $this->app->loadLang('execution');
        $executions    = $this->loadModel('execution')->getPairs(0, 'all', 'nocode|multiple');
        $executionList = $this->execution->getByIdList(array_keys($executions));
        foreach($executionList as $execution)
        {
            if(isset($projects[$execution->project])) $executions[$execution->id] = $projects[$execution->project] . $executions[$execution->id];
        }

        $executions = array($this->lang->execution->common) + $executions;
        $this->view->executions = $executions;

        return $executions;
    }

    /**
     * 加载用户模块。
     * Load user module.
     *
     * @access public
     * @param  int    $userID
     * @return array
     */
    public function loadUserModule(int $userID): array
    {
        $user    = $userID ? $this->loadModel('user')->getById($userID, 'id') : '';
        $account = $user ? $user->account : 'all';

        $userIdPairs = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter|useid');
        $userIdPairs[''] = $this->lang->company->user;
        $this->view->userIdPairs = $userIdPairs;

        $accountPairs = $this->user->getPairs('nodeleted|noletter|all');
        $accountPairs[''] = '';
        $this->view->accountPairs = $accountPairs;

        return array($account, $accountPairs);
    }

    /**
     * 构建动态搜索表单。
     * Build dynamic search form.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $userID
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function buildDyanmicSearchForm(array $products, array $projects, array $executions, int $userID, int $queryID): string
    {
        list($account, $accountPairs) = $this->loadUserModule($userID);

        $executions[0] = '';
        $products[0]   = '';
        $projects[0]   = '';
        ksort($executions);
        ksort($products);
        ksort($projects);
        $executions['all'] = $this->lang->execution->allExecutions;
        $products['all']   = $this->lang->product->allProduct;
        $projects['all']   = $this->lang->project->all;

        foreach(array_keys($this->lang->action->search->label) as $action)
        {
            if($action) $this->lang->action->search->label[$action] .= " [ $action ]";
        }

        $this->config->company->dynamic->search['actionURL'] = $this->createLink('company', 'dynamic', "browseType=bysearch&param=myQueryID");
        $this->config->company->dynamic->search['queryID'] = $queryID;
        $this->config->company->dynamic->search['params']['action']['values']    = $this->lang->action->search->label;
        if($this->config->vision == 'rnd') $this->config->company->dynamic->search['params']['product']['values']   = $products;
        $this->config->company->dynamic->search['params']['project']['values']   = $projects;
        $this->config->company->dynamic->search['params']['execution']['values'] = $executions;
        $this->config->company->dynamic->search['params']['actor']['values']     = $accountPairs;
        $this->loadModel('search')->setSearchParams($this->config->company->dynamic->search);

        return $account;
    }
}
