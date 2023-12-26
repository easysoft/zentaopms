<?php
/**
 * The model file of vm module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class zanodemodel extends model
{

    const STATUS_CREATED       = 'created';
    const STATUS_LAUNCH        = 'launch';
    const STATUS_FAIL_CREATE   = 'vm_fail_create';
    const STATUS_RUNNING       = 'running';
    const STATUS_SHUTOFF       = 'shutoff';
    const STATUS_BUSY          = 'busy';
    const STATUS_READY         = 'ready';
    const STATUS_UNKNOWN       = 'unknown';
    const STATUS_DESTROY       = 'destroy';
    const STATUS_RESTORING     = 'restoring';
    const STATUS_CREATING_SNAP = 'creating_snap';
    const STATUS_CREATING_IMG  = 'creating_img';
    const STATUS_DESTROY_FAIL  = 'vim_destroy_fail';

    const KVM_CREATE_PATH = '/api/v1/kvm/create';
    const KVM_TOKEN_PATH  = '/api/v1/virtual/getVncToken';
    const KVM_EXPORT_PATH = '/api/v1/kvm/exportVm';
    const KVM_STATUS_PATH = '/api/v1/task/getStatus';

    const SNAPSHOT_CREATE_PATH = '/api/v1/kvm/addCreateSnap';
    const SNAPSHOT_REVERT_PATH = '/api/v1/kvm/addRevertSnap';
    const SNAPSHOT_REMOVE_PATH = '/api/v1/kvm/removeSnap';

    /**
     * 设置语言项。
     * Set lang;
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->app->lang->host = $this->lang->zanode;
    }

    /**
     * 创建执行节点。
     * Create an Node.
     *
     * @param  object $data
     * @access public
     * @return int|bool
     */
    public function create(object $data): int|bool
    {
        $this->dao->insert(TABLE_ZAHOST)->data($data, 'osNamePhysics,osNamePre')->autoCheck()->exec();
        if(dao::isError()) return false;

        $nodeID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('zanode', $nodeID, 'Created');
        return $nodeID;
    }

    /**
     * Create Image by zanode.
     *
     * @param  int    $zanodeID
     * @access public
     * @return int|bool
     */
    public function createImage($zanodeID = 0)
    {
        $data = form::data()->get();

        if(empty($data->name)) dao::$errors['message'][] = $this->lang->zanode->imageNameEmpty;
        if(dao::isError()) return false;

        $node  = $this->getNodeByID($zanodeID);

        $newImage = new stdClass();
        $newImage->host        = $node->parent;
        $newImage->name        = $data->name;
        $newImage->status      = 'created';
        $newImage->osName      = $node->osName;
        $newImage->from        = $node->id;
        $newImage->createdDate = helper::now();

        $this->dao->insert(TABLE_IMAGE)
            ->data($newImage)
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;

        $newID = $this->dao->lastInsertID();

        /* Prepare create params. */
        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap;
        $param    = array(
            'backing' => $data->name,
            'task'    => $newID,
            'vm'      => $node->name
        );

        $result = json_decode(commonModel::http($agnetUrl . static::KVM_EXPORT_PATH, json_encode($param,JSON_NUMERIC_CHECK), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10));

        if(!empty($result) and $result->code == 'success')
        {
            $this->dao->update(TABLE_ZAHOST)->set('status')->eq(static::STATUS_CREATING_IMG)->where('id')->eq($node->id)->exec();
            return $newID;
        }

        $this->dao->delete()->from(TABLE_IMAGE)->where('id')->eq($newID)->exec();
        return false;
    }

    /**
     * 创建快照。
     * Create snapshot.
     *
     * @param  object $node
     * @param  object $snapshot
     * @access public
     * @return false|int
     */
    public function createSnapshot(object $node, object $snapshot): false|int
    {
        $this->dao->insert(TABLE_IMAGE)->data($snapshot)->autoCheck()->exec();
        if(dao::isError()) return false;

        $snapshotID = $this->dao->lastInsertID();

        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap;
        $param    = array(array(
            'name' => $snapshot->name,
            'task' => $snapshotID,
            'type' => 'createSnap',
            'vm'   => $node->name
        ));
        $result = json_decode(commonModel::http($agnetUrl . static::SNAPSHOT_CREATE_PATH, json_encode($param,JSON_NUMERIC_CHECK), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10));

        if(!empty($result) && $result->code == 'success')
        {
            $this->loadModel('action')->create('zanode', $node->id, 'createdSnapshot', '', $snapshot->name);
            $this->dao->update(TABLE_ZAHOST)->set('status')->eq(static::STATUS_CREATING_SNAP)->where('id')->eq($node->id)->exec();

            return $snapshotID;
        }

        $this->dao->delete()->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->exec();
        dao::$errors[] = (!empty($result) and !empty($result->msg)) ? $result->msg : $this->lang->fail;
        return false;
    }

    /**
     * 创建默认的快照。
     * Create default snapshot.
     *
     * @param  int    $zanodeID
     * @access public
     * @return bool
     */
    public function createDefaultSnapshot(int $zanodeID = 0): bool
    {
        $node = $this->getNodeByID($zanodeID);
        if($node->status != 'running')
        {
            dao::$errors['name'] = $this->lang->zanode->apiError['notRunning'];
            return false;
        }

        $defaultSnapshot = new stdClass();
        $defaultSnapshot->host        = $node->id;
        $defaultSnapshot->name        = 'defaultSnap';
        $defaultSnapshot->desc        = '';
        $defaultSnapshot->status      = 'creating';
        $defaultSnapshot->osName      = $node->osName;
        $defaultSnapshot->memory      = 0;
        $defaultSnapshot->disk        = 0;
        $defaultSnapshot->fileSize    = 0;
        $defaultSnapshot->from        = 'snapshot';
        $defaultSnapshot->createdBy   = 'system';
        $defaultSnapshot->createdDate = helper::now();

        $this->createSnapshot($node, $defaultSnapshot);
    }

    /**
     * Edit Snapshot.
     *
     * @param int $snapshotID
     * @access public
     * @return bool
     */
    public function editSnapshot($snapshotID)
    {
        $data = form::data()->get();

        if(empty($data->name)) dao::$errors['name'] = $this->lang->zanode->imageNameEmpty;
        if(dao::isError()) return false;

        if(is_numeric($data->name)) dao::$errors['name'] = sprintf($this->lang->error->code, $this->lang->zanode->name);
        if(dao::isError()) return false;

        $newSnapshot = new stdClass();
        $newSnapshot->localName = $data->name;
        $newSnapshot->desc      = $data->desc;

        $this->dao->update(TABLE_IMAGE)
            ->data($newSnapshot)
            ->where('id')->eq($snapshotID)
            ->autoCheck()
            ->exec();
        return true;
    }

    /**
     * Restore Snapshot to zanode.
     *
     * @param  int    $zanodeID
     * @param  int    $snapshotID
     * @access public
     * @return bool
     */
    public function restoreSnapshot($zanodeID = 0, $snapshotID = 0)
    {
        $node = $this->getNodeByID($zanodeID);
        $snap = $this->getImageByID($snapshotID);

        $snap->status = ($snap->status == 'restoring' && time() - strtotime($snap->restoreDate) > 600) ? 'restore_failed' : $snap->status;
        if(!in_array($snap->status, array('completed', 'restoring', 'restore_failed', 'restore_completed'))) dao::$errors = $this->lang->zanode->snapStatusError;
        if($snap->status == 'restoring') dao::$errors = $this->lang->zanode->snapRestoring;
        if(dao::isError()) return false;

        //update snapshot status
        $this->dao->update(TABLE_IMAGE)
        ->set('status')->eq('restoring')
        ->set('restoreDate')->eq(helper::now())
        ->where('id')->eq($snapshotID)->exec();


        /* Prepare create params. */
        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap;
        $param    = array(array(
            'name'    => $snap->name,
            'task'    => $snapshotID,
            'type'    => 'revertSnap',
            'vm'      => $node->name
        ));

        $result = json_decode(commonModel::http($agnetUrl . static::SNAPSHOT_CREATE_PATH, json_encode($param,JSON_NUMERIC_CHECK), null, array("Authorization:$node->tokenSN"), 'data', 'POST', 10));

        if(!empty($result) and $result->code == 'success')
        {
            $this->dao->update(TABLE_ZAHOST)->set('status')->eq('restoring')->where('id')->eq($node->id)->exec();
            $this->loadModel('action')->create('zanode', $zanodeID, 'restoredsnapshot', '', $snap->name);
            return true;
        }

        $this->dao->update(TABLE_IMAGE)->set('status')->eq('completed')->where('id')->eq($snapshotID)->exec();
        dao::$errors[] = (!empty($result) and !empty($result->msg)) ? $result->msg : $this->lang->zanode->apiError['fail'];
        return false;
    }

    /**
     * Delete Snapshot.
     *
     * @param  int $id
     * @access public
     * @return string|bool
     */
    public function deleteSnapshot($snapshotID)
    {
        $snapshot = $this->getImageByID($snapshotID);
        $node     = $this->getNodeByID($snapshot->host);

        if(!$node) return false;

        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap;
        $param    = (object)array(
            'name' => $snapshot->name,
            'task' => $snapshotID,
            'vm'   => $node->name
        );

        $result = commonModel::http($agnetUrl . static::SNAPSHOT_REMOVE_PATH, json_encode($param,JSON_NUMERIC_CHECK), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10);
        $result = json_decode($result);

        if(!empty($result) and $result->code == 'success')
        {
            $this->dao->delete()->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->exec();
            $this->loadModel('action')->create('zanode', $node->id, 'deletesnapshot', '', $snapshot->name);
            return true;
        }

        $error = (!empty($result) and !empty($result->msg)) ? $result->msg : $this->lang->zanode->apiError['fail'];

        return $error;
    }

    /**
     * Destroy Node.
     *
     * @param  int $id
     * @access public
     * @return string
     */
    public function destroy($id)
    {
        $node = $this->getNodeByID($id);

        if($node && $node->hostType != 'physics')
        {
            $req = array( 'name' => $node->name );
            $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap;
            $result = commonModel::http($agnetUrl . '/api/v1/kvm/remove', json_encode($req), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10);

            $data = json_decode($result, true);
            if(empty($data)) return $this->lang->zanode->notFoundAgent;

            if($data['code'] != 'success') return zget($this->lang->zanode->apiError, $data['code'], $data['msg']);
        }

        /* delete ZenAgent Node. */
        $this->dao->delete()->from(TABLE_ZAHOST)->where('id')->eq($id)->exec();;
        $this->loadModel('action')->create('zanode', $id, 'deleted');
        return '';
    }

    /**
     * 更新一个执行节点。
     * Update Node.
     *
     * @param  int        $id
     * @param  object     $hostInfo
     * @return array|bool
     */
    public function update(int $id, object $hostInfo): array|bool
    {
        $oldHost = $this->getNodeById($id);

        $this->dao->update(TABLE_ZAHOST)
            ->data($hostInfo)
            ->batchCheck($this->config->zanode->edit->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldHost, $hostInfo);
    }

    /**
     * 更新导出镜像的状态。
     * Update Image status.
     *
     * @param  int $imageID
     * @access public
     * @return void
     */
    public function updateImageStatus(int $imageID): void
    {
        $data = form::data()->get();
        $this->dao->update(TABLE_IMAGE)->data($data)->where('id')->eq($imageID)->autoCheck()->exec();
    }

    /**
     * 获取执行节点列表。
     * Get zanode list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getListByQuery(string $browseType = 'all', int $param = 0, string $orderBy = 't1.id_desc', object $pager = null): array
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            if($param)
            {
                $query = $this->loadModel('search')->getQuery($param);
                if($query)
                {
                    $this->session->set('zanodeQuery', $query->sql);
                    $this->session->set('zanodeForm',  $query->form);
                }
                else
                {
                    $this->session->set('zanodeQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->zanodeQuery == false) $this->session->set('zanodeQuery', ' 1 = 1');
            }
            $query = $this->session->zanodeQuery;
            $query = preg_replace('/`(id|name|status|os|parent|cpuCores|memory|diskSize|extranet)`/', 't1.`\1`', $query);
            $query = str_replace('`hostID`', 't2.`id`', $query);
        }

        $nodeList  = $this->zanodeTao->getZaNodeListByQuery($query, $orderBy, $pager);
        $hosts = $this->zanodeTao->getHostsByIDList(array_unique(array_column($nodeList, 'parent')));

        foreach($nodeList as $node)
        {
            $node->heartbeat = empty($node->heartbeat) ? '' : $node->heartbeat;
            $host            = $node->hostType == '' ? zget($hosts, $node->parent) : clone $node;
            if(is_object($host))
            {
                $host->status    = in_array($host->status, array('running', 'ready')) ? 'online' : $host->status;
                $host->heartbeat = empty($host->heartbeat) ? '' : $host->heartbeat;
            }

            if($node->status == 'running' || $node->status == 'ready')
            {
                if(!is_object($host))
                {
                    $node->status = self::STATUS_SHUTOFF;
                    continue;
                }

                if($host->status != 'online' || time() - strtotime($host->heartbeat) > 60) $node->status = $node->hostType == '' ? 'wait' : 'offline';
            }
        }

        return $nodeList;
    }

    /**
     * 获取执行节点的id-name键值对。
     * Get node id-name pairs.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairs(string $orderBy = 'id_desc'): array
    {
        return $this->dao->select('id,name')->from(TABLE_ZAHOST)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('node,physics')
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 通过宿主机ID获取执行节点。
     * Get node list by hostID
     *
     * @param  int    $hostID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getListByHost(int $hostID, string $orderBy = 'id_desc'): array
    {
        if(!$hostID) return array();

        $list = $this->zanodeTao->getSubZahostListByID($hostID, $orderBy);
        $host = $this->loadModel('zahost')->getByID($hostID);

        foreach($list as $node)
        {
            $node->heartbeat = empty($node->heartbeat) ? '' : $node->heartbeat;

            if($node->status == 'running' || $node->status == 'ready')
            {
                if(empty($host) || $host->status != 'online')
                {
                    $node->status = self::STATUS_SHUTOFF;
                }
                elseif(time() - strtotime($node->heartbeat) > 60)
                {
                    $node->status = 'wait';
                }
            }
        }

        return $list;
    }


    /**
     * 通过ID获取host。
     * Get Host by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getHostByID($id)
    {
        return $this->dao->select('*')->from(TABLE_ZAHOST)
            ->where('id')->eq($id)
            ->fetch();
    }

    /**
     * 通过ip获取host。
     * Get Host by IP.
     *
     * @param  string $ip
     * @access public
     * @return object
     */
    public function getHostByIP($ip)
    {
        return $this->dao->select('*')->from(TABLE_ZAHOST)
            ->where('extranet')->eq($ip)
            ->fetch();
    }

    /**
     * 通过 id 获取镜像。
     * Get Image by id.
     *
     * @param  int          $id
     * @access public
     * @return object|false
     */
    public function getImageByID(int $id): object|bool
    {
        return $this->dao->select('*')->from(TABLE_IMAGE)->where('id')->eq($id)->fetch();
    }

    /**
     * 获取自定义的镜像。
     * Get custom image.
     *
     * @param  int          $nodeID
     * @param  string|array $status
     * @param  string       $orderBy
     * @access public
     * @return object|false
     */
    public function getCustomImage(int $nodeID = 0, string|array $status = '', string $orderBy = 'id_desc'): object|false
    {
        return $this->dao->select('*')->from(TABLE_IMAGE)
            ->where('`from`')->eq($nodeID)
            ->beginIF($status)->andWhere('status')->in($status)->fi()
            ->orderBy($orderBy)
            ->fetch();
    }

    /**
     * 获取快照列表。
     * Get snapshot list.
     *
     * @param  int    $nodeID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSnapshotList(int $nodeID, string $orderBy = 'id', object $pager = null): array
    {
        $snapshotList = $this->dao->select('*')->from(TABLE_IMAGE)
            ->where('host')->eq($nodeID)
            ->andWhere('`from`')->eq('snapshot')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('name');

        foreach($snapshotList as $name => $snapshot)
        {
            if($snapshot->status == 'creating' && (time() - strtotime($snapshot->createdDate)) > 600)
            {
                if($snapshot->name == 'defaultSnap' && $snapshot->createdBy == "system")
                {
                    $this->dao->delete()->from(TABLE_IMAGE)->where('id')->eq($snapshot->id)->exec();
                    $this->dao->update(TABLE_ZAHOST)->data(array("status" => "wait"))->where("id")->eq($snapshot->host)->exec();
                    continue;
                }

                $this->dao->update(TABLE_IMAGE)->set('status')->eq('failed')->where('id')->eq($snapshot->id)->exec();
                $snapshotList[$name]->status = 'failed';
            }
        }

        return $snapshotList;
    }

    /**
     * 通过 id 获取执行节点。
     * Get Node by id.
     *
     * @param  int          $id
     * @access public
     * @return object|false
     */
    public function getNodeByID(int $id): object|bool
    {
        $node = $this->dao->select("t1.*, t2.name as hostName, if(t1.hostType='', t2.extranet, t1.extranet) ip,t2.zap as hzap,if(t1.hostType='', t3.osName, t1.osName) osName, if(t1.hostType='', t2.tokenSN, t1.tokenSN) tokenSN, if(t1.hostType='', t2.secret, t1.secret) secret")
            ->from(TABLE_ZAHOST)->alias('t1')
            ->leftJoin(TABLE_ZAHOST)->alias('t2')->on('t1.parent = t2.id')
            ->leftJoin(TABLE_IMAGE)->alias('t3')->on('t3.id = t1.image')
            ->where('t1.id')->eq($id)
            ->fetch();
        if(empty($node)) return false;
        return $this->processNodeStatus($node);
    }

    /**
     * 通过 mac 地址获取执行节点。
     * Get Node by mac address.
     *
     * @param  string       $mac
     * @access public
     * @return object|false
     */
    public function getNodeByMac(string $mac): object|bool
    {
        $node = $this->dao->select('*')->from(TABLE_ZAHOST)->where('mac')->eq($mac)->fetch();
        if(empty($node)) return false;
        return $this->processNodeStatus($node);
    }

    /**
     * 计算执行节点的状态。
     * Process node status.
     *
     * @param  object    $node
     * @access protected
     * @return object
     */
    protected function processNodeStatus(object $node): object
    {
        $host = $node->hostType == '' ? $this->loadModel('zahost')->getByID($node->parent) : clone $node;
        $host->status = in_array($host->status, array('running', 'ready')) ? 'online' : $host->status;

        if($node->status == 'running' || $node->status == 'ready' || $node->status == 'online')
        {
            if(empty($host) || $host->status != 'online')
            {
                $node->status = self::STATUS_SHUTOFF;
            }
            elseif(time() - strtotime($node->heartbeat) > 60)
            {
                $node->status = $node->hostType == '' ? 'wait' : 'offline';
            }
        }

        return $node;
    }

    /**
     * Get vnc url.
     *
     * @param  object      $node
     * @access public
     * @return object|bool
     */
    public function getVncUrl($node)
    {
        if(empty($node) or empty($node->parent) or empty($node->vnc)) return false;

        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap . static::KVM_TOKEN_PATH;
        $result   = json_decode(commonModel::http("$agnetUrl?port={$node->vnc}", array(), array(CURLOPT_CUSTOMREQUEST => 'GET'), array("Authorization:$node->tokenSN"), 'json', 'POST', 10));

        if(empty($result) or $result->code != 'success') return false;

        $returnData = new stdClass();
        $returnData->hostIP    = $node->ip;
        $returnData->agentPort = $node->hzap;
        $returnData->vnc       = $node->vnc;
        $returnData->token     = $result->data->token;
        return $returnData;
    }

    /**
     * Get task status by zagent api.
     *
     * @param  object     $node
     * @param  int        $taskID
     * @param  string     $type
     * @param  string     $status
     * @access public
     * @return array|bool
     */
    public function getTaskStatus($node, $taskID = 0, $type = '', $status = '')
    {
        $agnetUrl = 'http://' . $node->ip . ':' . $node->hzap . static::KVM_STATUS_PATH;
        $result   = json_decode(commonModel::http($agnetUrl, array(), array(CURLOPT_CUSTOMREQUEST => 'POST'), array("Authorization:$node->tokenSN"), 'json', 'POST', 10));

        if(empty($result) or $result->code != 'success') return false;
        $data = $result->data;
        if(empty($data)) return array();

        if($status and !$taskID and isset($data->$status)) return $data->$status;

        if(!$taskID) return $data;

        foreach($data as $status => $tasks)
        {
            if(empty($tasks)) continue;

            foreach($tasks as $task)
            {
                if(!empty($tasks['inprogress']) && $task->task != $tasks['inprogress'][0]->task && $task->status == 'created') $task->status = 'pending';
                if($type == $task->type and $taskID == $task->task) return $task;
            }
        }

        return $result;
    }

    /**
     * 获取宿主机中zagent、nginx、websockify、novnc的运行及安装状态.
     * Get service status from host.
     *
     * @param  object $node
     * @access public
     * @return array
     */
    public function getServiceStatus($node)
    {
        $result = json_decode(commonModel::http("http://{$node->ip}:{$node->zap}/api/v1/service/check", json_encode(array('services' => 'all')), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10));

        if(empty($result->data->ztfStatus) || $result->code != 'success')
        {
            $result = new stdclass;
            $result->data = $this->lang->zanode->init->serviceStatus;

            return $result->data;
        }

        $result->data = array(
            'ZenAgent' => 'ready',
            'ZTF'      => $result->data->ztfStatus,
        );

        return $result->data;
    }

    /**
     * 执行节点安装应用（支持ztf,zendata）.
     * Install service to node by name.
     *
     * @param  object $node
     * @param  string $name
     * @access public
     * @return array
     */
    public function installService($node, $name)
    {
        $param = array(
            'name'   => strtolower($name),
            'secret' => $node->secret,
            'server' => getWebRoot(true),
        );
        $result = json_decode(commonModel::http("http://{$node->ip}:{$node->zap}/api/v1/service/setup", json_encode($param), array(), array("Authorization:$node->tokenSN")));

        if(empty($result->data) || $result->code != 'success')
        {
            $result = new stdclass;
            return $this->lang->zanode->init->serviceStatus;
        }

        return array(
            'ZenAgent' => 'ready',
            'ZTF'      => $result->data->ztfStatus,
        );
    }

    /**
     * 通过 product id 获取自动化设置。
     * Get automation by product id.
     *
     * @param  int          $productID
     * @access public
     * @return object|false
     */
    public function getAutomationByProduct(int $productID = 0): object|bool
    {
        return $this->dao->select('*')->from(TABLE_AUTOMATION)->where('product')->eq($productID)->fetch();
    }

    /**
     * 通过 id 获取自动化设置。
     * Get automation by id.
     *
     * @param  int          $id
     * @access public
     * @return object|false
     */
    public function getAutomationByID(int $id): object|bool
    {
        return $this->dao->select('*')->from(TABLE_AUTOMATION)->where('id')->eq($id)->fetch();
    }

    /**
     * 自动化设置。
     * Set automation setting.
     *
     * @param  object    $object
     * @access public
     * @return int|false
     */
    public function setAutomationSetting(object $object): int|bool
    {
        $this->dao->replace(TABLE_AUTOMATION)
            ->data($object)
            ->batchCheck('node,scriptPath', 'notempty')
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * Run ZTFScript.
     *
     * @param  int    $scriptID
     * @param  int    $caseID
     * @param  int    $task     //testtaskID
     * @access public
     * @return void
     */
    public function runZTFScript($scriptID = 0, $caseID = 0, $task = 0)
    {
        $automation = $this->getAutomationByID($scriptID);
        $node       = $this->getNodeByID($automation->node);

        if(empty($node) or $node->status != 'running' or !$node->ip or !$node->ztf or !$node->tokenSN)
        {
            $this->dao->delete()->from(TABLE_TESTRESULT)->where('id')->eq($task)->exec();
            return  dao::$errors = $this->lang->zanode->runTimeout;
        }

        $params = array(
            'cmd'  => $automation->shell,
            'ids'  => strval($caseID),
            'path' => $automation->scriptPath,
            'task' => intval($task)
        );

        $result = json_decode(commonModel::http("http://{$node->ip}:{$node->ztf}/api/v1/jobs/add", json_encode($params), array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10));
        if(empty($result) or $result->code != 0)
        {
            $this->dao->delete()->from(TABLE_TESTRESULT)->where('id')->eq($task)->exec();
            return  dao::$errors = $this->lang->zanode->runTimeout;
        }
    }

    /**
     * Sync cases in dir to zentao.
     *
     * @access public
     * @return void
     */
    public function syncCasesToZentao($path)
    {
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $node
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $node, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'resume') return $node->status == 'suspend' && $node->hostType != 'physics';
        if($action == 'start')  return $node->status == 'shutoff' && $node->hostType != 'physics';
        if($action == 'getvnc') return $node->hostType == '' && in_array($node->status, array('running', 'launch', 'wait'));
        if($action == 'close'   || $action == 'reboot') return $node->hostType != 'physics' && !in_array($node->status, array('wait', 'creating_img', 'creating_snap', 'restoring', 'shutoff'));
        if($action == 'suspend' || $action == 'createsnapshot' || $action == 'createimage') return $node->status == 'running' && $node->hostType != 'physics';

        return true;
    }

    /**
     * 检查创建字段。
     * Check fields of create.
     *
     * @param  object $data
     * @access public
     * @return bool
     */
    public function checkFields4Create(object $data): bool
    {
        /* 检查必填项。*/
        /* Check required fields. */
        $this->dao->update(TABLE_ZAHOST)->data($data)
            ->batchCheck($data->hostType != 'physics' ? $this->config->zanode->create->requiredFields : $this->config->zanode->create->physicsRequiredFields, 'notempty');
        if(dao::isError()) return false;

        /* 检查名称格式。*/
        /* Check the style of name. */
        if(!preg_match("/^(?!_)(?!-)(?!\.)[a-zA-Z0-9\_\.\-]+$/", $data->name))
        {
            dao::$errors['name'] = $this->lang->zanode->nameValid;
            return false;
        }

        /* 检查名称的唯一性。*/
        /* If name already exists return error. */
        $node = $this->dao->select('*')->from(TABLE_ZAHOST)->where('name')->eq($data->name)->andWhere('type')->eq('node')->fetch();
        if($node)
        {
            dao::$errors['name'] = $this->lang->zanode->nameUnique;
            return false;
        }

        /* 检查网络状态。*/
        /* Check the status of network. */
        if($data->hostType == 'physics')
        {
            $ping = $this->loadModel('zahost')->checkAddress($data->extranet);
            if(!$ping)
            {
                dao::$errors['extranet'] = $this->lang->zanode->netError;
                return false;
            }
        }

        return true;
    }

    /**
     * 连接Agent服务。
     * Link agent service.
     *
     * @param  object $data
     * @access public
     * @return bool
     */
    public function linkAgentService(object $data): false|object
    {
        $image    = $this->getImageByID($data->image);
        $host     = $this->getHostByID($data->parent);
        $agentUrl = 'http://' . $host->extranet . ':' . $host->zap;
        $param    = array(
            'os'     => $image->osName,
            'path'   => $image->path,
            'name'   => $data->name,
            'cpu'    => (int)$data->cpuCores,
            'disk'   => (int)$data->diskSize,
            'memory' => (int)$data->memory,
        );
        $result = json_decode(commonModel::http($agentUrl . static::KVM_CREATE_PATH, json_encode($param), array(), array("Authorization:$host->tokenSN"), 'data', 'POST', 10));

        if(empty($result))
        {
            dao::$errors['image'] = $this->lang->zanode->notFoundAgent;
            return false;
        }
        if($result->code != 'success')
        {
            dao::$errors['image'] = $this->lang->zanode->createVmFail;
            return false;
        }

        return $result;
    }
}
