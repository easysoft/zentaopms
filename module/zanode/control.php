<?php
/**
 * The control file of ZenAgent Node of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liyuchun <liyuchun@easycorp.ltd>
 * @package     qa
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class zanode extends control
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->zanode->setMenu();
    }

    /**
     * 执行节点介绍页。
     * View ZenAgent Node instruction.
     *
     * @access public
     * @return void
     */
    public function instruction()
    {
        $this->view->title = $this->lang->zanode->instruction;
        $this->display();
    }

    /**
     * 执行节点列表页。
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
    public function browse(string $browseType = 'all', string $param = '', string $orderBy = 't1.id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $browseType = strtolower($browseType);

        /* 加载分页器。*/
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $queryID = $browseType == 'bysearch' ? (int)$param : 0;
        $pager   = pager::init($recTotal, $recPerPage, $pageID);

        /* 构建搜索表单。*/
        /* Build the search form. */
        $hosts     = $this->loadModel('zahost')->getPairs('host');
        $actionURL = $this->createLink('zanode', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->zanode->search['actionURL'] = $actionURL;
        $this->config->zanode->search['queryID']   = $queryID;
        $this->config->zanode->search['onMenuBar'] = 'no';
        $this->config->zanode->search['params']['host']['values'] = array('' => '') + $hosts;
        $this->loadModel('search')->setSearchParams($this->config->zanode->search);

        $hiddenHost = $this->zahost->hiddenHost();
        if($hiddenHost)
        {
            foreach(array('type', 'cpuCores', 'memory', 'diskSize', 'hostName') as $disableField)
            {
                unset($this->config->zanode->dtable->fieldList[$disableField]);
                unset($this->config->zanode->search['fields'][$disableField]);
                unset($this->config->zanode->search['params'][$disableField]);
            }

            unset($this->config->zanode->search['fields']['host']);
            unset($this->config->zanode->search['params']['host']);
            foreach($this->lang->zanode->statusList as $statusKey => $statusValue)
            {
                if(!in_array($statusKey, array('online', 'offline'))) unset($this->lang->zanode->statusList[$statusKey]);
            }
            $this->config->zanode->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $this->lang->zanode->statusList);

            $this->config->zanode->dtable->fieldList['actions']['menu'] = array('edit', 'destroy');
            $this->loadModel('search')->setSearchParams($this->config->zanode->search);
        }

        $this->view->title       = $this->lang->zanode->common;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->nodeList    = $this->zanode->getListByQuery($browseType, $queryID, $orderBy, $pager);
        $this->view->hiddenHost  = $hiddenHost;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;

        $this->display();
    }

    /**
     * 在宿主机详情页中展示执行节点列表。
     * Browse ZenAgent Node list in zahost view.
     *
     * @param  int    $hostID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function nodeList(int $hostID, string $orderBy = 'id_desc')
    {
        if(!commonModel::hasPriv('zanode', 'browse')) $this->loadModel('common')->deny('zanode', 'browse', false);

        $this->view->title    = $this->lang->zanode->common;
        $this->view->nodeList = $this->zanode->getListByHost($hostID, $orderBy);
        $this->view->orderBy  = $orderBy;
        $this->view->hostID   = $hostID;
        $this->view->sortLink = $this->createLink('zanode', 'nodeList', "hostID={$hostID}&orderBy={orderBy}");

        $this->display();
    }

    /**
     * 创建执行节点。
     * Create node.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function create(int $hostID = 0)
    {
        if(!empty($_POST))
        {
            $data   = $this->zanodeZen->prepareCreateExtras();
            $nodeID = $this->zanode->create($data);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "id=$nodeID")));
        }

        $this->view->title      = $this->lang->zanode->create;
        $this->view->hostPairs  = array('' => '') + $this->loadModel('zahost')->getPairs($this->session->product);
        $this->view->hiddenHost = $this->loadModel('zahost')->hiddenHost();
        $this->view->hostID     = $hostID;

        return $this->display();
    }

    /**
     * 编辑执行节点。
     * Edit node.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        if($_POST)
        {
            $hostInfo = form::data($this->config->zanode->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', helper::now())
                ->get();

            $this->config->zanode->create->requiredFields = 'name';
            $checkResult = $this->zanode->checkFields4Create($hostInfo);

            if(!$checkResult) return $this->sendError(dao::getError());

            $changes = $this->zanode->update($id, $hostInfo);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('zanode', $id, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse')));
        }

        $zanode = $this->zanode->getNodeByID($id);

        $this->view->title  = $this->lang->zanode->editAction;
        $this->view->zanode = $zanode;
        if($zanode->type == 'node')
        {
            $this->view->host  = $this->zanode->getHostByID($zanode->parent);
            $this->view->image = $this->zanode->getImageByID($zanode->image);
        }
        $this->view->hiddenHost = $this->loadModel('zahost')->hiddenHost();
        $this->display();
    }

    /**
     * 执行节点详情。
     * View Node.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $node = $this->zanode->getNodeByID($id);
        $vnc  = $this->zanode->getVncUrl($node);

        $this->view->url          = $node->ip . ":" . $node->hzap;
        $this->view->host         = !empty($vnc->hostIP) ? $vnc->hostIP:'';
        $this->view->token        = !empty($vnc->token) ? $vnc->token:'';
        $this->view->title        = $this->lang->zanode->view;
        $this->view->zanode       = $node;
        $this->view->snapshotList = $this->zanode->getSnapshotList($id);
        $this->view->initBash     = sprintf(zget($this->config->zanode->versionToOs, $node->osName, '') != '' ? $this->config->zanode->initPosh : $this->config->zanode->initBash, $node->secret, getWebRoot(true));
        $this->view->actions      = $this->loadModel('action')->getList('zanode', $id);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->hiddenHost   = $this->loadModel('zahost')->hiddenHost();

        $this->display();
    }

    /**
     * 开始执行节点。
     * Start Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function start(int $nodeID)
    {
        $this->zanodeZen->handleNode($nodeID, 'boot');
    }

    /**
     * 关闭执行节点。
     * Shutdown Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function close(int $nodeID)
    {
        $this->zanodeZen->handleNode($nodeID, 'destroy');
    }

    /**
     * 休眠执行节点。
     * Suspend Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function suspend(int $nodeID)
    {
        $this->zanodeZen->handleNode($nodeID, 'suspend');
    }

    /**
     * 重启执行节点。
     * Reboot Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function reboot(int $nodeID)
    {
        $this->zanodeZen->handleNode($nodeID, 'reboot');
    }

    /**
     * 恢复执行节点。
     * Resume Node.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function resume(int $nodeID)
    {
        $this->zanodeZen->handleNode($nodeID, 'resume');
    }

    /**
     * 导出镜像。
     * Create custom image.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function createImage(int $nodeID = 0)
    {
        if($_POST)
        {
            $imageData = form::data()->get();
            $this->zanode->createImage($nodeID, $imageData);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $task        = '';
        $node        = $this->zanode->getNodeByID($nodeID);
        $customImage = $this->zanode->getCustomImage($nodeID, 'created,inprogress');
        if($customImage) $task = $this->zanodeZen->getTaskStatus($node, $customImage->id, 'exportVm');

        $this->view->task = $task;
        $this->view->node = $node;
        $this->view->rate = isset($task->rate) ? $task->rate : 0;
        $this->display();
    }

    /**
     * 创建快照。
     * Create snapshot.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function createSnapshot(int $nodeID = 0)
    {
        if($_POST)
        {
            $node     = $this->zanode->getNodeByID($nodeID);
            $snapshot = $this->zanodeZen->prepareCreateSnapshotExtras($node);
            $this->zanode->createSnapshot($node, $snapshot);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->node = $this->zanode->getNodeByID($nodeID);
        $this->display();
    }

    /**
     * 编辑快照。
     * Edit Snapshot.
     *
     * @param  int $snapshotID
     * @access public
     * @return void
     */
    public function editSnapshot(int $snapshotID)
    {
        $snapshot = $this->zanode->getImageByID($snapshotID);

        if($_POST)
        {
            $formData = form::data()->get();
            if(is_numeric($formData->name)) return $this->sendError(array('name' => sprintf($this->lang->error->code, $this->lang->zanode->name)));

            $this->zanode->editSnapshot($snapshotID, $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('zanode', $snapshot->host, 'editSnapshot', '', $snapshot->localName ? $snapshot->localName : $snapshot->name);
            return $this->sendSuccess(array('load' => inLink('view', "id=$snapshot->host")));
        }

        $this->view->snapshot = $snapshot;
        $this->display();
    }

    /**
     * 删除快照。
     * Delete Snapshot.
     *
     * @param  int    $snapshotID
     * @access public
     * @return void
     */
    public function deleteSnapshot(int $snapshotID)
    {
        $result = $this->zanode->deleteSnapshot($snapshotID);
        if($result !== true) return $this->sendError($result, true);

        return $this->sendSuccess(array('message' => $this->lang->zanode->actionSuccess, 'load' => true));
    }

    /**
     * 销毁执行节点。
     * Desctroy node.
     *
     * @param  int $nodeID
     * @access public
     * @return void
     */
    public function destroy(int $nodeID)
    {
        $error = $this->zanode->destroy($nodeID);
        if(!empty($error)) return $this->sendError($error);

        return $this->sendSuccess(array('message' => $this->lang->zanode->actionSuccess, 'load' => inlink('browse')));
    }

    /**
     * 远程操控执行节点。
     * Bring up novmc management view.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function getVNC(int $nodeID)
    {
        $node = $this->zanode->getNodeByID($nodeID);
        $vnc  = $this->zanode->getVncUrl($node);

        /* 记录操作日志。*/
        /* Add action log. */
        if(!empty($vnc->token)) $this->loadModel('action')->create('zanode', $nodeID, 'getVNC');

        $this->view->title = $this->lang->zanode->getVNC;
        $this->view->url   = $node->ip . ":" . $node->hzap;
        $this->view->host  = !empty($vnc->hostIP) ? $vnc->hostIP:'';
        $this->view->token = !empty($vnc->token) ? $vnc->token:'';
        $this->display();
    }

    /**
     * 快照列表。
     * Browse snapshot.
     *
     * @param int    $nodeID
     * @param string $browseType
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function browseSnapshot(int $nodeID, string $browseType = 'all', string $orderBy = 'id', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadLang('zahost');
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->zanode->browseSnapshot;
        $this->view->nodeID       = $nodeID;
        $this->view->node         = $this->zanode->getNodeByID($nodeID);
        $this->view->snapshotList = $this->zanode->getSnapshotList($nodeID, $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;

        $this->display();
    }

    /**
     * 还原快照。
     * Restore node.
     *
     * @param  int  $nodeID
     * @param  int  $snapshotID
     * @access public
     * @return void
     */
    public function restoreSnapshot(int $nodeID, int $snapshotID)
    {
        $this->zanode->restoreSnapshot($nodeID, $snapshotID);
        if(dao::isError()) return $this->sendError(dao::getError(), true);

        return $this->sendSuccess(array('message' => $this->lang->zanode->actionSuccess, 'load' => true));
    }

    /**
     * 获取镜像列表。
     * AJAX: Get template pairs by api.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxGetImages(int $hostID)
    {
        $options       = array();
        $templatePairs = $this->loadModel('zahost')->getImagePairs($hostID);
        foreach($templatePairs as $key => $template) $options[] = array('text' => $template, 'value' => $key);
        return print(json_encode($options));
    }

    /**
     * 获取镜像信息。
     * AJAX: Get template info.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function ajaxGetImage(int $imageID)
    {
        $template = $this->loadModel('zahost')->getImageByID($imageID);
        return print(json_encode($template));
    }

    /**
     * 获取导出镜像的状态。
     * AJAX: Get task status.
     *
     * @param  int    $nodeID
     * @param  int    $taskID
     * @param  string $type
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetTaskStatus(int $nodeID, int $taskID = 0, string $type = '', string $status = '')
    {
        $node   = $this->zanode->getNodeByID($nodeID);
        $result = $this->zanodeZen->getTaskStatus($node, $taskID, $type, $status);
        return print(json_encode($result));
    }

    /**
     * 更新导出镜像状态。
     * AJAX: Update image.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function ajaxUpdateImage(int $imageID = 0)
    {
        if($_POST)
        {
            $data = form::data()->get();
            $this->zanode->updateImageStatus($imageID, $data);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true));
        }
    }

    /**
     * 获取宿主机服务状态。
     * AJAX: Get service status.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function ajaxGetServiceStatus(int $hostID)
    {
        $node          = $this->zanode->getNodeById($hostID);
        $serviceStatus = $this->zanodeZen->getServiceStatus($node);
        if($node->status != 'running')
        {
            $serviceStatus['ZenAgent'] = 'unknown';
            $serviceStatus['ZTF']      = 'unknown';
        }
        $node->status = $node->status == 'online' ? 'ready' : $node->status;
        $serviceStatus['node'] = $node->status;

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $serviceStatus));
    }

    /**
     * 安装服务。
     * AJAX: Install service by ajax.
     *
     * @param  int    $nodeID
     * @param  string $service
     * @access public
     * @return void
     */
    public function ajaxInstallService(int $nodeID, string $service)
    {
        $node   = $this->zanode->getNodeById($nodeID);
        $result = $this->zanodeZen->installService($node, $service);

        return $this->send(array('result' => 'success', 'message' => '', 'data' => $result));
    }

    /**
     * 获取ZTF脚本。
     * AJAX: Get ZTF script.
     *
     * @param string $type
     * @param int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetZTFScript(string $type = 'product', int $objectID = 0)
    {
        $script = array();
        if($type == '')        $script = $this->zanode->getAutomationByID($objectID);
        if($type == 'product') $script = $this->zanode->getAutomationByProduct($objectID);
        return $this->send(array('result' => 'success', 'data' => $script));
    }

    /**
     * 执行ZTF脚本。
     * AJAX: Run ZTF script.
     *
     * @param  int    $scriptID
     * @access public
     * @return void
     */
    public function ajaxRunZTFScript(int $scriptID = 0)
    {
        if($_POST)
        {
            $caseIdList = $_POST['caseIdList'];
            $runIdList  = empty($_POST['runIDList']) ? array() : $_POST['runIDList'];
            $script     = $this->zanode->getAutomationByID($scriptID);
            $cases      = $this->loadModel('testcase')->getByList($caseIdList);

            $case2RunMap = array();
            foreach($caseIdList as $index => $caseID) $case2RunMap[$caseID] = empty($runIDList[$index]) ? 0 : $runIDList[$index];

            foreach($cases as $id => $case)
            {
                if($case->auto != 'auto') continue;
                if(empty($script->node)) continue;
                $resultID = $this->loadModel('testtask')->initResult($case2RunMap[$id], $id, $case->version, $script->node);
                if(!dao::isError()) $this->zanode->runZTFScript($script->id, $id, $resultID);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => 'success'));
        }
    }

    /**
     * 获取执行节点。
     * AJAX：Get nodes.
     *
     * @access public
     * @return void
     */
    public function ajaxGetNodes()
    {
        $nodeList = $this->zanode->getPairs();
        return print(html::select("node", $nodeList, '', "class='form-control picker-select'"));
    }
}
