<?php
public function getFamily($categoryID, $type = '', $root = 0)
{
    if($categoryID == 0 and empty($type)) return array();
    $category = $this->getById($categoryID);

    if($category)
    {
        return $this->dao->select('id')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('path')->like($category->path . '%')->fetchPairs();
    }
    if(!$category)
    {
        return $this->dao->select('id')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('type')->eq($type)->beginIF($root)->andWhere('root')->eq((int)$root)->fi()->fetchPairs();
    }
}
