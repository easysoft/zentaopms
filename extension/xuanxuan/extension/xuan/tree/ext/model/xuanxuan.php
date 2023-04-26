<?php
public function getFamily($categoryID, $type = '', $root = 0)
{
    if($categoryID == 0 and empty($type)) return array();
    $category = $this->loadModel('dept')->getById($categoryID);

    if($category)
    {
        return $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like($category->path . '%')->fetchPairs();
    }
    if(!$category)
    {
        return $this->dao->select('id')->from(TABLE_DEPT)->beginIF($root)->where('root')->eq((int)$root)->fi()->fetchPairs();
    }
}
