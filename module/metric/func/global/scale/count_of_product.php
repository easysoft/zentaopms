<?php
include dirname(__FILE__, 4) . DS . 'func.class.php';
class count_of_product extends func
{
    public $dao = null;

    public function getResult()
    {
        return $this->dao->select('count(id)')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->fetch();
    }
}
