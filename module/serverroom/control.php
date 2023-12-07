<?php
declare(strict_types=1);
/**
 * The control file of serverroom of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     serverroom
 * @link        https://www.zentao.net
 */
class serverroom extends control
{
    /**
     * 机房列表。
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
    public function browse(string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        $this->view->title          = $this->lang->serverroom->common;
        $this->view->pager          = $pager;
        $this->view->param          = $param;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->orderBy        = $orderBy;
        $this->view->browseType     = $browseType;
        $this->view->serverRoomList = $serverRoomList;
        $this->display();
    }

    /**
     * 添加机房。
     * Create server room.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $room = form::data($this->config->serverroom->form->create)->get();
            $createID = $this->serverroom->create($room);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('serverRoom', $createID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('serverroom', 'browse')));
        }

        $this->view->title = $this->lang->serverroom->create;
        $this->view->users = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->display();
    }

    /**
     * 编辑机房。
     * Edit server room.
     *
     * @param  int     $roomID
     * @access public
     * @return void
     */
    public function edit(int $roomID)
    {
        if($_POST)
        {
            $room = form::data($this->config->serverroom->form->edit)->get();
            $changes = $this->serverroom->update($roomID, $room);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('serverRoom', $roomID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('serverroom', 'view', "room=$roomID")));
        }

        $this->view->title      = $this->lang->serverroom->edit;
        $this->view->serverRoom = $this->serverroom->fetchByID($roomID);
        $this->view->position[] = html::a($this->createLink('serverroom', 'browse'), $this->lang->serverroom->common);
        $this->view->position[] = $this->lang->serverroom->edit;

        $this->view->users = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->display();
    }

    /**
     * 查看机房信息。
     * View server room.
     *
     * @param  int    $roomID
     * @access public
     * @return void
     */
    public function view(int $roomID)
    {
        $this->view->title      = $this->lang->serverroom->view;
        $this->view->serverRoom = $this->serverroom->fetchByID($roomID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->actions    = $this->loadModel('action')->getList('serverroom', $roomID);

        $this->display();
    }

    /**
     * 删除机房。
     * Delete server room.
     *
     * @param  int     $roomID
     * @access public
     * @return void
     */
    public function delete(int $roomID)
    {
        $this->serverroom->delete(TABLE_SERVERROOM, $roomID);

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
