<?php

use function zin\createLink;

/**
 * The control file of server room of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     serverroom
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class serverroom extends control
{
    /**
     * Server room.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $param      = (int)$param;

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $serverRoomList = $this->serverroom->getList($browseType, $param, $orderBy, $pager);

        /* Build the search form. */
        $actionURL = $this->createLink('serverroom', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->serverroom->search['actionURL'] = $actionURL;
        $this->config->serverroom->search['queryID']   = $param;
        $this->config->serverroom->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->serverroom->search);

        $this->view->title      = $this->lang->serverroom->common;
        $this->view->position[] = $this->lang->serverroom->common;

        $this->view->pager   = $pager;
        $this->view->param   = $param;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->orderBy = $orderBy;

        $this->view->browseType     = $browseType;
        $this->view->serverRoomList = $serverRoomList;
        $this->display();
    }

    /**
     * Create server room.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $createID = $this->serverroom->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('serverRoom', $createID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('serverroom', 'browse')));
        }

        $this->view->title = $this->lang->serverroom->create;
        $this->view->position[] = html::a($this->createLink('serverroom', 'browse'), $this->lang->serverroom->common);
        $this->view->position[] = $this->lang->serverroom->create;

        $this->view->users = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->display();
    }

    /**
     * Edit server room.
     *
     * @param  int     $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->serverroom->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('serverRoom', $id, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('serverroom', 'view', "room=$id")));
        }

        $this->view->title      = $this->lang->serverroom->edit;
        $this->view->serverRoom = $this->serverroom->getById($id);
        $this->view->position[] = html::a($this->createLink('serverroom', 'browse'), $this->lang->serverroom->common);
        $this->view->position[] = $this->lang->serverroom->edit;

        $this->view->users = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->display();
    }

    public function view($id)
    {
        $this->view->title      = $this->lang->serverroom->view;
        $this->view->serverRoom = $this->serverroom->getById($id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->actions    = $this->loadModel('action')->getList('serverroom', $id);

        $this->view->position[] = html::a($this->createLink('serverroom', 'browse'), $this->lang->serverroom->common);
        $this->view->position[] = $this->lang->serverroom->view;

        $this->display();
    }

    /**
     * Delete server room.
     *
     * @param  int     $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->serverroom->delete(TABLE_SERVERROOM, $id);

        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
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
