<?php
class dataset
{
    public $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function getAllExecutions($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_PROJECT)
            ->where('deleted')->eqw(0)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->query();
    }

    public function getReleaseList($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.hasProduct')->eq(1)
            ->query();
    }
}
