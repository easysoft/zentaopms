<?php
/**
 * The control file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testsuite extends control
{
    public $products = array();

    /**
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
     * Browse test suites.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testsuiteList', $this->app->getURI(true));

        /* Set menu. */
        $this->view->products = $this->products = $this->loadModel('product')->getPairs('nocode');
        $productID = $this->product->saveState($productID, $this->products);
        $this->testsuite->setMenu($this->products, $productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);
        $productName = isset($this->products[$productID]) ? $this->products[$productID] : '';

        $this->view->title       = $productName . $this->lang->testsuite->common;
        $this->view->position[]  = html::a($this->createLink('testsuite', 'browse', "productID=$productID"), $productName);
        $this->view->position[]  = $this->lang->testsuite->common;

        $this->view->productID   = $productID;
        $this->view->productName = $productName;
        $this->view->orderBy     = $orderBy;
        $this->view->suites      = $this->testsuite->getSuites($productID, $sort, $pager);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;

        $this->display();
    }

    /**
     * Create a test suite.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->testsuite->successSaved;
            $suiteID = $this->testsuite->create($productID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }
            $actionID = $this->loadModel('action')->create('testsuite', $suiteID, 'opened');

            $this->executeHooks($suiteID);

            $response['locate']  = $this->createLink('testsuite', 'browse', "productID=$productID");
            $this->send($response);
        }

        /* Set menu. */
        $this->view->products = $this->products = $this->loadModel('product')->getPairs('nocode');
        $productID  = $this->product->saveState($productID, $this->products);
        $this->testsuite->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testsuite->create;
        $this->view->position[] = html::a($this->createLink('testsuite', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testsuite->common;
        $this->view->position[] = $this->lang->testsuite->create;

        $this->view->productID    = $productID;
        $this->display();
    }

    /**
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
    public function view($suiteID, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('testtask');
        /* Get test suite, and set menu. */
        $suite = $this->testsuite->getById($suiteID, true);
        if(!$suite) die(js::error($this->lang->notFound) . js::locate('back'));
        if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) die(js::error($this->lang->error->accessDenied) . js::locate('back'));
        $productID = $suite->product;

        $this->view->products = $this->products = $this->loadModel('product')->getPairs('nocode');
        $this->testsuite->setMenu($this->products, $productID);

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->executeHooks($suiteID);

        $this->view->title      = "SUITE #$suite->id $suite->name/" . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('testsuite', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testsuite->common;
        $this->view->position[] = $this->lang->testsuite->view;

        $this->view->productID = $productID;
        $this->view->suite     = $suite;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions   = $this->loadModel('action')->getList('testsuite', $suiteID);
        $this->view->cases     = $this->testsuite->getLinkedCases($suiteID, $sort, $pager);
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->modules   = $this->loadModel('tree')->getOptionMenu($suite->product, 'case');
        $this->view->branches  = $this->loadModel('branch')->getPairs($suite->product, 'noempty');

        $this->display();
    }

    /**
     * Edit a test suite.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function edit($suiteID)
    {
        $suite = $this->testsuite->getById($suiteID);
        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->testsuite->successSaved;
            $changes = $this->testsuite->update($suiteID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testsuite', $suiteID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($suiteID);

            $response['locate']  = inlink('view', "suiteID=$suiteID");
            $this->send($response);
        }

        if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

        /* Get suite info. */
        $this->view->products = $this->products = $this->loadModel('product')->getPairs('nocode');
        $productID = $this->product->saveState($suite->product, $this->products);

        /* Set menu. */
        $this->testsuite->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testsuite->edit;
        $this->view->position[] = html::a($this->createLink('testsuite', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testsuite->common;
        $this->view->position[] = $this->lang->testsuite->edit;

        $this->view->suite = $suite;
        $this->display();
    }

    /**
     * Delete a test suite.
     *
     * @param  int    $suiteID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($suiteID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testsuite->confirmDelete, inlink('delete', "suiteID=$suiteID&confirm=yes")));
        }
        else
        {
            $suite = $this->testsuite->getById($suiteID);
            if($suite->type == 'private' and $suite->addedBy != $this->app->user->account and !$this->app->user->admin) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $this->testsuite->delete($suiteID);

            $this->executeHooks($suiteID);

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
                $this->send($response);
            }
            die(js::reload('parent'));
        }
    }

    /**
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
    public function linkCase($suiteID, $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $this->testsuite->linkCase($suiteID);
            $this->locate(inlink('view', "suiteID=$suiteID"));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get suite and product id. */
        $this->view->products = $this->products = $this->loadModel('product')->getPairs('nocode');
        $suite      = $this->testsuite->getById($suiteID);
        $productID = $this->product->saveState($suite->product, $this->products);

        /* Save session. */
        $this->testsuite->setMenu($this->products, $productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['actionURL'] = inlink('linkCase', "suiteID=$suiteID&param=myQueryID");
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->title      = $suite->name . $this->lang->colon . $this->lang->testsuite->linkCase;
        $this->view->position[] = html::a($this->createLink('testsuite', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testsuite->common;
        $this->view->position[] = $this->lang->testsuite->linkCase;

        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases   = $this->testsuite->getUnlinkedCases($suite, $param, $pager);
        $this->view->suiteID = $suiteID;
        $this->view->pager   = $pager;
        $this->view->suite   = $suite;

        $this->display();
    }

    /**
     * Remove a case from test suite.
     *
     * @param  int    $suiteID
     * @param  int    $rowID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlinkCase($suiteID, $rowID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testsuite->confirmUnlinkCase, $this->createLink('testsuite', 'unlinkCase', "rowID=$rowID&confirm=yes")));
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
            $this->send($response);
        }
    }

    /**
     * Batch unlink cases.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function batchUnlinkCases($suiteID)
    {
        if(isset($_POST['caseIDList']))
        {
            $this->dao->delete()->from(TABLE_SUITECASE)
                ->where('suite')->eq((int)$suiteID)
                ->andWhere('`case`')->in($this->post->caseIDList)
                ->exec();
        }

        die(js::locate($this->createLink('testsuite', 'view', "suiteID=$suiteID")));
    }
}
