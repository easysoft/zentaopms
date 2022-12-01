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
     * View host list.
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
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
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
     * View host.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id, $orderBy = 'id_desc')
    {
        $this->view->title      = $this->lang->zahost->view;
        $this->view->position[] = html::a($this->createLink('host', 'browse'), $this->lang->zahost->common);
        $this->view->position[] = $this->lang->zahost->view;

        $this->view->zahost     = $this->zahost->getById($id);
        $this->view->orderBy    = $orderBy;
        $this->view->nodeList   = $this->loadModel("zanode")->getListByHost($this->view->zahost->parent, $orderBy);
        $this->view->actions    = $this->loadModel('action')->getList('zahost', $id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
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
            $hostID = $this->zahost->create();
            if(dao::isError())
            {
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $initLink = $this->createLink('zahost', 'inithost', "hostID=$hostID");
            return print("<script>showModal('$initLink')</script>");
        }

        $this->view->title      = $this->lang->zahost->create;
        $this->view->notice     = $this->lang->zahost->initHostNotice;
        $this->view->buttonName = $this->lang->zahost->init;
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

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('zahost', $hostID, 'Edited');
                $this->action->logHistory($actionID, $changes);
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

        $this->dao->update(TABLE_ZAHOST)->set('deleted')->eq(1)->where('id')->eq($assetID)->exec();
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
     * Show image list page.
     *
     * @param  int    $hostID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseImage($hostID, $browseType = 'all', $param = 0, $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->session->set('imageList', $this->app->getURI(true));
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $imageList = $this->zahost->getImageList($hostID, $browseType, $param, $orderBy, $pager);

        $this->view->title      = $this->lang->zahost->image->list;
        $this->view->hostID     = $hostID;
        $this->view->imageList  = $imageList;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Create template.
     *
     * @access public
     * @return void
     */
    public function createImage($hostID)
    {
        $host = $this->zahost->getById($hostID);
        if($_POST)
        {
            $this->zahost->createImage();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browseTemplate', "id={$hostID}")));
        }

        $this->view->imageOptions = array('' => $this->lang->zahost->notice->loading);
        $this->view->host         = $host;
        $this->display();
    }

    /**
     * Sent download image request to Host.
     *
     * @param  int    $hostID
     * @param  string $imageName
     * @access public
     * @return object
     */
    public function ajaxDownloadImage($hostID, $imageName)
    {
        $image = $this->zahost->getImageByNameAndHostID($imageName, $hostID);
        if(empty($image))
        {
            $image = $this->zahost->createImage($hostID, $imageName);
        }

        $this->zahost->downloadImage($image);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->zahost->image->downloadImageFail));

        return $this->send(array('result' => 'success', 'message' => $this->lang->zahost->image->downloadImageSuccess));
    }

    /**
     * Query downloading progress of images of host.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxImageDownloadProgress($hostID)
    {
        $statusList = array();

        $imageList = $this->zahost->getImageList($hostID);
        foreach($imageList as $image)
        {
            $image = $this->zahost->queryDownloadImageStatus($image);
            $statusName = zget($this->lang->zahost->image->statusList, $image->status,'');

            $statusList[$image->id] = array('statusCode' => $image->status, 'status' => $statusName, 'progress' => $image->status == 'inprogress' ? $image->rate * 100 . '%' : '');
        }

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $statusList));
    }

    /**
     * Sent cancel download image request to Host.
     *
     * @param  int    $hostID
     * @param  string $imageName
     * @access public
     * @return object
     */
    public function ajaxCancelDownload($imageID)
    {
        $image = $this->zahost->getImageByID($imageID);

        $this->zahost->cancelDownload($image);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->zahost->image->downloadImageFail));

        return $this->send(array('result' => 'success', 'message' => $this->lang->zahost->image->cancelDownloadSuccess));
    }

    /**
     * Get image list by ajax.
     *
     * @param  int    $hostID
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxImageList()
    {
        $imageList = array('1' => 'Ubuntu20');
        if($imageList) return $this->send(array('result' => 'success', 'message' => '', 'data' => $imageList));

        return $this->send(array('result' => 'fail', 'message' => array('imageName' => array($this->lang->zahost->notice->noImage))));
    }

    /*
     * Init host.
     *
     * @param  int      $hostID
     * @return void
     */
    public function initHost($hostID)
    {
        $imageList = $this->zahost->getImagePairs($hostID);

        $this->view->title      = $this->lang->zahost->init;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->hostID     = $hostID;
        $this->view->host       = $this->zahost->getById($hostID);
        $this->view->notice     = $imageList ? $this->lang->zahost->createZanodeNotice : $this->lang->zahost->downloadImageNotice;
        $this->view->buttonName = $imageList ? $this->lang->zahost->createZanode : $this->lang->zahost->image->downloadImage;
        $this->view->modalLink  = $imageList ? $this->createLink('zanode', 'create', "hostID=$hostID") : $this->createLink('zahost', 'browseImage', "hostID=$hostID");

        $this->display();
    }

    /**
     * Check service status by ajax.
     *
     * @param  int    $hostID
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxGetServiceStatus($hostID)
    {
        $host          = $this->zahost->getById($hostID);
        $serviceStatus = $this->zahost->getServiceStatus($host);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $serviceStatus));
    }
}
