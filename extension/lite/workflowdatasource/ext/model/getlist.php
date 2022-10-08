<?php
/**
 * Get datasource list.
 *
 * @param  string $orderBy
 * @param  objcet $pager
 * @access public
 * @return array
 */
public function getList($orderBy = 'id_desc', $pager = null)
{
    return $this->dao->select('*')->from(TABLE_WORKFLOWDATASOURCE)
        ->where(1)
        ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
        ->beginIF($this->config->visions == ',lite,')->andWhere('code')->notin($this->config->workflowdatasource->excludeDatasource)->fi()
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll();
}
