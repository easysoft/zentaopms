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
	return $this->dao->select('*')->from(TABLE_DEPT)->orderBy($orderBy)->fetchAll('id');
}
