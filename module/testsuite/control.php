<?php
declare(strict_types=1);
/**
 * The control file of testsuite module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package   testsuite
 * @link      https://www.zentao.net
 */
class testsuite extends control
{
    /**
     * 所有产品。
     * All products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * 构造函数
     *
     * 1.加载其他model类。
     * 2.获取产品，并输出到视图。
     *
     * The construct function.
     *
     * 1. Load model of other modules.
     * 2. Get products and assign to view.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->view->products = $this->products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(empty($this->products) && (helper::isAjaxRequest('zin') || helper::isAjaxRequest('fetch')))
        {
            $tab      = $this->app->tab == 'project' || $this->app->tab == 'execution' ? $this->app->tab : 'qa';
            $objectID = $tab == 'project' || $tab == 'execution' ? $this->session->{$tab} : 0;
            $this->locate($this->createLink('product', 'showErrorNone', "moduleName={$tab}&activeMenu=testsuite&objectID=$objectID"));
        }
    }

    /**
     * 索引页，导向browse页面。
     * Index page, header to browse.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->inlink('browse'));
    }

    /**
     * 测试套件列表。
     * Browse test suites.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $type = 'all', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 将当前URI存入SESSION。 */
        /* Save session. */
        $this->session->set('testsuiteList', $this->app->getURI(true), 'qa');

        /* 设置1.5级导航。 */
        /* Set level 1.5 navigation */
        $productID = $this->product->checkAccess($productID, $this->products);
        $this->loadModel('qa')->setMenu($productID);

        /* 初始化分页。 */
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 生成排序规则。 */
        /* Generate the sort rules. */
        $sort = common::appendOrder($orderBy);

        /* 根据产品名称获取套件，如果查询为空并且不在第一页，则初始化至当前页码到第一页。 */
        /* Get the package according to the product name, if the query is empty and not on the first page, initialize to the current page number to the first page. */
        $productName = isset($this->products[$productID]) ? $this->products[$productID] : '';
        $suites      = $this->testsuite->getSuites($productID, $sort, $pager, $type);
        if(empty($suites) && $pageID > 1)
        {
            $pager  = pager::init(0, $recPerPage, 1);
            $suites = $this->testsuite->getSuites($productID, $sort, $pager, $type);
        }

        /* 获取公共和私有的套件数量。 */
        /* Get the number of public and private suites. */
        $totalNum   = count($suites);
        $privateNum = count(array_filter($suites, function($suite){return $suite->type == 'private';}));
        $publicNum  = $totalNum - $privateNum;
        $summary    = sprintf($this->lang->testsuite->summary, $totalNum, $publicNum, $privateNum);

        $this->view->title   = $productName . $this->lang->testsuite->common;
        $this->view->orderBy = $orderBy;
        $this->view->suites  = $suites;
        $this->view->type    = $type;
        $this->view->summary = $summary;
        $this->view->pager   = $pager;
        $this->view->product = $this->product->getByID($productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * 创建一个测试套件。
     * Create a test suite.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create(int $productID)
    {
        if(!empty($_POST))
        {
            $suiteData = form::data($this->config->testsuite->form->create)
                ->setIF($this->lang->navGroup->testsuite != 'qa', 'project', $this->session->project)
                ->add('product', $productID)
                ->get();
            $suite = $this->loadModel('file')->processImgURL($suiteData, $this->config->testsuite->editor->create['id'], $this->post->uid);

            $suiteID = $this->testsuite->create($suite);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->file->updateObjectID($this->post->uid, $suiteID, 'testsuite');
            $message = $this->executeHooks($suiteID) ? : $this->lang->testsuite->successSaved;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $suiteID));
            return $this->send(array('result' => 'success', 'message' => $message, 'locate' => $this->inlink('browse', "productID=$productID")));
        }

        /* 设置1.5级导航。 */
        /* Set level 1.5 navigation */
        $productID  = $this->product->checkAccess($productID, $this->products);
        $this->loadModel('qa')->setMenu($productID);

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->testsuite->create;
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * 查看一个测试套件详情。
     * View a test suite.
     *
     * @param  int    $suiteID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $suiteID, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        /* 检查用户访问权限。 */
        /* Check user access rights. */
        $suite = $this->testsuiteZen->checkTestsuiteAccess($suiteID);

        /* 设置1.5级导航。 */
        /* Set level 1.5 navigation */
        $productID = $this->product->checkAccess($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($productID);

        /* 当前URI存入session。 */
        /* Sava the uri of current page into session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* 生成排序规则。 */
        /* Generate the sort rules. */
        $sort = common::appendOrder($orderBy);

        /* 初始化该套件关联用例的分页器。 */
        /* Initalizes the paginator for this suites's associated use case. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->executeHooks($suiteID);

        $this->view->title        = "SUITE #$suite->id $suite->name";
        $this->view->productID    = $productID;
        $this->view->suite        = $suite;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions      = $this->loadModel('action')->getList('testsuite', $suiteID);
        $this->view->cases        = $this->testsuite->getLinkedCases($suiteID, $sort, $pager);
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->modules      = $this->loadModel('tree')->getOptionMenu($suite->product, 'case', 0, 'all');
        $this->view->branches     = $this->loadModel('branch')->getPairs($suite->product);
        $this->view->canBeChanged = common::canBeChanged('testsuite', $suite);
        $this->view->automation   = $this->loadModel('zanode')->getAutomationByProduct($productID);
        $this->display();
    }

    /**
     * 修改一个测试套件。
     * Edit a test suite.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function edit(int $suiteID)
    {
        /* 检查用户访问权限。 */
        /* Check user access rights. */
        $suite = $this->testsuiteZen->checkTestsuiteAccess($suiteID);

        if(!empty($_POST))
        {
            /* 根据suiteID更新数据，如果更新成功，则记录日志。 */
            /* Update the data according to the suiteID, and record the log if the update is successful. */
            $suite   = form::data($this->config->testsuite->form->edit)->add('id', $suiteID)->get();
            $changes = $this->testsuite->update($suite, $this->post->uid);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testsuite', $suiteID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($suiteID) ? : $this->lang->testsuite->successSaved;
            return $this->send(array('result' => 'success', 'message' => $message, 'locate' => inlink('view', "suiteID={$suiteID}")));
        }

        /* 设置1.5级导航。 */
        /* Set level 1.5 navigation */
        $productID = $this->product->checkAccess($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($productID);

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->testsuite->edit;
        $this->view->suite     = $suite;
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * 删除一个测试套件。
     * Delete a test suite.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function delete(int $suiteID)
    {
        /* 检查用户访问权限。 */
        /* Check user access rights. */
        $this->testsuiteZen->checkTestsuiteAccess($suiteID);

        $this->testsuite->deleteSuiteByID($suiteID);
        $message = $this->executeHooks($suiteID) ? : '';
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * 关联用例到一个测试套件。
     * Link cases to a test suite.
     *
     * @param  int    $suiteID
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkCase(int $suiteID, int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 当前页面URI存入session。 */
        /* Save the URI of current page into session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        if(!empty($_POST))
        {
            $caseData = form::data($this->config->testsuite->form->linkCase)->get();

            $this->testsuite->linkCase($suiteID, $caseData->cases, $caseData->versions);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "suiteID=$suiteID")));
        }

        $suite = $this->testsuite->getById($suiteID);

        /* 设置1.5级导航。 */
        /* Set level 1.5 navigation */
        $productID = $this->product->checkAccess($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($productID);

        /* 初始化分页。 */
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 初始化表单搜索参数。 */
        /* Init form search parameters. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', 0, 'all');
        $this->config->testcase->search['params']['lib']['values']    = $this->loadModel('caselib')->getLibraries();
        $this->config->testcase->search['params']['story']['values']  = array('' => '') + $this->testsuite->getCaseLinkedStories($productID);

        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['actionURL'] = inlink('linkCase', "suiteID=$suiteID&param=myQueryID");

        $scene = $this->testcase->getSceneMenu($productID);
        $this->config->testcase->search['params']['scene']['values'] = $scene;

        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->title     = $suite->name . $this->lang->colon . $this->lang->testsuite->linkCase;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases     = $this->testsuite->getUnlinkedCases($suite, $param, $pager);
        $this->view->suiteID   = $suiteID;
        $this->view->param     = $param;
        $this->view->pager     = $pager;
        $this->view->suite     = $suite;
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * 从测试套件中移除一个用例。
     * Remove a case from test suite.
     *
     * @param  int    $suiteID
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function unlinkCase(int $suiteID, int $caseID)
    {
        $this->testsuite->deleteCaseBySuiteID(array($caseID), $suiteID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 批量移除关联用例。
     * Batch unlink cases.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function batchUnlinkCases(int $suiteID)
    {
        $caseIDList = zget($_POST, 'caseIdList', array());
        $this->testsuite->deleteCaseBySuiteID($caseIDList, $suiteID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }
}
