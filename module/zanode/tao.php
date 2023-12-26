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
