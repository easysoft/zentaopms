<?php
class deliverableModel extends model
{
    public function getList()
    {
        $deliverables = $this->dao->select('*')->from(TABLE_DELIVERABLE)->fetchAll();
        return $deliverables;
    }
}
