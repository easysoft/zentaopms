<?php
/**
 * The control file of zahost of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class zahost extends control
{
    /**
     * 展示宿主机列表。
     * View host list.
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
        $this->app->session->set('zahostList', $this->app->getURI(true));

        /* 构建搜索表单。*/
        /* Build the search form. */
        $actionURL = $this->createLink('zahost', 'browse', "browseType=bySearch&param=myQueryID");
        $this->config->zahost->search['actionURL'] = $actionURL;
        $this->config->zahost->search['queryID']   = $param;
        $this->config->zahost->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->zahost->search);

        /* 是否展示帮助信息。*/
        /* Whether to show the help. */
        $showFeature = false;
        $accounts = !empty($this->config->global->skipAutomation) ? $this->config->global->skipAutomation : '';
        if(strpos(",$accounts,", $this->app->user->account) === false)
        {
            $showFeature = true;
            $accounts .= ',' . $this->app->user->account;
            $this->loadModel('setting')->setItem('system.common.global.skipAutomation', $accounts);
        }

        $browseType = strtolower($browseType);

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->zahost->common;
        $this->view->hostList    = $this->zahost->getList($browseType, $param, $orderBy, $pager);
        $this->view->nodeList    = $this->zahost->getHostNodeGroup();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->showFeature = $showFeature;

        $this->display();
    }

    /**
     * 展示宿主机详情。
     * View host.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $zahost = $this->zahost->getById($id);

        $this->view->title    = $this->lang->zahost->view;
        $this->view->zahost   = $zahost;
        $this->view->nodeList = $this->loadModel('zanode')->getListByHost($id);
        $this->view->initBash = sprintf($this->config->zahost->initBash, $zahost->secret, getWebRoot(true));
        $this->view->actions  = $this->loadModel('action')->getList('zahost', $id);
        $this->display();
    }

    /**
     * 创建宿主机。
     * Create host.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $hostInfo = form::data($this->config->zahost->form->create)->get();
            $hostInfo->secret = md5($hostInfo->name . time());

            $hostID = $this->zahost->create($hostInfo);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($hostID === false) return $this->send(array('result' => 'fail', 'message' => array('extranet' => array($this->lang->zahost->netError))));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'parent.loadHosts()'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zahost', 'view', "hostID=$hostID")));
        }

        $this->view->title = $this->lang->zahost->create;
        $this->display();
    }

    /**
     * 编辑宿主机。
     * Edit host.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function edit(int $hostID)
    {
        if($_POST)
        {
            $hostInfo = form::data($this->config->zahost->form->edit)->add('id', $hostID)->get();
            $changes  = $this->zahost->update($hostInfo);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes === false) return $this->send(array('result' => 'fail', 'message' => array('extranet' => array($this->lang->zahost->netError))));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('zahost', $hostID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse')));
        }

        $this->view->title = $this->lang->zahost->editAction;
        $this->view->host  = $this->zahost->getById($hostID);
        $this->display();
    }


    /**
     * 删除宿主机。
     * Delete host.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function delete(int $hostID)
    {
        $this->zahost->delete(TABLE_ZAHOST, $hostID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(isInModal()) return $this->send(array('result' => 'success', 'load' => true));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zahost', 'browse')));
    }

    /**
     * 显示镜像列表。
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
    public function browseImage(int $hostID, string $orderBy = 'id', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('imageList', $this->app->getURI(true));

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->zahost->image->browseImage;
        $this->view->hostID    = $hostID;
        $this->view->imageList = $this->zahost->getImageList($hostID, $orderBy, $pager);
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;

        $this->display();
    }

    /**
     * 创建镜像。
     * Create Iamge.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function createImage(int $hostID)
    {
        if($_POST)
        {
            $this->zahost->createImage();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inLink('browseTemplate', "id={$hostID}")));
        }

        $this->view->imageOptions = array('' => $this->lang->zahost->notice->loading);
        $this->view->host         = $this->zahost->getByID($hostID);
        $this->display();
    }

    /**
     * 下载镜像。
     * Sent download image request to Host.
     *
     * @param  int    $hostID
     * @param  int    $imageID
     * @access public
     * @return object
     */
    public function downloadImage(int $hostID, int $imageID)
    {
        $image = $this->zahost->getImageByID($imageID);

        $this->zahost->downloadImage($image);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->zahost->image->downloadImageFail));

        if(isInModal()) return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => true));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zahost', 'browseImage', array("hostID" => $hostID))));
    }

    /**
     * 查询镜像下载进度。
     * Query downloading progress of images of host.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxImageDownloadProgress(int $hostID)
    {
        $statusList = array();

        $imageList = $this->zahost->getImageList($hostID);
        foreach($imageList as $image)
        {
            $this->zahost->queryDownloadImageStatus($image);

            $statusName = zget($this->lang->zahost->image->statusList, $image->status, '');

            $progress = '';
            if($image->status == 'inprogress') $progress = $image->rate * 100 . '%';
            if($image->status == 'completed')  $progress = '100%';

            $statusList[$image->id] = array('statusCode' => $image->status, 'status' => $statusName, 'progress' => $progress, 'path' => $image->path);
        }

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $statusList));
    }

    /**
     * 取消镜像下载。
     * Sent cancel download image request to Host.
     *
     * @param  int    $imageID
     * @access public
     * @return object
     */
    public function cancelDownload(int $imageID)
    {
        $image = $this->zahost->getImageByID($imageID);

        $this->zahost->cancelDownload($image);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->zahost->image->downloadImageFail));

        if(isInModal()) return $this->send(array('result' => 'success', 'reload' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zahost', 'browseImage', array("hostID" => $image->host))));
    }

    /**
     * Ajax 方式获取服务状态。
     * Check service status by ajax.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxGetServiceStatus(int $hostID)
    {
        $host          = $this->zahost->getByID($hostID);
        $serviceStatus = $this->zahost->getServiceStatus($host);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $serviceStatus));
    }

    /**
     * Ajax：get hosts.
     *
     * @access public
     * @return void
     */
    public function ajaxGetHosts()
    {
        $hostList = $this->zahost->getPairs();

        $options = array();
        foreach($hostList as $key => $host)
        {
            $options[] = array('text' => $host, 'value' => $key);
        }
        return print(json_encode($options));
    }
}
