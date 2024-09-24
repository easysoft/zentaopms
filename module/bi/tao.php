<?php
declare(strict_types=1);
class biTao extends biModel
{
    /**
     * Fetch tables.
     *
     * @access protected
     * @return array
     */
    protected function fetchAllTables()
    {
        return $this->dao->select("table_name as 'table'")
            ->from('information_schema.tables')
            ->where('table_type')->eq('BASE TABLE')
            ->andWhere('table_schema')->eq($this->config->db->name)
            ->fetchPairs();
    }

    /**
     * Fetch table queue.
     *
     * @access protected
     * @return array
     */
    protected function fetchTableQueue()
    {
        return $this->dao->select('object')->from(TABLE_DUCKDBQUEUE)
            ->where('updatedTime >= syncTime')
            ->orWhere('syncTime IS NULL')
            ->fetchPairs();
    }

    /**
     * Update sync time.
     *
     * @param  array    $tables
     * @access protected
     * @return void
     */
    protected function updateSyncTime($tables)
    {
        $this->dao->update(TABLE_DUCKDBQUEUE)
            ->set('syncTime')->eq(helper::now())
            ->where('object')->in($tables)
            ->exec();
    }

}
