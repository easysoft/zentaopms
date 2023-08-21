<?php
/**
 * The control file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
        if(empty($this->products) and !helper::isAjaxRequest()) return print($this->locate($this->createLink('product', 'showErrorNone', "moduleName=qa&activeMenu=testsuite")));
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
        $this->locate($this->createLink('testsuite', 'browse'));
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
        /* Set level 1.5 menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->loadModel('qa')->setMenu($this->products, $productID);

        /* 加载分页。 */
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 二次排序规则。 */
        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* 根据产品名称获取套件，如果查询为空并且不在第一页，则初始化至当前页码到第一页。 */
        $productName = isset($this->products[$productID]) ? $this->products[$productID] : '';
        $suites      = $this->testsuite->getSuites($productID, $sort, $pager, $type);
        if(empty($suites) and $pageID > 1)
        {
            $pager  = pager::init(0, $recPerPage, 1);
            $suites = $this->testsuite->getSuites($productID, $sort, $pager, $type);
        }

        /* 获取私有和公开的套件数量。 */
        $privateNum = 0;
        foreach($suites as $suiteItem)
        {
            if($suiteItem->type == 'private')
            {
                $privateNum++;
            }
        }
        $suitesNum = !empty($suites) ? count($suites) : 0;
        $publicNum = $suitesNum - $privateNum;
        $summary   = str_replace(array('%total%', '%public%', '%private%'), array($suitesNum, $publicNum, $privateNum), $this->lang->testsuite->summary);

        $this->view->title       = $productName . $this->lang->testsuite->common;
        $this->view->productID   = $productID;
        $this->view->productName = $productName;
        $this->view->orderBy     = $orderBy;
        $this->view->suites      = $suites;
        $this->view->type        = $type;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;
        $this->view->product     = $this->product->getByID($productID);
        $this->view->summary     = $summary;
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
            $suite = form::data($this->config->testsuite->form->create)
                ->setIF($this->lang->navGroup->testsuite != 'qa', 'project', $this->session->project)
                ->add('product', (int)$productID)
                ->get();
            $suite = $this->loadModel('file')->processImgURL($suite, $this->config->testsuite->editor->create['id'], $this->post->uid);

            $suiteID = $this->testsuite->create($suite);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->testsuite->successSaved;

            $this->file->updateObjectID($this->post->uid, $suiteID, 'testsuite');
            $message = $this->executeHooks($suiteID);
            if($message) $response['message'] = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $suiteID));

            $response['locate']  = $this->createLink('testsuite', 'browse', "productID=$productID");
            return $this->send($response);
        }

        /* 设置1.5级菜单。 */
        /* Set menu. */
        $productID  = $this->product->saveState($productID, $this->products);
        $this->loadModel('qa')->setMenu($this->products, $productID);

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
        $this->app->loadLang('testtask');

        /* 根据id获取测试套件信息。 */
        /* Get test suite, and set menu. */
        $suite = $this->testsuite->getById($suiteID, true);
        if(!$suite) return print(js::error($this->lang->notFound) . js::locate('back'));
        if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));

        /* 设置1.5级菜单。 */
        /* Set product session. */
        $productID = $this->product->saveState($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($this->products, $productID);

        /* 用例列表URI存入session。 */
        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* 生成排序规则。 */
        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* 初始化用例列表分页。 */
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->executeHooks($suiteID);

        $this->view->title      = "SUITE #$suite->id $suite->name";
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
        $this->view->automation      = $this->loadModel('zanode')->getAutomationByProduct($productID);
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
        $suite = $this->testsuite->getById($suiteID);
        if(!empty($_POST))
        {
            $response = array();
            $response['result']  = 'success';
            $response['message'] = $this->lang->testsuite->successSaved;

            /* 根据suiteID更新数据，如果更新成功，则记录日志。 */
            $suite   = form::data($this->config->testsuite->form->edit)->add('id', $suiteID)->get();
            $uid     = $this->app->user->id;
            $changes = $this->testsuite->update($suite, $uid);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testsuite', $suiteID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($suiteID);
            if($message) $response['message'] = $message;

            $response['locate']  = inlink('view', "suiteID=$suiteID");
            return $this->send($response);
        }

        if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));

        /* 设置1.5级菜单。 */
        /* Set product session. */
        $productID = $this->product->saveState($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testsuite->edit;
        $this->view->suite = $suite;
        $this->display();
    }

    /**
     * 删除一个测试套件。
     * Delete a test suite.
     *
     * @param  int    $suiteID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete(int $suiteID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->testsuite->confirmDelete, inlink('delete', "suiteID=$suiteID&confirm=yes")));
        }
        else
        {
            $suite = $this->testsuite->getById($suiteID);
            if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $this->testsuite->delete($suiteID);

            $message = $this->executeHooks($suiteID);
            if($message) $response['message'] = $message;

            /* 如果是ajax操作，直接返回操作结果。 */
            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }
            return print(js::reload('parent'));
        }
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
        /* 当前页面存入session。 */
        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        if(!empty($_POST))
        {
            $this->testsuite->linkCase($suiteID);
            if(dao::isError()) return $this->send(array('result' => 'success', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "suiteID=$suiteID")));
        }

        $suite = $this->testsuite->getById($suiteID);

        /* 设置1.5级菜单。 */
        /* Set product session. */
        $productID = $this->product->saveState($suite->product, $this->products);
        $this->loadModel('qa')->setMenu($this->products, $productID);

        /* 初始化一个分页器。 */
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 创建搜索表单。 */
        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', 0, 'all');
        $this->config->testcase->search['params']['lib']['values']    = $this->loadModel('caselib')->getLibraries();

        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['actionURL'] = inlink('linkCase', "suiteID=$suiteID&param=myQueryID");

        $scene = $this->testcase->getSceneMenu($productID, 0, '', 0,  0,0);
        $this->config->testcase->search['params']['scene']['values'] = $scene;

        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->title      = $suite->name . $this->lang->colon . $this->lang->testsuite->linkCase;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases   = $this->testsuite->getUnlinkedCases($suite, $param, $pager);
        $this->view->suiteID = $suiteID;
        $this->view->pager   = $pager;
        $this->view->suite   = $suite;
        $this->display();
    }

    /**
     * 从测试套件中移除一个用例。
     * Remove a case from test suite.
     *
     * @param  int    $suiteID
     * @param  int    $rowID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlinkCase(int $suiteID, int $rowID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->testsuite->confirmUnlinkCase, $this->createLink('testsuite', 'unlinkCase', "rowID=$rowID&confirm=yes")));
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $this->dao->delete()->from(TABLE_SUITECASE)->where('`case`')->eq((int)$rowID)->andWhere('suite')->eq($suiteID)->exec();
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            return $this->send($response);
        }
    }

    /**
     * 批量取消关联用例。
     * Batch unlink cases.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function batchUnlinkCases(int $suiteID)
    {
        if(isset($_POST['caseIDList']))
        {
            $this->dao->delete()->from(TABLE_SUITECASE)
                ->where('suite')->eq((int)$suiteID)
                ->andWhere('`case`')->in($this->post->caseIDList)
                ->exec();
        }

        return print(js::locate($this->createLink('testsuite', 'view', "suiteID=$suiteID")));
    }
}
