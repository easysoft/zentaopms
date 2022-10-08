<?php    
/**
     * Get list of one type.
     * 
     * @param  string $type 
     * @param  string $orderBy
     * @access public
     * @return array
     */
public function getListByType($type = 'article', $orderBy = 'id_asc')
{
    $depts   = $this->dao->select('*')->from(TABLE_DEPT)->orderBy('grade,`order`')->fetchAll('id');
    $manager = array();
    foreach($depts as $dept)
    {
        $dept->manager = $dept->manager ? ",{$dept->manager}," : zget($manager, $dept->parent, '');
        $manager[$dept->id] = $dept->manager;
    }
    return $depts;
}
