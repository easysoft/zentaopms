<?php
/**
 * The control file of account of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
     * @param  string   $browseType
     * @param  string   $param
     * @param  string   $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
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
        $this->view->position[]  = $this->lang->account->common;

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
            $id = $this->account->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('account', $id, 'created');

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'loadCurrentPage()', 'closeModal' => true));
        }

        $this->app->loadLang('serverroom');

        $this->view->title      = $this->lang->account->create;
        $this->view->position[] = html::a($this->createLink('account', 'browse'), $this->lang->account->common);
        $this->view->position[] = $this->lang->account->create;

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
    public function edit($id, $from = 'parent')
    {
        if($_POST)
        {
            $changes = $this->account->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('account', $id, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse'), 'closeModal' => true));
        }

        $this->view->title   = $this->lang->account->edit;
        $this->view->account = $this->account->getById($id);
        $this->view->rooms   = $this->loadModel('serverroom')->getPairs();
        $this->view->from    = $from;

        $this->view->position[] = html::a($this->createLink('account', 'browse'), $this->lang->account->common);
        $this->view->position[] = $this->lang->account->edit;

        $this->display();
    }

    /**
     * View account.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->view->title      = $this->lang->account->view;
        $this->view->position[] = html::a($this->createLink('account', 'browse'), $this->lang->account->common);
        $this->view->position[] = $this->lang->account->view;

        $this->view->account       = $this->account->getById($id);
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
    public function delete($id)
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

    /**
     * Change account status.
     *
     * @param  int    $id
     * @param  int    $accountID
     * @param  int    $status
     * @access public
     * @return void
     */
    public function changeStatus($id, $accountID, $status)
    {
        $accountStatus = $status == 'offline' ? 'online' : 'offline';
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $postData = fixer::input('post')->get();
            if(empty($postData->reason))
            {
                $reasonKey = $accountStatus . 'Reason';
                $errTip = $this->lang->account->{$reasonKey};
                dao::$errors['submit'][] = sprintf($this->lang->error->notempty, $errTip);
                return print(js::error(dao::getError()));
            }

            $this->account->updateStatus($accountID, $accountStatus);

            $this->loadModel('action')->create('account', $id, $accountStatus, $postData->reason);
            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::reload('parent'));
        }

        $this->view->title = $this->lang->account->{$accountStatus};
        $this->display();
    }

}
