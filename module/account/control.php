<?php
declare(strict_types=1);
/**
 * The control file of account of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     account
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class account extends control
{
    /**
     * Browse accouts page.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $browseType = 'all', string $param = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadLang('serverroom');
        $browseType = strtolower($browseType);

        $this->session->set('accountList', $this->app->getURI(true));
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $accountList = $this->account->getList($browseType, $param, $orderBy, $pager);

        /* Build the search form. */
        $this->config->account->search['actionURL'] = $this->createLink('account', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->account->search['queryID']   = $param;
        $this->config->account->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->account->search);

        $this->view->title       = $this->lang->account->common;
        $this->view->accountList = $accountList;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;

        $this->display();
    }

    /**
     * Create account.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $account = $this->accountZen->buildDataForCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->account->create($account);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink('account', 'browse')));
        }

        $this->app->loadLang('serverroom');
        $this->view->title = $this->lang->account->create;
        $this->display();
    }

    /**
     * Edit one account.
     *
     * @param  int    $id
     * @param  string $from parent|self
     * @access public
     * @return void
     */
    public function edit(int $id, string $from = 'parent')
    {
        if($_POST)
        {
            $account = $this->accountZen->buildDataForEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->account->update($id, $account);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse'), 'closeModal' => true));
        }

        $this->view->title   = $this->lang->account->edit;
        $this->view->account = $this->account->getById($id);
        $this->view->rooms   = $this->loadModel('serverroom')->getPairs();

        $this->display();
    }

    /**
     * View account.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $this->view->title      = $this->lang->account->view;
        $this->view->account    = $this->account->getById($id);
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->actions    = $this->loadModel('action')->getList('account', $id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'account');
        $this->display();
    }

    /**
     * Delete Account.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $this->account->delete(TABLE_ACCOUNT, $id);

        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError(true);
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $response['load']    = true;
        }
        return $this->send($response);
    }
}
