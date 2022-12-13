<?php
/**
 * The control file of ZenAgent Node of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liyuchun <liyuchun@easycorp.ltd>
 * @package     qa
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zanode extends control
{

    /**
     * Browse ZenAgent Node page.
     *
     * @param  string   $browseType
     * @param  string   $param
     * @param  string   $orderBy
     * @param  int      $recTotal
     * @param  int      $recPerPage
     * @param  int      $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $this->app->loadClass('pager', $static = true);

        $queryID  = ($browseType == 'bysearch')  ? (int)$param : 0;
        $pager    = pager::init($recTotal, $recPerPage, $pageID);
        $nodeList = $this->zanode->getListByQuery($browseType, $queryID, $orderBy, $pager);
        $hosts    = $this->loadModel('zahost')->getPairs('host');

        /* Build the search form. */
        $actionURL = $this->createLink('zanode', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->zanode->search['actionURL'] = $actionURL;
        $this->config->zanode->search['queryID']   = $queryID;
        $this->config->zanode->search['onMenuBar'] = 'no';
        $this->config->zanode->search['params']['host']['values'] = array('' => '') + $hosts;
        $this->loadModel('search')->setSearchParams($this->config->zanode->search);

        $this->view->title      = $this->lang->zanode->common;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->nodeList   = $nodeList;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Create node page.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->zanode->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->zanode->create;
        $this->view->hostPairs = array('' => '') + $this->loadModel('zahost')->getPairs('host');

        return $this->display();
    }

    /**
     * Edit node.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->zanode->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('zanode', $id, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->zanode->editAction;
        $this->view->zanode    = $this->zanode->getNodeByID($id);
        $this->view->host      = $this->zanode->getHostByID($this->view->zanode->parent);
        $this->view->image     = $this->zanode->getImageByID($this->view->zanode->image);
        $this->display();
    }

    /**
     * View Node.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->view->title   = $this->lang->zanode->view;
        $this->view->zanode  = $this->zanode->getNodeByID($id);
        $this->view->actions = $this->loadModel('action')->getList('zanode', $id);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /*
     * Init node.
     *
     * @param  int      $hostID
     * @return void
     */
    public function init($nodeID)
    {
        $this->view->title      = $this->lang->zanode->initTitle;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->nodeID     = $nodeID;
        $this->view->node       = $this->zanode->getNodeById($nodeID);
        $this->view->notice     = $this->lang->zanode->init->initSuccessNoticeTitle;
        $this->view->buttonName = $this->lang->zanode->init->button;
        $this->view->modalLink  = $this->createLink('zanode', 'browse');
        $this->view->closeLink  = $this->createLink('zanode', 'browse');

        $this->display();
    }

    /**
     * Suspend Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function suspend($nodeID)
    {
        $this->handleNode($nodeID, 'suspend');
    }

    /**
     * Reboot Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function reboot($nodeID)
    {
        $this->handleNode($nodeID, 'reboot');
    }

    /**
     * Resume Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function resume($nodeID)
    {
        $this->handleNode($nodeID, 'resume');
    }

    /**
     * Boot node.
     *
     * @param  int    $nodeID
     * @param  string $type
     * @return void
     */
    public function handleNode($nodeID, $type)
    {
        $error = $this->zanode->handleNode($nodeID, $type);

        if($error)
        {
            return print(js::error($error));
        }
        else
        {
            return print(js::alert($this->lang->zanode->actionSuccess) . js::reload('parent'));
        }
    }

    /**
     * Create custom image.
     *
     * @param  int    $zanodeID
     * @access public
     * @return void
     */
    public function createImage($nodeID = 0)
    {
        $task        = '';
        $node        = $this->zanode->getNodeByID($nodeID);
        $customImage = $this->zanode->getCustomImage($nodeID, 'created,inprogress');
        if($customImage) $task = $this->zanode->getTaskStatus($node, $customImage->id, 'exportVm');

        if($_POST)
        {
            $this->zanode->createImage($nodeID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->task = $task;
        $this->view->node = $node;
        $this->view->rate = !empty($task->rate) ? $task->rate : 0;
        $this->display();
    }

    /**
     * Desctroy node.
     *
     * @param  int  $nodeID
     * @return void
     */
    public function delete($nodeID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->zanode->confirmDelete, inlink('delete', "nodeID=$nodeID&confirm=yes")));
        }
        else
        {
            $error = $this->zanode->destroy($nodeID);

            if($error)
            {
                return print(js::alert($error));
            }
            else
            {
                if(isonlybody()) return print(js::alert($this->lang->zanode->actionSuccess) . js::reload('parent.parent'));
                return print(js::alert($this->lang->zanode->actionSuccess) . js::reload('parent'));
            }
    }
    }

    /**
     * Bring up novmc management view.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function getVNC($nodeID)
    {
        $node = $this->zanode->getNodeByID($nodeID);
        $vnc  = $this->zanode->getVncUrl($node);

        /* Add action log. */
        if(!empty($vnc->token)) $this->loadModel('action')->create('zanode', $nodeID, 'getVNC');

        $this->view->url   = $node->ip . ":" . $node->hzap;
        $this->view->host  = !empty($vnc->hostIP) ? $vnc->hostIP:'';
        $this->view->token = !empty($vnc->token) ? $vnc->token:'';
        $this->display();
    }

    /**
     * Ajax get template pairs by api.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxGetImages($hostID)
    {
        $templatePairs = $this->loadModel('zahost')->getImagePairs($hostID);

        return print(html::select('image', $templatePairs, '', "class='form-control chosen'"));
    }

    /**
     * Ajax get template info.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function ajaxGetImage($imageID)
    {
        $template = $this->loadModel('zahost')->getImageByID($imageID);
        return print(json_encode($template));
    }

    /**
     * Ajax get task status.
     *
     * @param  int    $extranet
     * @param  int    $taskID
     * @param  string $type
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetTaskStatus($nodeID, $taskID = 0, $type = '', $status = '')
    {
        $node   = $this->zanode->getNodeByID($nodeID);
        $result = $this->zanode->getTaskStatus($node, $taskID, $type, $status);
        return print(json_encode($result));
    }

    /**
     * Update image.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function ajaxUpdateImage($imageID = 0)
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->dao->update(TABLE_IMAGE)->data($data)->where('id')->eq($imageID)->autoCheck()->exec();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }
    }

    /**
     * Check service status by ajax.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function ajaxGetServiceStatus($hostID)
    {
        $node          = $this->zanode->getNodeById($hostID);
        $serviceStatus = $this->zanode->getServiceStatus($node);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $serviceStatus));
    }

    /**
     * Install service by ajax.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function ajaxInstallService($nodeID, $service)
    {
        $node   = $this->zanode->getNodeById($nodeID);
        $result = $this->zanode->installService($node, $service);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $result));
    }

    /**
     * ajaxRunZTFScript
     *
     * @param  int    $scriptID
     * @access public
     * @return void
     */
    public function ajaxRunZTFScript($scriptID = 0)
    {
        $script = $this->zanode->getAutomationByID($scriptID);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $serviceStatus));
    }
}
