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
}
