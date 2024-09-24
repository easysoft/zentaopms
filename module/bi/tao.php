<?php
declare(strict_types=1);
class biTao extends biModel
{
    protected function fetchTables()
    {
        return $this->dao->select("table_name as 'table'")
            ->from('information_schema.tables')
            ->where('table_type')->eq('BASE TABLE')
            ->andWhere('table_schema')->eq($this->config->db->name)
            ->fetchPairs();
    }

}
