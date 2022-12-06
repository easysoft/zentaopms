<?php
/**
 * Get task list by conditions.
 *
 * @param  object           $conds
 * @param  string           $orderBy
 * @param  int              $limit
 * @param  object           $pager
 * @access public
 * @return array
 */
public function getListByConds($conds, $orderBy = 'id_desc', $limit = 0, $pager = null)
{
    foreach(array('priList' => array(), 'assignedToList' => array(), 'statusList' => array(), 'idList' => array(), 'taskName' => '') as $condKey => $defaultValue)
    {
        if(!isset($conds->$condKey)) $conds->$condKey = $defaultValue;
    }

    return $this->dao->select('*')->from(TABLE_TASK)
        ->where('deleted')->eq(0)
        ->beginIF(!empty($conds->priList))->andWhere('pri')->in($conds->priList)->fi()
        ->beginIF(!empty($conds->assignedToList))->andWhere('assignedTo')->in($conds->assignedToList)->fi()
        ->beginIF(!empty($conds->statusList))->andWhere('status')->in($conds->statusList)->fi()
        ->beginIF(!empty($conds->idList))->andWhere('id')->in($conds->idList)->fi()
        ->beginIF(!empty($conds->taskName))->andWhere('name')->like("%{$conds->taskName}%")
        ->orderBy($orderBy)
        ->beginIF($limit > 0)->limit($limit)->fi()
        ->page($pager)
        ->fetchAll('id');
}
