<?php
class feedbackModel extends model
{
    public function getFeedbackPairs($type)
    {
        return array('admin' => 'Admin', 'user1' => 'User1');
    }

    /**
     * Get feedback by list.
     *
     * @param  string $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        if(empty($idList)) return array();
        return $this->dao->select('*')->from(TABLE_FEEDBACK)
            ->where('id')->in($idList)
            ->fetchAll('id');
    }

    /**
     * Get feedback list by condition.
     *
     * @param  string $condition
     * @access public
     * @return array
     */
    public function getList($condition = 'all')
    {
        if(empty($condition)) $condition = 'all';
        
        $query = $this->dao->select('*')->from(TABLE_FEEDBACK);
        
        if($condition != 'all')
        {
            $query->where('status')->eq($condition);
        }
        
        return $query->fetchAll('', false);
    }
}
