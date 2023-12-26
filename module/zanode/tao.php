<?php
declare(strict_types=1);
class zanodeTao extends zanodeModel
{
    /**
     * 通过主机ID获取此主机下所有的子主机。
     * Get all sub hosts by host ID.
     *
     * @param  int    $hostID
     * @param  string $orderBy
     * @access protected
     * @return array
     */
    protected function getSubZahostListByID(int $hostID, string $orderBy): array
    {
        return $this->dao->select('id, name, vnc, cpuCores, memory, diskSize, osName, status, heartbeat')->from(TABLE_ZAHOST)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq($hostID)
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * 检查创建字段。
     * Check fields of create.
     *
     * @param  object $data
     * @access protected
     * @return bool
     */
    protected function checkFields4Create(object $data): bool
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
     * @access protected
     * @return bool
     */
    protected function linkAgentService(object $data): false|object
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

    /**
     * 通过查询条件获取执行节点列表。
     * Get host list by query.
     *
     * @param  string    $query
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getZaNodeListByQuery(string $query, string $orderBy, object|null $pager): array
    {
        return $this->dao->select("t1.*, t2.name as hostName, if(t1.hostType='', t2.extranet, t1.extranet) extranet,if(t1.hostType='', t3.osName, t1.osName) osName")->from(TABLE_ZAHOST)->alias('t1')
            ->leftJoin(TABLE_ZAHOST)->alias('t2')->on('t1.parent = t2.id')
            ->leftJoin(TABLE_IMAGE)->alias('t3')->on('t3.id = t1.image')
            ->where('t1.deleted')->eq(0)
            ->andWhere("t1.type = 'node'")
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 通过主机ID列表获取主机列表。
     * Get host list by host ID list.
     *
     * @param  array    $hostIDList
     * @access protected
     * @return array
     */
    protected function getHostsByIDList(array $hostIDList): array
    {
        return $this->dao->select('id,status,heartbeat')->from(TABLE_ZAHOST)
            ->where('id')->in($hostIDList)
            ->fetchAll('id');
    }
}
