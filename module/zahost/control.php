<?php
/**
 * The control file of zahost of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zahost extends control
{
    /**
     * View host.
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
    public function browse($browseType = 'all', $param = 0, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $param      = (int)$param;

        $this->app->session->set('zahostList', $this->app->getURI(true));
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $hostList = $this->zahost->getList($browseType, $param, $orderBy, $pager);

        /* Build the search form. */
        $actionURL = $this->createLink('zahost', 'browse', "browseType=bySearch&param=myQueryID");
        $this->config->zahost->search['actionURL'] = $actionURL;
        $this->config->zahost->search['queryID']   = $param;
        $this->config->zahost->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->zahost->search);

        $this->view->title      = $this->lang->zahost->common;
        $this->view->hostList   = $hostList;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Create host.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->zahost->create();
            if(dao::isError())
            {
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->zahost->create;
        $this->display();
    }

    /**
     * Edit host.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function edit($hostID)
    {
        if($_POST)
        {
            $changes = $this->zahost->update($hostID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('zahost', $hostID, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->zahost->editAction;
        $this->view->host  = $this->zahost->getById($hostID);
        $this->display();
    }


    /**
     * Delete host.
     *
     * @param  int    $assetID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($assetID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->zahost->confirmDelete, inlink('delete', "assetID=$assetID&confirm=yes")));
        }

        $this->dao->update(TABLE_ASSET)->set('deleted')->eq(1)->where('id')->eq($assetID)->exec();
        $this->loadModel('action')->create('zahost', $assetID, 'deleted');

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

        if(isonlybody()) return print(js::reload('parent.parent'));
        return print(js::locate($this->createLink('zahost', 'browse'), 'parent'));
    }

    /**
     * Create template.
     *
     * @access public
     * @return void
     */
    public function createTemplate($hostID)
    {
        $host = $this->zahost->getById($hostID);
        if($_POST)
        {
            $this->zahost->createTemplate($host);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browseTemplate', "id={$hostID}")));
        }

        $this->view->imageOptions = array('' => $this->lang->zahost->notice->loading);
        $this->view->host         = $host;
        $this->display();
    }

    /**
     * Edit template
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function editTemplate($templateID)
    {
        $template = $this->zahost->getTemplateById($templateID);
        if($_POST)
        {
            $changes = $this->zahost->updateTemplate($templateID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('vmtemplate', $templateID, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browsetemplate', "hostID={$template->hostID}")));
        }

        $this->view->title        = $this->lang->zahost->edit;
        $this->view->template     = $template;
        $this->view->imageOptions = array('' => $this->lang->zahost->notice->loading);
        $this->display();
    }

    /**
     * Delete host.
     *
     * @param  int    $templateID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteTemplate($templateID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->zahost->confirmDeleteVMTemplate, inlink('deleteTemplate', "templateID=$templateID&confirm=yes")));
        }

        $template = $this->zahost->getTemplateById($templateID);
        $this->dao->delete()->from(TABLE_VMTEMPLATE)->where('id')->eq($templateID)->exec();
        $this->loadModel('action')->create('vmtemplate', $templateID, 'deleted');

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

        if(isonlybody()) return print(js::reload('parent.parent'));
        return print(js::locate($this->createLink('zahost', 'browsetemplate', "hostID={$template->hostID}"), 'parent'));
    }

    /*
     * Browse VM template.
     *
     * @param  int      $hostID
     * @param  string   $browseType
     * @param  string   $param
     * @param  string   $orderBy
     * @param  int      $recTotal
     * @param  int      $recPerPage
     * @param  int      $pageID
     * @access public
     * @return void
     */
    public function browseTemplate($hostID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $this->app->loadClass('pager', true);

        $queryID      = ($browseType == 'bysearch')  ? (int)$param : 0;
        $pager        = pager::init($recTotal, $recPerPage, $pageID);
        $templateList = $this->zahost->getVMTemplateList($hostID, $browseType, $queryID, $orderBy, $pager);

        /* Build the search form. */
        $actionURL = $this->createLink('zahost', 'browseTemplate', "hostID=$hostID&browseType=bySearch&queryID=myQueryID");
        $this->config->vmTemplate->search['actionURL'] = $actionURL;
        $this->config->vmTemplate->search['queryID']   = $queryID;
        $this->config->vmTemplate->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->vmTemplate->search);

        $this->view->title        = $this->lang->zahost->vmTemplate->common;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->templateList = $templateList;
        $this->view->hostID       = $hostID;
        $this->view->pager        = $pager;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;

        $this->display();
    }

    /**
     * Check IP format and generate secret.
     *
     * @param  string $IP
     * @access public
     * @return void
     */
    public function ajaxCheckPublicIP($IP)
    {
        if(!validater::checkIP($IP)) return $this->send(array('result' => 'fail', 'message' => array('publicIP' => array(sprintf($this->lang->zahost->notice->ip, $this->lang->zahost->publicIP)))));

        $secret = md5(time());
        $registerCommand = sprintf($this->lang->zahost->notice->registerCommand, $this->server->server_addr, $this->server->server_port, $IP, $secret);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => array('registerCommand' => $registerCommand, 'secret' => $secret)));
    }

    /**
     * Get image list by ajax.
     *
     * @param  int    $hostID
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxImageList($hostID, $templateID = 0)
    {
        $host      = $this->zahost->getById($hostID);
        $imageList = $this->zahost->getImageList($host, $templateID);

        if($imageList) return $this->send(array('result' => 'success', 'message' => '', 'data' => $imageList));

        return $this->send(array('result' => 'fail', 'message' => array('imageName' => array($this->lang->zahost->notice->noImage))));
    }
}
